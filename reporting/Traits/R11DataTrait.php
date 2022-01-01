<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');
require(dirname(__FILE__).'/../Traits/R10DataTrait.php');

// B Section from R10DataTrait
trait R11DataTrait {
    use R10DataTrait {
        R10DataTrait::data as r10data;
    }
    
    public function data($rpt_date=null) {
        // YTD
        $sdate = new DateTime($rpt_date->format('y-m-d'));
        $sdate->setDate( $sdate->format('Y'), 1, 1);
        $start_date = $sdate->format('y-m-d');
        $cur_year = $sdate->format('Y');

        $edate = new DateTime($rpt_date->format('y-m-d'));
        $edate->modify('+1 month');
        $end_date = $edate->format('y-m-d');
    
        $sdate->modify('-1 year');
        $yb_start_date = $sdate->format('y-m-d');
        $yb_year = $sdate->format('Y');
    
        $edate->modify('-1 year');
        $yb_end_date = $edate->format('y-m-d');
        $edate->modify('-1 month');
        $yb_rpt_date = $edate;
    
    
    
        error_log($start_date.' --> '.$end_date);
        
        
        $con = connect();
        
        // SECTION A
        $query = <<<SQL
                    SELECT
                        CASE WHEN c.donor_type='Rescued Food' THEN 'Rescued'
                            WHEN c.donor_type='Purchased Food' THEN 'Purchased'
                            WHEN c.donor_type='Food Drive Food' THEN 'Food Drive'
                            ELSE 'Transported Food Bank' END AS type,
                        SUM(IF(s.yr='$cur_year',s.weight,0)) as cur_weight,
                        SUM(IF(s.yr='$yb_year',s.weight,0)) as yb_weight,
                        CASE WHEN c.donor_type='Rescued Food' THEN 0
                            WHEN c.donor_type='Purchased Food' THEN 1
                            WHEN c.donor_type='Food Drive Food' THEN 2 ELSE 3 END AS orderby
                    from (
                        SELECT client,
                               CASE WHEN date >= '2021-01-01' THEN '2021' ELSE '2020' END as yr,
                               SUM(weight) as weight
                        FROM dbStops
                        WHERE (
                                (date >= '$start_date' AND date < '$end_date')
                                    OR
                                (date >= '$yb_start_date' AND date < '$yb_end_date')
                            )
                            -- AND type = 'pickup'
                            AND weight > 0
                        GROUP BY 1,2) s
                    JOIN dbClients c on c.id = s.client
                        AND c.type = 'donor'
                    GROUP BY 1,4
                    ORDER BY 4

SQL;
//        error_log($query);
        $result = mysqli_query ($con,$query);
        $theStops = array();
        
        $A = array();
        $cur_tot = 0;
        $yb_tot = 0;
        $cur_res_tot = 0;
        $yb_res_tot = 0;
        while ($result_row = mysqli_fetch_assoc($result)) {
            $type = $result_row['type'];
            $cur_weight = $result_row['cur_weight'];
            $yb_weight = $result_row['yb_weight'];
            $cur_tot += $cur_weight;
            $yb_tot += $yb_weight;
            $A[] = array(
                'type' => $type,
                'cur_weight' => $cur_weight,
                'yb_weight' => $yb_weight,
                'pcytd' => 0.0,
                'yoy_diff' => ($cur_weight - $yb_weight),
                'yoy_pct' => ($yb_weight == 0 ? null : (($cur_weight - $yb_weight) / $yb_weight) * 100.0)
            );
            if ($type == 'Rescued') {
                $cur_res_tot += $cur_weight;
                $yb_res_tot += $yb_weight;
            }
        }
        for ($x = 0; $x < count($A); $x++) {
            $A[$x]['pcytd'] = ($cur_tot == 0 ? null : ($A[$x]['cur_weight'] / $cur_tot) * 100.0);
        }
        $A[] = array(
            'type' => 'Total Food Received',
            'cur_weight' => $cur_tot,
            'yb_weight' => $yb_tot,
            'pcytd' => 100.0,
            'yoy_diff' => ($cur_tot - $yb_tot),
            'yoy_pct' => ($yb_tot == 0 ? null : (($cur_tot - $yb_tot)/ $yb_tot) * 100.0)
        );
    
        // SECTION C
        $query = <<<SQL
                SELECT da.deliveryAreaName,
                        CASE WHEN date >= '$start_date' THEN '$cur_year' ELSE '$yb_year' END as yr,
                        SUM(weight) as weight
                FROM dbStops s
                        JOIN dbClients c on c.id = s.client
                            AND c.type = 'donor'
                            AND c.donor_type = 'Rescued Food'
                        JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
                WHERE (
                    (date >= '$start_date' AND date < '$end_date')
                    OR
                    (date >= '$yb_start_date' AND date < '$yb_end_date')
                )
           --     AND s.type = 'pickup'
                AND weight > 0
                GROUP BY 1,2
SQL;
        $C = array(
            'Hilton Head' => array(null,null,null,null,null),
            'Bluffton' => array(null,null,null,null,null),
            'Beaufort' => array(null,null,null,null,null),
            'Total Rescued Food' => array(null,null,null,null,null),
        );
        $cur_tot = 0;
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
            $type = $result_row['deliveryAreaName'];
            $year = $result_row['yr'];
            $weight = $result_row['weight'];
            $cur_tot += $year == $cur_year ? $weight : 0;
            $C[$type][$year == $cur_year ? 0 : 1] += $weight;
            $C['Total Rescued Food'][$year == $cur_year ? 0 : 1] += $weight;
        }
        foreach($C as $key => $values) {
            $C[$key][2] = ($cur_tot == 0 ? null : ($values[0] / $cur_tot) * 100.0);;
            if ($key == 'Total Rescued Food') {
                $C[$key][2] = 100.0;
            }
            $C[$key][3] = ($values[0]- $values[1]);
            $C[$key][4] = ($values[1] == 0 ? null : (($values[0] - $values[1]) / $values[1]) * 100.0);
        }
    
    
        // SECTION D
        $query = <<<SQL
                SELECT RIGHT(route,3) as area,
                       CASE WHEN RIGHT(route,3) = 'HHI' THEN 0
                            WHEN RIGHT(route,3) = 'SUN' THEN 1 ELSE 2 END as orderBy,
                        CASE WHEN date >= '$start_date' THEN '$cur_year' ELSE '$yb_year' END as yr,
                        SUM(weight) as weight
                FROM dbStops s
                        JOIN dbClients c on c.id = s.client
                            AND c.type = 'donor'
                            AND c.donor_type = 'Rescued Food'
                WHERE (
                    (date >= '$start_date' AND date < '$end_date')
                    OR
                    (date >= '$yb_start_date' AND date < '$yb_end_date')
                )
        --        AND s.type = 'pickup'
                AND weight > 0
                GROUP BY 1,2,3
                ORDER BY 2
SQL;
        $D = array(
            'Hilton Head' => array(null,null,null,null,null),
            'Bluffton' => array(null,null,null,null,null),
            'Beaufort' => array(null,null,null,null,null),
            'Total Rescued Food' => array(null,null,null,null,null),
        );
        $cur_tot = 0;
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
            $type = $result_row['area'];
            if ($type == 'HHI') $type='Hilton Head';
            if ($type == 'SUN') $type='Bluffton';
            if ($type == 'BFT') $type='Beaufort';
            $year = $result_row['yr'];
            $weight = $result_row['weight'];
            $cur_tot += $year == $cur_year ? $weight : 0;
            $D[$type][$year == $cur_year ? 0 : 1] += $weight;
            $D['Total Rescued Food'][$year == $cur_year ? 0 : 1] += $weight;
        }
        foreach($D as $key => $values) {
            $D[$key][2] = ($cur_tot == 0 ? null : ($values[0] / $cur_tot) * 100.0);;
            if ($key == 'Total Rescued Food') {
                $D[$key][2] = 100.0;
            }
            $D[$key][3] = ($values[0]- $values[1]);
            $D[$key][4] = ($values[1] == 0 ? null : (($values[0] - $values[1]) / $values[1]) * 100.0);
        }
        
        
        // SECTION E
        $query = <<<SQL
                SELECT county,
                        CASE WHEN date >= '$start_date' THEN '$cur_year' ELSE '$yb_year' END  as yr,
                        SUM(weight) as weight
                FROM dbStops s
                        JOIN dbClients c on c.id = s.client
                            AND c.type = 'recipient'
                WHERE (
                    (date >= '$start_date' AND date < '$end_date')
                    OR
                    (date >= '$yb_start_date' AND date < '$yb_end_date')
                )
                -- AND s.type = 'dropoff'
                AND weight > 0
                GROUP BY 1,2
                ORDER BY 3 desc
