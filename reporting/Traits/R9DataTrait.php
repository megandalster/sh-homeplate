<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R9DataTrait {
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
        
        
        $dropoffs = array();
        $total = 0;
        
        $con = connect();
        $query = <<<SQL
                SELECT s.client,
                    SUBSTRING(s.date,1,5) AS date,
                    da.deliveryAreaName as area,
                    CASE WHEN c.deliveryAreaId=20 THEN 'Hilton Head Area'
                        WHEN c.deliveryAreaId=21 THEN 'Bluffton Area'
                      	WHEN c.deliveryAreaId=9 THEN 'Beaufort Area'
                        WHEN c.deliveryAreaId IN (10,14) THEN 'Jasper County'
                        ELSE 'Hampton County' END AS subTotalsArea,
                    CASE WHEN c.deliveryAreaId=20 THEN 0
                        WHEN c.deliveryAreaId=21 THEN 1
                      	WHEN c.deliveryAreaId=9 THEN 2
                        WHEN c.deliveryAreaId IN (10,14) THEN 3 ELSE 4 END AS subOrderBy,
                    SUM(s.weight) AS weight,
                    MAX(tot.weight) AS tot_weight
                FROM dbStops s
                    JOIN dbClients c ON c.id = s.client
                        AND c.type = 'recipient'
                    LEFT JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
                    LEFT JOIN (
                        SELECT s.client, sum(weight) AS weight
                        FROM dbStops s
                        WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                            AND s.weight > 0
                        GROUP BY 1
                    ) tot ON tot.client = s.client
                WHERE (s.date >= '$start_date' AND s.date < '$end_date')
                    AND s.type = 'dropoff'
                    AND s.weight > 0
                GROUP BY 1,2
                ORDER BY 5,7 DESC,1
SQL;
//        error_log($query);
        $result = mysqli_query ($con,$query);
        if (!$result) {
            error_log(mysqli_error($con). "\n");
            mysqli_close($con);
            return false;
        }
        $row = null;
        $recipient = null;
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
        $bcTotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $jcTotals = array(
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'm5' => 0,
            'm6' => 0,
            'tot' => 0
        );
        $hcTotals = array(
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
            $r = $result_row['client'];
            if ($recipient != $r) {
                if ($row != null) {
                    $dropoffs[] = $row;
                    $total += $row['tot'];
                }
                $row = array(
                    'client' => $result_row['client'],
                    'area' => $result_row['subTotalsArea'],
                    'm1' => null,
                    'm2' => null,
                    'm3' => null,
                    'm4' => null,
                    'm5' => null,
                    'm6' => null,
                    'tot' => $result_row['tot_weight']
                );
                $recipient = $r;
            }
            $m = $this->headerMap[$result_row['date']];
            $row[$m] = $result_row['weight'];
            
            if ($result_row['subOrderBy'] == 0) {
                $bcTotals[$m] += $result_row['weight'];
                $bcTotals['tot'] += $result_row['weight'];
                $hhiTotals[$m] += $result_row['weight'];
                $hhiTotals['tot'] += $result_row['weight'];
            } else if ($result_row['subOrderBy'] == 1) {
                $bcTotals[$m] += $result_row['weight'];
                $bcTotals['tot'] += $result_row['weight'];
                $bluTotals[$m] += $result_row['weight'];
                $bluTotals['tot'] += $result_row['weight'];
            } else if ($result_row['subOrderBy'] == 2) {
                $bcTotals[$m] += $result_row['weight'];
                $bcTotals['tot'] += $result_row['weight'];
                $beaTotals[$m] += $result_row['weight'];
                $beaTotals['tot'] += $result_row['weight'];
            } else if ($result_row['subOrderBy'] == 3) {
                $jcTotals[$m] += $result_row['weight'];
                $jcTotals['tot'] += $result_row['weight'];
            } else if ($result_row['subOrderBy'] == 4) {
                $hcTotals[$m] += $result_row['weight'];
                $hcTotals['tot'] += $result_row['weight'];
            }
            $alltotals[$m] += $result_row['weight'];
            $alltotals['tot'] += $result_row['weight'];
    
        }
        if ($row != null) {
            $dropoffs[] = $row;
            $total += $row['tot'];
        }
    
        mysqli_close($con);
        return array(
            'total' => $total,
            'dropoffs' => $dropoffs,
    
            'hhiTotals' => $hhiTotals,
            'bluTotals' => $bluTotals,
            'beaTotals' => $beaTotals,
            'bcTotals' => $bcTotals,
            'jcTotals' => $jcTotals,
            'hcTotals' => $hcTotals,
            'alltotals' => $alltotals
        );
    }
}