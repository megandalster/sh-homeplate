<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R6DataTrait {
    public $startDateLabel = '';
    public function data($rpt_date=null) {
        $tmpdate = new DateTime($rpt_date->format('y-m-d'));
    
        // start Date is -3 months INCLUSIVE
        $tmpdate->modify('-2 month');
        $this->startDateLabel = $tmpdate->format('M-y');
        $start_date = $tmpdate->format('y-m-d');
    
        // back to prv ytd
        $tmpdate->modify('-1 year');
        $prv_start_date = $tmpdate->format('y-m-d');
    
        // back to prv end date
        $tmpdate->modify('+3 month');
        $prv_end_date = $tmpdate->format('y-m-d');
    
        // up to cur year end date
        $tmpdate->modify('+1 year');
        $end_date = $tmpdate->format('y-m-d');
    
    
    
    
        $tmpdate = new DateTime($rpt_date->format('y').'-01-01');
        $ytd_start_date = $tmpdate->format('y-m-d');
    
        $tmpdate->modify('-1 year');
        $pytd_start_date = $tmpdate->format('y-m-d');
    
        error_log('   cur: '.$start_date.' < '.$end_date.    '      prv: '.$prv_start_date.' < '.$prv_end_date);
        error_log('   ytd: '.$ytd_start_date.' < '.$end_date.'   prvytd: '.$pytd_start_date.' < '.$prv_end_date);
    
        $dropoffs = array();
        
        $con = connect();
        $query = <<<SQL
select client,
       area,
       county,
		cur_weight,
        prv_weight,
        IFNULL(cur_weight,0)-IFNULL(prv_weight,0) as chg_weight,
		ycur_weight,
        yprv_weight,
        IFNULL(ycur_weight,0)-IFNULL(yprv_weight,0) as ychg_weight
from (
        SELECT  DISTINCT s.client,
                         da.deliveryAreaName as area,
                         c.county,
    		CUR.weight as cur_weight,
    		PRV.weight as prv_weight,
    		YCUR.weight as ycur_weight,
    		YPRV.weight as yprv_weight
         FROM dbStops s
    		JOIN dbClients c on c.id = s.client
    			and c.type = 'recipient'
                -- and c.status='active'
             LEFT JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
            LEFT JOIN (
                SELECT s.client, sum(weight) as weight
                FROM dbStops s
                WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                       AND s.weight > 0
                group by 1
            ) CUR on CUR.client = s.client
            
            LEFT JOIN (
                SELECT s.client, SUM(s.weight) as weight
                FROM dbStops s
                WHERE (s.date >= '$prv_start_date' AND s.date < '$prv_end_date')
                       AND s.weight > 0
                group by 1
            ) PRV on PRV.client = s.client
    
            LEFT JOIN (
                SELECT s.client, sum(weight) as weight
                FROM dbStops s
                WHERE (s.date >= '$ytd_start_date' AND s.date < '$end_date')
                       AND s.weight > 0
                group by 1
            ) YCUR on YCUR.client = s.client
            
            LEFT JOIN (
                SELECT s.client, SUM(s.weight) as weight
                FROM dbStops s
                WHERE (s.date >= '$pytd_start_date' AND s.date < '$prv_end_date')
                       AND s.weight > 0
                group by 1
            ) YPRV on YPRV.client = s.client
        	WHERE ((s.date >= '$start_date' AND s.date < '$end_date')
        	           or (s.date >= '$prv_start_date' AND s.date < '$prv_end_date')
        	           or (s.date >= '$ytd_start_date' AND s.date < '$end_date')
        	           or (s.date >= '$pytd_start_date' AND s.date < '$prv_end_date'))
            	-- AND s.type='dropoff'
               AND s.weight > 0
    ) y
    order by 6 desc
SQL;
//        error_log($query);
        $result = mysqli_query ($con,$query);
        if (!$result) {
            error_log(mysqli_error($con). "\n");
            mysqli_close($con);
            return false;
        }
        while ($result_row = mysqli_fetch_assoc($result)) {
            $dropoffs[] = array(
                'client' => $result_row['client'],
                'area' => $result_row['area'],
                'county' => $result_row['county'],
                'cur_weight' => $result_row['cur_weight'],
                'prv_weight' => $result_row['prv_weight'],
                'chg_weight' => $result_row['chg_weight'],
                'ycur_weight' => $result_row['ycur_weight'],
                'yprv_weight' => $result_row['yprv_weight'],
                'ychg_weight' => $result_row['ychg_weight'],
            );
        }
        mysqli_close($con);
        return array(
            'dropoffs' => $dropoffs
        );
    }
}