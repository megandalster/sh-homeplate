<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R2DataTrait {
    public function data($rpt_date=null,$isytd=false) {
        $start_date = $rpt_date->format('y-m-d');
        if ($isytd) {
            $start_date = new DateTime($rpt_date->format('y-m-d'));
            $start_date->setDate( $start_date->format('Y'), 1, 1);
            $start_date = $start_date->format('y-m-d');
        }
        $end_date = new DateTime($rpt_date->format('y-m-d'));
        $end_date->modify('+1 month');
        $end_date = $end_date->format('y-m-d');
        error_log($start_date.' --> '.$end_date);
        
        $pickups = array();
        $dropoffs = array();
        
        // keep track of total weights
        $tw_pickups  = 0;
        $tw_dropoffs = 0;
        
        $con = connect();
        $query = <<<SQL
                    SELECT s.client, s.type, s.weight,
                    case when c.donor_type='Rescued Food' then 'Rescued Food' else 'Other Food' end as donor_type,
                    case when c.donor_type='Rescued Food' THEN 0 else 1 end as orderby
                    from (
                        SELECT client, type, SUM(weight) as weight
                        FROM dbStops
                        WHERE date >= '$start_date' AND date < '$end_date'
                            AND weight > 0
                        GROUP BY client ) s
                    JOIN dbClients c on c.id = s.client
                    order by 5,3 desc
SQL;
        error_log($query);
        $result = mysqli_query ($con,$query);
        $theStops = array();
        while ($result_row = mysqli_fetch_assoc($result)) {
            $clientName = $result_row['client'];
            $type = $result_row['type'];
            $weight = $result_row['weight'];
            $donor_type = $result_row['donor_type'];
            if($type == "pickup"){
                $pickups[] = array(
                    'client' => $clientName,
                    'weight' => $weight,
                    'donor_type' => $donor_type,
                );
                $tw_pickups += $weight;
            } else{
                $dropoffs[] = array(
                    'client' => $clientName,
                    'weight' => $weight,
                    'donor_type' => $donor_type,
                );
                $tw_dropoffs += $weight;
            }
        }
        mysqli_close($con);
        $max = max(count($pickups), count($dropoffs));
        return array(
            'count' => $max,
            'tw_pickups' => $tw_pickups,
            'tw_dropoffs' => $tw_dropoffs,
            'pickups' => $pickups,
            'dropoffs' => $dropoffs,
        );
    }
}