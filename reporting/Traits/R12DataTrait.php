<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R12DataTrait {
    
    public function data($start_date=null,$end_date) {
        
        $con = connect();
        
        $query = <<<SQL
            SELECT s.client,
                    c.area,
                    c.county,
                    CASE WHEN c.county = 'Beaufort' THEN 0
                        WHEN c.county = 'Jasper' THEN 1 ELSE 2 END AS orderBy1,
                    CASE WHEN c.area = 'BFT' THEN 0
                        WHEN c.area = 'SUN' THEN 1 ELSE 2 END AS orderBy2,
                    CUR.weight as del_food,
                    CUR.transported_weight AS transported_weight,
                    CUR.purchased_weight AS purchased_weight,
                    CUR.food_drive_weight AS food_drive_weight,
                    CUR.adj_weight  AS adj_del_food,
                    CUR.ns1+CUR.ns2+CUR.ns3 AS num_served_week,
                    CUR.adj_weight / CUR.weeks_in_report AS adj_del_food_week,
                    CUR.adj_weight / CUR.weeks_in_report / (CUR.ns1+CUR.ns2+CUR.ns3) AS adj_perperson,
                    CUR.weeks_in_report AS weeks_in_report
                 FROM dbStops s
                    JOIN dbClients c ON c.id = s.client
                        AND c.type = 'recipient'
                        AND c.number_served IS NOT NULL
                        AND char_length(c.number_served) > 2
                    JOIN (
                        SELECT s.client,
                            SUM(weight) AS weight,
                            cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 1), ',', -1) AS UNSIGNED ) ns1,
                            cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 2), ',', -1) AS UNSIGNED ) ns2,
                            cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 3), ',', -1) AS UNSIGNED ) ns3,
                            SUM(transported_weight) AS transported_weight,
                            SUM(purchased_weight) AS purchased_weight,
                            SUM(food_drive_weight) AS food_drive_weight,
                            SUM(weight-transported_weight-purchased_weight-food_drive_weight) AS adj_weight,
                            (DATEDIFF('$end_date', '$start_date')/7) as weeks_in_report
                        FROM dbStops s
                        JOIN dbClients c ON c.id = s.client
                            AND c.type = 'recipient'
                            AND c.number_served IS NOT NULL
                            AND char_length(c.number_served) > 2
                        WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                               AND s.weight > 0
                        GROUP BY 1
                       ORDER BY 1
                    ) CUR ON CUR.client = s.client
                        AND (CUR.ns1 + CUR.ns2 + CUR.ns3) > 0
                    
            WHERE (s.date >= '$start_date' AND s.date < '$end_date')
            AND s.type='dropoff'
            AND s.weight > 0
            group by 4,5,1
            order by 4,5,13 desc
SQL;
//                error_log($query);
    
        $weeks_in_report = 0;
        $data = array();
        $totals = array(
            'BFT' => array(1,'Beaufort Area',null,null,null,null,null,null,null,null),
            'SUN' => array(1,'Bluffton Area',null,null,null,null,null,null,null,null),
            'HHI' => array(1,'Hilton Head Area',null,null,null,null,null,null,null,null),
            'Beaufort' => array(2,'Beaufort Co.',null,null,null,null,null,null,null,null),
            'Jasper' => array(2,'Jasper Co.',null,null,null,null,null,null,null,null),
            'Hampton' => array(2,'Hampton Co.',null,null,null,null,null,null,null,null),
            'Total of Above' => array(3,'Total of Above',null,null,null,null,null,null,null,null),
        );
        $result = mysqli_query ($con,$query);
        $in_county = 'Beaufort';
        $in_area = 'BFT';
        while ($result_row = mysqli_fetch_assoc($result)) {
            if ($weeks_in_report == 0) $weeks_in_report = floatval($result_row['weeks_in_report']);
            $county = $result_row['county'];
            $area = $result_row['area'];
            
            // only change areas when in Beaufort county
            if ($area != $in_area && $in_county == 'Beaufort') {
                $totals[$in_area][8] = $totals[$in_area][6] / $weeks_in_report;
                $totals[$in_area][9] = $totals[$in_area][6] / $weeks_in_report / $totals[$in_area][7];
                $data[] = $totals[$in_area];
                $in_area = $area;
            }
            if ($county != $in_county) {
                $totals[$in_county][8] = $totals[$in_county][6] / $weeks_in_report;
                $totals[$in_county][9] = $totals[$in_county][6] / $weeks_in_report / $totals[$in_county][7];
                $data[] = $totals[$in_county];
                $in_county = $county;
            }
            
            if ($in_county == 'Beaufort') {
                $totals[$in_area][2] += $result_row['del_food'];
                $totals[$in_area][3] += $result_row['transported_weight'];
                $totals[$in_area][4] += $result_row['purchased_weight'];
                $totals[$in_area][5] += $result_row['food_drive_weight'];
                $totals[$in_area][6] += $result_row['adj_del_food'];
                $totals[$in_area][7] += $result_row['num_served_week'];
            }
            
            $totals[$in_county][2] += $result_row['del_food'];
            $totals[$in_county][3] += $result_row['transported_weight'];
            $totals[$in_county][4] += $result_row['purchased_weight'];
            $totals[$in_county][5] += $result_row['food_drive_weight'];
            $totals[$in_county][6] += $result_row['adj_del_food'];
            $totals[$in_county][7] += $result_row['num_served_week'];
    
            $totals['Total of Above'][2] += $result_row['del_food'];
            $totals['Total of Above'][3] += $result_row['transported_weight'];
            $totals['Total of Above'][4] += $result_row['purchased_weight'];
            $totals['Total of Above'][5] += $result_row['food_drive_weight'];
            $totals['Total of Above'][6] += $result_row['adj_del_food'];
            $totals['Total of Above'][7] += $result_row['num_served_week'];
    
            $data[] = array(
                0,
                $result_row['client'],
                $result_row['del_food'],
                $result_row['transported_weight'],
                $result_row['purchased_weight'],
                $result_row['food_drive_weight'],
                $result_row['adj_del_food'],
                $result_row['num_served_week'],
                $result_row['adj_del_food_week'],
                $result_row['adj_perperson']
            );
    
        }
    
        if ($in_county == 'Beaufort') {
            $totals[$in_area][8] = $totals[$in_area][6] / $weeks_in_report;
            $totals[$in_area][9] = $totals[$in_area][6] / $weeks_in_report / $totals[$in_area][7];
            $data[] = $totals[$in_area];
        }
        $totals[$in_county][8] = $totals[$in_county][6] / $weeks_in_report;
        $totals[$in_county][9] = $totals[$in_county][6] / $weeks_in_report / $totals[$in_county][7];
        $data[] = $totals[$in_county];
    
        $totals['Total of Above'][8] = $totals['Total of Above'][6] / $weeks_in_report;
        $totals['Total of Above'][9] = $totals['Total of Above'][6] / $weeks_in_report / $totals['Total of Above'][7];
        $data[] = $totals['Total of Above'];
    
        return array(
            'weeks_in_report' => number_format($weeks_in_report,1),
            'rows' => $data
        );
    }
}