<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R3DataTrait {
    public function data($rpt_date=null) {
        $start_date = $rpt_date->format('y-m-d');
        $end_date = new DateTime($rpt_date->format('y-m-d'));
        $end_date->modify('+1 month');
        $end_date = $end_date->format('y-m-d');

        $prv_start_date = new DateTime($start_date);
        $prv_start_date->modify('-1 year');
        $prv_start_date = $prv_start_date->format('y-m-d');
        $prv_end_date = new DateTime($end_date);
        $prv_end_date->modify('-1 year');
        $prv_end_date = $prv_end_date->format('y-m-d');
        error_log($start_date.' --> '.$end_date.'     '.$prv_start_date.' --> '.$prv_end_date);
        
        $pickups = array();
        
        $con = connect();
        $query = <<<SQL
            SELECT DISTINCT s.client as client,
                case when c.donor_type='Rescued Food' then 'Rescued Food' else 'Other Food' end as donor_type,
                case when c.donor_type='Rescued Food' THEN 0 else 1 end as orderby,
        		CUR.weight as cur_weight,
        		PRV.weight as prv_weight
            FROM dbStops s
    		JOIN dbClients c on c.id = s.client
    			and c.type = 'donor'
            
            LEFT JOIN (
                SELECT client, sum(weight) as weight
                FROM dbStops
                WHERE (date >= '$start_date' AND date < '$end_date')
                       AND weight > 0
                group by 1
            ) CUR on CUR.client = s.client
            
            LEFT JOIN (
                SELECT client, sum(weight) as weight
                FROM dbStops
                WHERE (date >= '$prv_start_date' AND date < '$prv_end_date')
                       AND weight > 0
                group by 1
            ) PRV on PRV.client = s.client
    
        	WHERE ((s.date >= '$start_date' AND s.date < '$end_date') or (s.date >= '$prv_start_date' AND s.date < '$prv_end_date'))
               AND s.weight > 0
              -- AND s.type = 'pickup'
         order by 3,4 DESC
        -- group by 1
SQL;
//        error_log($query);
        $result = mysqli_query ($con,$query);
        if (!$result) {
            error_log(mysqli_error($con). "\n");
            mysqli_close($con);
            return false;
        }
        while ($result_row = mysqli_fetch_assoc($result)) {
            $pickups[] = array(
                'client' => $result_row['client'],
                'donor_type' => $result_row['donor_type'],
                'cur_weight' => $result_row['cur_weight'],
                'prv_weight' => $result_row['prv_weight'],
            );
        }
        mysqli_close($con);
        return array(
            'pickups' => $pickups,
        );
    }
}