SQL;
        $E = array(
            'Beaufort Co. Agencies' => array(null,null,null,null,null),
            'Jasper Co. Agencies' => array(null,null,null,null,null),
            'Hampton Co. Agencies' => array(null,null,null,null,null),
            'Total Distributed Food' => array(null,null,null,null,null),
        );
        $cur_tot = 0;
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
            $type = $result_row['county'].' Co. Agencies';
            $year = $result_row['yr'];
            $weight = $result_row['weight'];
            $cur_tot += $year == $cur_year ? $weight : 0;
            $E[$type][$year == $cur_year ? 0 : 1] += $weight;
            $E['Total Distributed Food'][$year == $cur_year ? 0 : 1] += $weight;
        }
        foreach($E as $key => $values) {
            $E[$key][2] = ($cur_tot == 0 ? null : ($values[0] / $cur_tot) * 100.0);;
            if ($key == 'Total Distributed Food') {
                $E[$key][2] = 100.0;
            }
            $E[$key][3] = ($values[0]- $values[1]);
            $E[$key][4] = ($values[1] == 0 ? null : (($values[0] - $values[1]) / $values[1]) * 100.0);
        }
    
    
        // SECTION F
        $query = <<<SQL
                SELECT s.client,
                       c.area,
                        SUM(IF(date >= '$start_date',weight,0)) as cur_weight,
                        SUM(IF(date < '$yb_end_date',weight,0)) as yb_weight
                FROM dbStops s
                        JOIN dbClients c on c.id = s.client
                            AND c.type = 'donor'
                WHERE (
                    (date >= '$start_date' AND date < '$end_date')
                    OR
                    (date >= '$yb_start_date' AND date < '$yb_end_date')
                )
                -- AND s.type = 'pickup'
                AND weight > 0
                AND s.client in (
                    'Sams Club 6582',
                    'Publix HH North (473)',
                    'Publix Rte 278 (845)',
                    'WalMart Hardeeville (2832)',
                    'Walmart Ladys Island (7181)'
                )
                GROUP BY 1
                ORDER BY 3 desc
