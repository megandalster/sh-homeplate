<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R14DataTrait {
    public $date_label = '';
    
    public function data($rpt_date=null) {
        // start Date is -6 months
        $s_date = new DateTime($rpt_date->format('y-m-d'));
        $s_date->modify('-5 month');
        $start_date = $s_date->format('y-m-d');
        $this->date_label = $s_date->format('M').'-';
        
        
        $s_date->modify('+5 month');
        $this->date_label .= $s_date->format('M Y');

        $s_date->modify('+1 month');
        $end_date = $s_date->format('y-m-d');
    
        error_log($start_date.' --> '.$end_date);
        
        
        $pickups = array();
        $total = 0;
        
        $con = connect();

        $query = <<<SQL
                select client,
                    CAST(mon as UNSIGNED) as mon,
                    CAST(tue as UNSIGNED) as tue,
                    CAST(wed as UNSIGNED) as wed,
                    CAST(thu as UNSIGNED) as thu,
                    CAST(fri as UNSIGNED) as fri,
                    CAST(sat as UNSIGNED) as sat,
                    CAST(IFNULL(mon,0)+IFNULL(tue,0)+IFNULL(wed,0)+IFNULL(thu,0)+IFNULL(fri,0)+IFNULL(sat,0) as UNSIGNED) as total,
                    daOrderBy,
                    area
                from (
                    SELECT REPLACE(s.client,'  ',' ') as client,
                        da.deliveryAreaName as area,
                        CASE
                            WHEN da.deliveryAreaName='Hilton Head' THEN 3
                            WHEN da.deliveryAreaName='Bluffton' THEN 2
                            WHEN da.deliveryAreaName='Beaufort' THEN 1 ELSE 4 END AS daOrderBy,
                        AVG(IF(WEEKDAY(date)=0,s.weight,0)) as mon,
                        AVG(IF(WEEKDAY(date)=1,s.weight,0)) as tue,
                        AVG(IF(WEEKDAY(date)=2,s.weight,0)) as wed,
                        AVG(IF(WEEKDAY(date)=3,s.weight,0)) as thu,
                        AVG(IF(WEEKDAY(date)=4,s.weight,0)) as fri,
                        AVG(IF(WEEKDAY(date)=5,s.weight,0)) as sat
                    FROM dbStops s
                        JOIN dbClients c ON c.id = s.client
                            AND c.type = 'donor'
                            AND c.donor_type = 'Rescued Food'
                            AND c.chain_name != ''
                        LEFT JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
                    WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                    --    AND s.type = 'pickup'
                        AND s.weight > 0
                    GROUP BY 1,2
                    ORDER BY 3,1
                ) x
SQL;
    
    
        $query = <<<SQL
        select client,
            CAST(mon as UNSIGNED) as mon,
            CAST(tue as UNSIGNED) as tue,
            CAST(wed as UNSIGNED) as wed,
            CAST(thu as UNSIGNED) as thu,
            CAST(fri as UNSIGNED) as fri,
            CAST(sat as UNSIGNED) as sat,
            CAST(IFNULL(mon,0)+IFNULL(tue,0)+IFNULL(wed,0)+IFNULL(thu,0)+IFNULL(fri,0)+IFNULL(sat,0) as UNSIGNED) as total,
            daOrderBy,
            area
        from (
            SELECT REPLACE(s.client,'  ',' ') as client,
                    da.deliveryAreaName as area,
                    CASE
                        WHEN da.deliveryAreaName='Hilton Head' THEN 3
                      	WHEN da.deliveryAreaName='Bluffton' THEN 2
                        WHEN da.deliveryAreaName='Beaufort' THEN 1 ELSE 4 END AS daOrderBy,
                    SUM(IF(WEEKDAY(date)=0,s.weight,0)) / MAX(cts.mon_count) as mon,
                    SUM(IF(WEEKDAY(date)=1,s.weight,0)) / MAX(cts.tue_count) as tue,
                    SUM(IF(WEEKDAY(date)=2,s.weight,0)) / MAX(cts.wed_count) as wed,
                    SUM(IF(WEEKDAY(date)=3,s.weight,0)) / MAX(cts.thu_count) as thu,
                    SUM(IF(WEEKDAY(date)=4,s.weight,0)) / MAX(cts.fri_count) as fri,
                    SUM(IF(WEEKDAY(date)=5,s.weight,0)) / MAX(cts.sat_count) as sat
                FROM dbStops s
                    JOIN dbClients c ON c.id = s.client
                        AND c.type = 'donor'
                        AND c.donor_type = 'Rescued Food'
    					AND c.chain_name != ''
                    LEFT JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
                    
                -- WEEKDAY COUNT GENERATOR
                JOIN (
                    select 'ALWAYS' as always
                        ,SUM(IF(WEEKDAY(v.selected_date)=0,1,0)) as mon_count
                        ,SUM(IF(WEEKDAY(v.selected_date)=1,1,0)) as tue_count
                        ,SUM(IF(WEEKDAY(v.selected_date)=2,1,0)) as wed_count
                        ,SUM(IF(WEEKDAY(v.selected_date)=3,1,0)) as thu_count
                        ,SUM(IF(WEEKDAY(v.selected_date)=4,1,0)) as fri_count
                        ,SUM(IF(WEEKDAY(v.selected_date)=5,1,0)) as sat_count
                    from
                    (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
                    WHERE (v.selected_date >= '20$start_date' AND v.selected_date < '20$end_date')
                ) cts ON cts.always='ALWAYS'

                WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                --    AND s.type = 'pickup'
                    AND s.weight > 0
                GROUP BY 1,2
                ORDER BY 3,1
    ) x
SQL;

//        error_log($query);
        $result = mysqli_query ($con,$query);
        if (!$result) {
            error_log(mysqli_error($con). "\n");
            mysqli_close($con);
            return false;
        }
        $row = null;
        $area = null;
        $subtotals = array(
            'mon' => 0,
            'tue' => 0,
            'wed' => 0,
            'thu' => 0,
            'fri' => 0,
            'sat' => 0,
            'tot' => 0
        );
        $totals = array(
            'mon' => 0,
            'tue' => 0,
            'wed' => 0,
            'thu' => 0,
            'fri' => 0,
            'sat' => 0,
            'tot' => 0
        );
    
        while ($result_row = mysqli_fetch_assoc($result)) {
            $a = $result_row['area'];
            if ($area != $a) {
                if ($area != null) {
                    $pickups[] = array(
                        'client' => $area.' Key Rescued Food',
                        'istotal' => true,
                        'mon' => $subtotals['mon'],
                        'tue' => $subtotals['tue'],
                        'wed' => $subtotals['wed'],
                        'thu' => $subtotals['thu'],
                        'fri' => $subtotals['fri'],
                        'sat' => $subtotals['sat'],
                        'tot' => $subtotals['tot']
                    );
                }
                $subtotals['mon'] = 0;
                $subtotals['tue'] = 0;
                $subtotals['wed'] = 0;
                $subtotals['thu'] = 0;
                $subtotals['fri'] = 0;
                $subtotals['sat'] = 0;
                $subtotals['tot'] = 0;
    
                $area = $a;
            }
            $pickups[] = array(
                'client' => $result_row['client'],
                'istotal' => false,
                'mon' => $result_row['mon'],
                'tue' => $result_row['tue'],
                'wed' => $result_row['wed'],
                'thu' => $result_row['thu'],
                'fri' => $result_row['fri'],
                'sat' => $result_row['sat'],
                'tot' => $result_row['total']
            );
            $subtotals['mon'] += $result_row['mon'];
            $subtotals['tue'] += $result_row['tue'];
            $subtotals['wed'] += $result_row['wed'];
            $subtotals['thu'] += $result_row['thu'];
            $subtotals['fri'] += $result_row['fri'];
            $subtotals['sat'] += $result_row['sat'];
            $subtotals['tot'] += $result_row['total'];

            $totals['mon'] += $result_row['mon'];
            $totals['tue'] += $result_row['tue'];
            $totals['wed'] += $result_row['wed'];
            $totals['thu'] += $result_row['thu'];
            $totals['fri'] += $result_row['fri'];
            $totals['sat'] += $result_row['sat'];
            $totals['tot'] += $result_row['total'];
        }
        $pickups[] = array(
            'client' => $area.' Key Rescued Food',
            'istotal' => true,
            'mon' => $subtotals['mon'],
            'tue' => $subtotals['tue'],
            'wed' => $subtotals['wed'],
            'thu' => $subtotals['thu'],
            'fri' => $subtotals['fri'],
            'sat' => $subtotals['sat'],
            'tot' => $subtotals['tot']
        );
        $pickups[] = array(
            'client' => 'Total Key Rescued Food',
            'istotal' => true,
            'mon' => $totals['mon'],
            'tue' => $totals['tue'],
            'wed' => $totals['wed'],
            'thu' => $totals['thu'],
            'fri' => $totals['fri'],
            'sat' => $totals['sat'],
            'tot' => $totals['tot']
        );
    
    
        mysqli_close($con);
        return array(
            'pickups' => $pickups,
        );
    }
}