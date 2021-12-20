<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R7DataTrait {
    public $headerLabels = array('','','','','','');
    public $headerMap = array();
    
    public function data($rpt_date=null) {
        // start Date is -6 months
        $s_date = new DateTime($rpt_date->format('y-m-d'));
        $s_date->modify('-5 month');
        $this->headerLabels[0] = $s_date->format('M-y');
        $this->headerMap[$s_date->format('y-m')] = 'm1';
        $start_date = $s_date->format('y-m-d');

        $s_date->modify('+1 month');
        $this->headerLabels[1] = $s_date->format('M-y');
        $this->headerMap[$s_date->format('y-m')] = 'm2';
        $s_date->modify('+1 month');
        $this->headerLabels[2] = $s_date->format('M-y');
        $this->headerMap[$s_date->format('y-m')] = 'm3';
        $s_date->modify('+1 month');
        $this->headerLabels[3] = $s_date->format('M-y');
        $this->headerMap[$s_date->format('y-m')] = 'm4';
        $s_date->modify('+1 month');
        $this->headerLabels[4] = $s_date->format('M-y');
        $this->headerMap[$s_date->format('y-m')] = 'm5';
        $s_date->modify('+1 month');
        $this->headerLabels[5] = $s_date->format('M-y');
        $this->headerMap[$s_date->format('y-m')] = 'm6';
        $s_date->modify('+1 month');
        $end_date = $s_date->format('y-m-d');
    
        error_log($start_date.' --> '.$end_date);
        
        
        $pickups = array();
        $total = 0;
        
        $con = connect();
        $query = <<<SQL
                SELECT s.client,
                      SUBSTRING(s.date,1,5) AS date,
                      c.donor_type,
                      CASE WHEN c.donor_type='Rescued Food' THEN 0
                      	WHEN c.donor_type='Transported Food' THEN 1
                        WHEN c.donor_type='Purchased Food' THEN 2 ELSE 3 END AS orderby,
                      SUM(s.weight) AS weight,
                      MAX(tot.weight) AS tot_weight
                FROM dbStops s
                  JOIN dbClients c ON c.id = s.client
                    AND c.type = 'donor'
                  LEFT JOIN (
                    SELECT s.client, sum(weight) AS weight
                    FROM dbStops s
                    WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                      AND s.weight > 0
                    GROUP BY 1
                  ) tot ON tot.client = s.client
                WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                  AND s.type = 'pickup'
                  AND s.weight > 0
                GROUP BY 1,2
                ORDER BY 4,6 DESC,2
SQL;
//        error_log($query);
        $result = mysqli_query ($con,$query);
        if (!$result) {
            error_log(mysqli_error($con). "\n");
            mysqli_close($con);
            return false;
        }
        $row = null;
        $donor = null;
        $rtotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $nrtotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $alltotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
    
        while ($result_row = mysqli_fetch_assoc($result)) {
            $d = $result_row['client'];
            if ($donor != $d) {
                if ($row != null) {
                    $pickups[] = $row;
                    $total += $row['tot'];
                }
                $row = array(
                    'client' => $result_row['client'],
                    'donor_type' => $result_row['donor_type'],
                    'm1' => null,
                    'm2' => null,
                    'm3' => null,
                    'm4' => null,
                    'm5' => null,
                    'm6' => null,
                    'tot' => $result_row['tot_weight']
                );
                $donor = $d;
            }
            $m = $this->headerMap[$result_row['date']];
            $row[$m] = $result_row['weight'];
            
            if ($result_row['donor_type'] == 'Rescued Food') {
                $rtotals[$m] += $result_row['weight'];
                $rtotals['tot'] += $result_row['weight'];
            } else {
                $nrtotals[$m] += $result_row['weight'];
                $nrtotals['tot'] += $result_row['weight'];
            }
            $alltotals[$m] += $result_row['weight'];
            $alltotals['tot'] += $result_row['weight'];
    
        }
        if ($row != null) {
            $pickups[] = $row;
            $total += $row['tot'];
        }
    
        mysqli_close($con);
        return array(
            'total' => $total,
            'pickups' => $pickups,
            'rtotals' => $rtotals,
            'nrtotals' => $nrtotals,
            'alltotals' => $alltotals
        );
    }
}