SQL;
        $F = array();
        $cur_tot = 0;
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
            $client = $result_row['client'];
//            $area = $result_row['area'];
            $area = 'unknown';
            if ($result_row['area'] == 'HHI') $area = 'Hilton Head';
            if ($result_row['area'] == 'BFT') $area = 'Beaufort';
            if ($result_row['area'] == 'SUN') $area = 'Bluffton';
//            $area = $result_row['area'] == 'HHI' ? 'Hilton Head' : $result_row['area'] == 'BFT' ?  "Beaufort" : 'Bluffton';
            $cur_weight = $result_row['cur_weight'];
            $yb_weight = $result_row['yb_weight'];
            $cur_tot += $cur_weight;
            $F[$client.'   ('.$area.' Area)'] = array(
                $cur_weight,
                $yb_weight,
                null,
                $cur_weight - $yb_weight,
                ($yb_weight == 0 ? null : (($cur_weight - $yb_weight) / $yb_weight) * 100.0)
            );
        }
        foreach($F as $key => $values) {
            $F[$key][2] = ($cur_res_tot == 0 ? null : ($values[0] / $cur_res_tot) * 100.0);;
        }
    
    
    
    
    
        // SECTION G
        $query = <<<SQL
                SELECT s.client,
                        SUM(IF(date >= '$start_date',weight,0)) as cur_weight,
                        SUM(IF(date < '$yb_end_date',weight,0)) as yb_weight,
                        SUM(IF(date >= '$start_date',weight,0)-IF(date < '$yb_end_date',weight,0)) as diff
                FROM dbStops s
                        JOIN dbClients c on c.id = s.client
                            AND c.type = 'recipient'
                WHERE (
                    (date >= '$start_date' AND date < '$end_date')
                    OR
                    (date >= '$yb_start_date' AND date < '$yb_end_date')
                )
                -- AND s.type = 'dropoff'
                AND weight > 0
                GROUP BY 1
                ORDER BY 4 desc
