<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R15DataTrait {
    public $startDateLabel = '';
    public function data($rpt_date=null) {
        $tmpdate = new DateTime($rpt_date->format('y-m-d'));
    
        // start Date last full month
        $this->startDateLabel = $tmpdate->format('M-y');
        $start_date = $tmpdate->format('y-m-d');
    
        // end date is one month only
        $tmpdate->modify('+1 month');
        $end_date = $tmpdate->format('y-m-d');
    
        $tmpdate = new DateTime(substr($start_date,0,2).'-01-01');
        $ytd_start_date = $tmpdate->format('y-m-d');
    
        error_log('   cur: '.$start_date.' < '.$end_date);
        error_log('   ytd: '.$ytd_start_date.' < '.$end_date);
    
        $dropoffs = array();
        
        $con = connect();
        $query = <<<SQL
            SELECT client,
                SUM(IF(type='CUR',IFNULL(transported_weight,0),0)) as transported_weight,
                SUM(IF(type='CUR',IFNULL(purchased_weight,0),0)) as purchased_weight,
                SUM(IF(type='CUR',IFNULL(food_drive_weight,0),0)) as food_drive_weight,
                SUM(IF(type='CUR',IFNULL(total_weight,0),0))  as total_weight,
                SUM(IF(type='YTD',IFNULL(transported_weight,0),0)) as ytd_transported_weight,
                SUM(IF(type='YTD',IFNULL(purchased_weight,0),0)) as ytd_purchased_weight,
                SUM(IF(type='YTD',IFNULL(food_drive_weight,0),0)) as ytd_food_drive_weight,
                SUM(IF(type='YTD',IFNULL(total_weight,0),0))  as ytd_total_weight
             FROM (
                    SELECT 'CUR' as type, s.client,
                           SUM(transported_weight) as transported_weight,
                           SUM(purchased_weight) as purchased_weight,
                           SUM(food_drive_weight) as food_drive_weight,
                           SUM(transported_weight+purchased_weight+food_drive_weight) as total_weight
                    FROM dbStops s
                    JOIN dbClients c on c.id = s.client
                        and c.type = 'recipient'
                    WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                           AND (
                               s.transported_weight > 0
                               OR s.purchased_weight > 0
                               OR s.food_drive_weight > 0
                               )
                    group by 1,2
                UNION
                    SELECT 'YTD' as type, s.client,
                           SUM(transported_weight) as transported_weight,
                           SUM(purchased_weight) as purchased_weight,
                           SUM(food_drive_weight) as food_drive_weight,
                           SUM(transported_weight+purchased_weight+food_drive_weight) as total_weight
                    FROM dbStops s
                    JOIN dbClients c on c.id = s.client
                        and c.type = 'recipient'
                    WHERE (s.date >= '$ytd_start_date' AND s.date < '$end_date')
                           AND (
                               s.transported_weight > 0
                               OR s.purchased_weight > 0
                               OR s.food_drive_weight > 0
                               )
                    group by 1,2
            )   x
        group by 1
        order by 1
SQL;

//        error_log($query);
        $result = mysqli_query ($con,$query);
        if (!$result) {
            error_log(mysqli_error($con). "\n");
            mysqli_close($con);
            return false;
        }
        $ytd_total = 0;
    
        $ytd_total = array(
            'client' => 'Total',
            'transported_weight' => 0,
            'purchased_weight' => 0,
            'food_drive_weight' => 0,
            'total_weight' => 0,
            'ytd_transported_weight' => 0,
            'ytd_purchased_weight' => 0,
            'ytd_food_drive_weight' => 0,
            'ytd_total_weight' => 0
        );
        while ($result_row = mysqli_fetch_assoc($result)) {
            $dropoffs[] = array(
                'client' => $result_row['client'],
                'transported_weight' => $result_row['transported_weight'],
                'purchased_weight' => $result_row['purchased_weight'],
                'food_drive_weight' => $result_row['food_drive_weight'],
                'total_weight' => $result_row['total_weight'],
                'ytd_transported_weight' => $result_row['ytd_transported_weight'],
                'ytd_purchased_weight' => $result_row['ytd_purchased_weight'],
                'ytd_food_drive_weight' => $result_row['ytd_food_drive_weight'],
                'ytd_total_weight' => $result_row['ytd_total_weight'],
                'pct_ytd_total'
            );
            $ytd_total['transported_weight'] +=  $result_row['transported_weight'];
            $ytd_total['purchased_weight'] +=  $result_row['purchased_weight'];
            $ytd_total['food_drive_weight'] +=  $result_row['food_drive_weight'];
            $ytd_total['total_weight'] +=  $result_row['total_weight'];
            $ytd_total['ytd_transported_weight'] +=  $result_row['ytd_transported_weight'];
            $ytd_total['ytd_purchased_weight'] +=  $result_row['ytd_purchased_weight'];
            $ytd_total['ytd_food_drive_weight'] +=  $result_row['ytd_food_drive_weight'];
            $ytd_total['ytd_total_weight'] +=  $result_row['ytd_total_weight'];
    
        }
        mysqli_close($con);
        return array(
            'ytd_total' => $ytd_total,
            'dropoffs' => $dropoffs
        );
    }
}