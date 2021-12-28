<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R8DataTrait {
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
                    da.deliveryAreaName as area,
                    CASE WHEN c.donor_type != 'Rescued Food' THEN 0
                        WHEN da.deliveryAreaName='Hilton Head' THEN 1
                      	WHEN da.deliveryAreaName='Bluffton' THEN 2
                        WHEN da.deliveryAreaName='Beaufort' THEN 3 ELSE 4 END AS daOrderBy,
                    CASE WHEN c.donor_type='Rescued Food' THEN 0
                      	WHEN c.donor_type='Transported Food' THEN 1
                        WHEN c.donor_type='Purchased Food' THEN 2 ELSE 3 END AS orderby,
                    SUM(s.weight) AS weight,
                    MAX(tot.weight) AS tot_weight
                FROM dbStops s
                    JOIN dbClients c ON c.id = s.client
                        AND c.type = 'donor'
                    LEFT JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
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
                ORDER BY 6,5,8 DESC,2
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
        $hhiTotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $bluTotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $beaTotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $rtotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $ttotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $ptotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $ftotals = array(
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
                    'area' => $result_row['area'],
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
                if ($result_row['area'] == 'Hilton Head') {
                    $hhiTotals[$m] += $result_row['weight'];
                    $hhiTotals['tot'] += $result_row['weight'];
                } else if ($result_row['area'] == 'Bluffton') {
                    $bluTotals[$m] += $result_row['weight'];
                    $bluTotals['tot'] += $result_row['weight'];
                } else if ($result_row['area'] == 'Beaufort') {
                    $beaTotals[$m] += $result_row['weight'];
                    $beaTotals['tot'] += $result_row['weight'];
                }
            } else if ($result_row['donor_type'] == 'Transported Food') {
                $ttotals[$m] += $result_row['weight'];
                $ttotals['tot'] += $result_row['weight'];
                $nrtotals[$m] += $result_row['weight'];
                $nrtotals['tot'] += $result_row['weight'];
            } else if ($result_row['donor_type'] == 'Purchased Food') {
                $ptotals[$m] += $result_row['weight'];
                $ptotals['tot'] += $result_row['weight'];
                $nrtotals[$m] += $result_row['weight'];
                $nrtotals['tot'] += $result_row['weight'];
            } else if ($result_row['donor_type'] == 'Food Drive Food') {
                $ftotals[$m] += $result_row['weight'];
                $ftotals['tot'] += $result_row['weight'];
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
    
            'hhiTotals' => $hhiTotals,
            'bluTotals' => $bluTotals,
            'beaTotals' => $beaTotals,
    
            'rtotals' => $rtotals,
            'ttotals' => $ttotals,
            'ptotals' => $ptotals,
            'ftotals' => $ftotals,
            'nrtotals' => $nrtotals,
            'alltotals' => $alltotals
        );
    }
}