SQL;
        $G = array();
        $cur_tot = 0;
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
            $client = $result_row['client'];
            $cur_weight = $result_row['cur_weight'];
            $yb_weight = $result_row['yb_weight'];
            $cur_tot += $cur_weight;
            $G[$client] = array(
                $cur_weight,
                $yb_weight,
                null,
                $cur_weight - $yb_weight,
                ($yb_weight == 0 ? null : (($cur_weight - $yb_weight) / $yb_weight) * 100.0)
            );
        }
        $G = array_merge(array_slice($G,0,3),array_slice($G, -3));
        foreach($G as $key => $values) {
            $G[$key][2] = ($cur_tot == 0 ? null : ($values[0] / $cur_tot) * 100.0);;
        }
    
    
    
        // SECTION CHART
        $query = <<<SQL
                SELECT	SUBSTR(date,4,2)as month,
                    CAST(ROUND(SUM(IF(date >= '21-01-01',weight,0)),-3)/1000 AS UNSIGNED) as cur_weight,
					CAST(ROUND(SUM(IF(date < '21-01-01',weight,0)),-3)/1000 AS UNSIGNED) as yb_weight
                FROM dbStops s
                        JOIN dbClients c on c.id = s.client
                            AND c.type = 'donor'
                            AND c.donor_type = 'Rescued Food'
                WHERE (
                    (date >= '$start_date' AND date < '$end_date')
                    OR
                    (date >= '$yb_start_date' AND date < '$start_date')
                )
                -- AND s.type = 'pickup'
                AND weight > 0
                GROUP BY 1
                ORDER BY 1
SQL;
        $CHART = array(
            'xticks' => array_values($this->months),
            'data1' => array_fill(0,12,null),
            'data2' => array_fill(0,12,null),
        );
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
            $month = (int)$result_row['month'];
            $CHART['data1'][$month-1] = $result_row['yb_weight'];
            $CHART['data2'][$month-1] = $result_row['cur_weight'];
        }
    
    
    
        mysqli_close($con);
    
        $B = array(
            'Meat' => array(null,null,null,null,null),
            'Deli' => array(null,null,null,null,null),
            'Bakery' => array(null,null,null,null,null),
            'Grocery' => array(null,null,null,null,null),
            'Dairy' => array(null,null,null,null,null),
            'Produce' => array(null,null,null,null,null),
            'Total Rescued Food' => array(null,null,null,null,null),
        );

        // Current Year Data
        $bData = $this->r10data($rpt_date);
        $B['Meat'][0] += $bData['food']['Rescued Food']['YTD'][0];
        $B['Deli'][0] += $bData['food']['Rescued Food']['YTD'][1];
        $B['Bakery'][0] += $bData['food']['Rescued Food']['YTD'][2];
        $B['Grocery'][0] += $bData['food']['Rescued Food']['YTD'][3];
        $B['Dairy'][0] += $bData['food']['Rescued Food']['YTD'][4];
        $B['Produce'][0] += $bData['food']['Rescued Food']['YTD'][5];
        $B['Total Rescued Food'][0] += $B['Meat'][0];
        $B['Total Rescued Food'][0] += $B['Deli'][0];
        $B['Total Rescued Food'][0] += $B['Bakery'][0];
        $B['Total Rescued Food'][0] += $B['Grocery'][0];
        $B['Total Rescued Food'][0] += $B['Dairy'][0];
        $B['Total Rescued Food'][0] += $B['Produce'][0];
    
        // Current Year Data
        $bData = $this->r10data($yb_rpt_date);
        $B['Meat'][1] += $bData['food']['Rescued Food']['YTD'][0];
        $B['Deli'][1] += $bData['food']['Rescued Food']['YTD'][1];
        $B['Bakery'][1] += $bData['food']['Rescued Food']['YTD'][2];
        $B['Grocery'][1] += $bData['food']['Rescued Food']['YTD'][3];
        $B['Dairy'][1] += $bData['food']['Rescued Food']['YTD'][4];
        $B['Produce'][1] += $bData['food']['Rescued Food']['YTD'][5];
        $B['Total Rescued Food'][1] += $B['Meat'][1];
        $B['Total Rescued Food'][1] += $B['Deli'][1];
        $B['Total Rescued Food'][1] += $B['Bakery'][1];
        $B['Total Rescued Food'][1] += $B['Grocery'][1];
        $B['Total Rescued Food'][1] += $B['Dairy'][1];
        $B['Total Rescued Food'][1] += $B['Produce'][1];
    
        return array(
            'cur_yr' => $cur_year,
            'yb_yr' => $yb_year,
            'A' => $A,
            'B' => $B,
            'C' => $C,
            'D' => $D,
            'E' => $E,
            'F' => $F,
            'G' => $G,
            'CHART' => $CHART
        );
    }
}