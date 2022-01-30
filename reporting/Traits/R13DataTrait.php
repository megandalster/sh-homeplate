<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R13DataTrait {
    
    public function data($start_date,$end_date,$ytd_start_date,$m_pppspw,$ytd_pppspw) {
        
        $con = connect();
        
        $query = <<<SQL
            SELECT s.client,
                    c.area,
                    c.county,
                    CASE WHEN c.county = 'Beaufort' THEN 0
                        WHEN c.county = 'Jasper' THEN 1 ELSE 2 END AS orderBy1,
                    CASE WHEN c.area = 'BFT' THEN 0
                        WHEN c.area = 'SUN' THEN 1 ELSE 2 END AS orderBy2,
                    YTD.nsw AS m_nsw,
                    $m_pppspw AS m_pppspw,
                    YTD.nsw * $m_pppspw AS m_tnpw,
                    CUR.adj_weight / CUR.weeks_in_report AS m_aadpw,
                    (IFNULL(CUR.adj_weight,0) / (DATEDIFF('$end_date', '$start_date')/7)) - (YTD.nsw * $m_pppspw) AS m_variance,

                    YTD.nsw AS ytd_nsw,
                    YTD.pppspw AS ytd_pppspw,
                    YTD.nsw * YTD.pppspw AS ytd_tnpw,
                    YTD.adj_weight / YTD.weeks_in_report AS ytd_aadpw,
                    (YTD.adj_weight / YTD.weeks_in_report) - (YTD.nsw * YTD.pppspw) AS ytd_variance
                 FROM dbStops s
                    JOIN dbClients c ON c.id = s.client
                        AND c.type = 'recipient'
                        AND c.number_served IS NOT NULL
                        AND char_length(c.number_served) > 2
                    LEFT JOIN (
                        SELECT s.client,
                               $m_pppspw as pppspw,
                            (cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 1), ',', -1) AS UNSIGNED ) +
                            cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 2), ',', -1) AS UNSIGNED ) +
                            cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 3), ',', -1) AS UNSIGNED )) nsw,
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
                        AND CUR.nsw > 0

                    JOIN (
                        SELECT s.client,
                               $ytd_pppspw as pppspw,
                            (cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 1), ',', -1) AS UNSIGNED ) +
                            cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 2), ',', -1) AS UNSIGNED ) +
                            cast(SUBSTRING_INDEX(SUBSTRING_INDEX(c.number_served, ',', 3), ',', -1) AS UNSIGNED )) nsw,
                            SUM(weight-transported_weight-purchased_weight-food_drive_weight) AS adj_weight,
                            (DATEDIFF('$end_date', '$ytd_start_date')/7) as weeks_in_report
                        FROM dbStops s
                        JOIN dbClients c ON c.id = s.client
                            AND c.type = 'recipient'
                            AND c.number_served IS NOT NULL
                            AND char_length(c.number_served) > 2
                        WHERE (s.date >= '$ytd_start_date' AND s.date < '$end_date')
                               AND s.weight > 0
                        GROUP BY 1
                       ORDER BY 1
                    ) YTD ON YTD.client = s.client
                        AND YTD.nsw > 0

            WHERE (s.date >= '$ytd_start_date' AND s.date < '$end_date')
            AND s.type='dropoff'
            AND YTD.adj_weight > 0
            group by 4,5,1
            order by 4,5,15 desc
SQL;
        $data = array();
        $totals = array(
            'BFT' => array(1,'Beaufort Agencies Total',null,null,null,null,null,null,null,null,null),
            'SUN' => array(1,'Bluffton Agencies Total',null,null,null,null,null,null,null,null,null),
            'HHI' => array(1,'Hilton Head Agencies Total',null,null,null,null,null,null,null,null,null),
            'Beaufort' => array(2,'Beaufort Co. Agencies Total',null,null,null,null,null,null,null,null,null),
            'Jasper' => array(2,'Jasper Co. Agencies Total',null,null,null,null,null,null,null,null,null),
            'Hampton' => array(2,'Hampton Co. Agencies Total',null,null,null,null,null,null,null,null,null),
            'Total of Above' => array(3,'Second Helpings Total',null,null,null,null,null,null,null,null,null),
        );
        $result = mysqli_query ($con,$query);
        $in_county = 'Beaufort';
        $in_area = 'BFT';
        while ($result_row = mysqli_fetch_assoc($result)) {
            $county = $result_row['county'];
            $area = $result_row['area'];
            
            // only change areas when in Beaufort county
            if ($area != $in_area && $in_county == 'Beaufort') {
                $data[] = $totals[$in_area];
                $in_area = $area;
            }
            if ($county != $in_county) {
                $data[] = $totals[$in_county];
                $in_county = $county;
            }
            
            if ($in_county == 'Beaufort') {
                $totals[$in_area][2] += $result_row['m_nsw'];
                $totals[$in_area][4] += $result_row['m_tnpw'];
                $totals[$in_area][5] += $result_row['m_aadpw'];
                $totals[$in_area][6] += $result_row['m_variance'];
                $totals[$in_area][8] += $result_row['ytd_tnpw'];
                $totals[$in_area][9] += $result_row['ytd_aadpw'];
                $totals[$in_area][10] += $result_row['ytd_variance'];
            }
    
            $totals[$in_county][2] += $result_row['m_nsw'];
            $totals[$in_county][4] += $result_row['m_tnpw'];
            $totals[$in_county][5] += $result_row['m_aadpw'];
            $totals[$in_county][6] += $result_row['m_variance'];
            $totals[$in_county][8] += $result_row['ytd_tnpw'];
            $totals[$in_county][9] += $result_row['ytd_aadpw'];
            $totals[$in_county][10] += $result_row['ytd_variance'];
    
            $totals['Total of Above'][2] += $result_row['m_nsw'];
            $totals['Total of Above'][4] += $result_row['m_tnpw'];
            $totals['Total of Above'][5] += $result_row['m_aadpw'];
            $totals['Total of Above'][6] += $result_row['m_variance'];
            $totals['Total of Above'][8] += $result_row['ytd_tnpw'];
            $totals['Total of Above'][9] += $result_row['ytd_aadpw'];
            $totals['Total of Above'][10] += $result_row['ytd_variance'];
    
            $data[] = array(
                0,
                $result_row['client'],
                $result_row['m_nsw'],
                $result_row['m_pppspw'],
                $result_row['m_tnpw'],
                $result_row['m_aadpw'],
                $result_row['m_variance'],
                $result_row['ytd_pppspw'],
                $result_row['ytd_tnpw'],
                $result_row['ytd_aadpw'],
                $result_row['ytd_variance']
            );
    
        }
    
        if ($in_county == 'Beaufort') {
            $data[] = $totals[$in_area];
        }
        $data[] = $totals[$in_county];
    
        $data[] = $totals['Total of Above'];
    
        return array(
            'rows' => $data
        );
    }
}