<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R10DataTrait {
    
    public function data($rpt_date=null) {
        
        // end datae exlusive
        $tmpdate = new DateTime($rpt_date->format('y-m').'-01');
        $start_date = $tmpdate->format('y').'-01-01';
        
        
        $tmpdate->modify('+1 month');
        $end_date = $tmpdate->format('y-m-d');
    
//        $s_date = strtotime('first day of '.$end_date->format('Y'));
//        $start_date->setTimestamp($s_date);
//        $sdate = new DateTime($edate->format('Y').'-01-01');

        error_log($start_date.' < '.$end_date);
        
        
        $food = array(
            'Rescued Food' => array(
                1 => array(null,null,null,null,null,null,null),
                2 => array(null,null,null,null,null,null,null),
                3 => array(null,null,null,null,null,null,null),
                4 => array(null,null,null,null,null,null,null),
                5 => array(null,null,null,null,null,null,null),
                6 => array(null,null,null,null,null,null,null),
                7 => array(null,null,null,null,null,null,null),
                8 => array(null,null,null,null,null,null,null),
                9 => array(null,null,null,null,null,null,null),
                10 => array(null,null,null,null,null,null,null),
                11 => array(null,null,null,null,null,null,null),
                12 => array(null,null,null,null,null,null,null),
                'YTD' => array(null,null,null,null,null,null,null)
            ),
            'Transported Food' => array(
                1 => array(null,null,null,null,null,null,null),
                2 => array(null,null,null,null,null,null,null),
                3 => array(null,null,null,null,null,null,null),
                4 => array(null,null,null,null,null,null,null),
                5 => array(null,null,null,null,null,null,null),
                6 => array(null,null,null,null,null,null,null),
                7 => array(null,null,null,null,null,null,null),
                8 => array(null,null,null,null,null,null,null),
                9 => array(null,null,null,null,null,null,null),
                10 => array(null,null,null,null,null,null,null),
                11 => array(null,null,null,null,null,null,null),
                12 => array(null,null,null,null,null,null,null),
                'YTD' => array(null,null,null,null,null,null,null)
            ),
            'Purchased Food' => array(
                1 => array(null,null,null,null,null,null,null),
                2 => array(null,null,null,null,null,null,null),
                3 => array(null,null,null,null,null,null,null),
                4 => array(null,null,null,null,null,null,null),
                5 => array(null,null,null,null,null,null,null),
                6 => array(null,null,null,null,null,null,null),
                7 => array(null,null,null,null,null,null,null),
                8 => array(null,null,null,null,null,null,null),
                9 => array(null,null,null,null,null,null,null),
                10 => array(null,null,null,null,null,null,null),
                11 => array(null,null,null,null,null,null,null),
                12 => array(null,null,null,null,null,null,null),
                'YTD' => array(null,null,null,null,null,null,null)
            ),
            'Food Drive Food' => array(
                1 => array(null,null,null,null,null,null,null),
                2 => array(null,null,null,null,null,null,null),
                3 => array(null,null,null,null,null,null,null),
                4 => array(null,null,null,null,null,null,null),
                5 => array(null,null,null,null,null,null,null),
                6 => array(null,null,null,null,null,null,null),
                7 => array(null,null,null,null,null,null,null),
                8 => array(null,null,null,null,null,null,null),
                9 => array(null,null,null,null,null,null,null),
                10 => array(null,null,null,null,null,null,null),
                11 => array(null,null,null,null,null,null,null),
                12 => array(null,null,null,null,null,null,null),
                'YTD' => array(null,null,null,null,null,null,null)
            )
        );
        
        $con = connect();
        $query = <<<SQL
                SELECT CAST(date AS UNSIGNED) as date,
                       food_type,
                       orderby,
                        SUM(weight) AS weight,
                        SUM(meat) AS meat,
                        SUM(deli) AS deli,
                        SUM(bakery) AS bakery,
                        SUM(grocery) AS grocery,
                        SUM(dairy) AS dairy,
                        SUM(produce) AS produce
                FROM (
                    SELECT
                        SUBSTRING(s.date,4,2) AS date,
                        c.donor_type as food_type,
                        CASE WHEN c.donor_type='Rescued Food' THEN 0
                            WHEN c.donor_type='Transported Food' THEN 1
                            WHEN c.donor_type='Purchased Food' THEN 2 ELSE 3 END AS orderby,
                        s.weight AS weight,
                        s.items,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(s.items, ',', 1), ':', -1) AS meat,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(s.items, ',', 2), ':', -1) AS deli,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(s.items, ',', 3), ':', -1) AS bakery,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(s.items, ',', 4), ':', -1) AS grocery,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(s.items, ',', 5), ':', -1) AS dairy,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(s.items, ',', 6), ':', -1) AS produce
                    FROM dbStops s
                        JOIN dbClients c ON c.id = s.client
                            AND c.type = 'donor'
                            AND c.donor_type = 'Rescued Food'
                    WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                       -- AND s.type = 'pickup'
                        AND s.weight > 0
                    order by 1,3
                ) x
                GROUP BY 1,2,3
                ORDER BY 3,1
SQL;
//        error_log($query);
        $result = mysqli_query ($con,$query);
        if (!$result) {
            error_log(mysqli_error($con). "\n");
            mysqli_close($con);
            return false;
        }
        $last_month = 12;
        while ($result_row = mysqli_fetch_assoc($result)) {
            $ft = $result_row['food_type'];
            $r = $result_row['date'];
            if ($result_row['weight'] > 0) $last_month = $r;

            $food[$ft][$r][0] = $result_row['meat'];
            $food[$ft][$r][1] = $result_row['deli'];
            $food[$ft][$r][2] = $result_row['bakery'];
            $food[$ft][$r][3] = $result_row['grocery'];
            $food[$ft][$r][4] = $result_row['dairy'];
            $food[$ft][$r][5] = $result_row['produce'];
            $food[$ft][$r][6] = $result_row['weight'];
    
            $food[$ft]['YTD'][0] += $result_row['meat'];
            $food[$ft]['YTD'][1] += $result_row['deli'];
            $food[$ft]['YTD'][2] += $result_row['bakery'];
            $food[$ft]['YTD'][3] += $result_row['grocery'];
            $food[$ft]['YTD'][4] += $result_row['dairy'];
            $food[$ft]['YTD'][5] += $result_row['produce'];
            $food[$ft]['YTD'][6] += $result_row['weight'];
        }
    
        mysqli_close($con);
        return array(
            'last_month' => $last_month,
            'food' => $food,
        );
    }
}