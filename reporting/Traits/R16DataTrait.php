<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R16DataTrait {
    public $Label = '';
    public $WEEKDAYS = ['MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'];
    
    public function data($rpt_date,$area) {
        // incoming date is month only and should be the start day of the last complete month
        $tmpdate = new DateTime($rpt_date->format('y-m-d'));

        // need full 12 weeks history, starting with Mondays, lets go to fi
        $tmpdate->modify('last day of this month');
        

        // back up to the last Sunday, then to the last Monday so we should have full week ahead
        $tmpdate->modify('last Sunday')->modify('last Monday');
        
        // now back up 12 weeks
        // should be on the first monday of the 12 week period
        $tmpdate->modify('-11 week');
        
        // now back 1 day so pre-modify in loop works
        $tmpdate->modify('-1 day');
        
        $dates = [[],[],[],[],[],[],[]];
        for ($x=0; $x < 12; $x++) {
            $dates[0][] = $tmpdate->modify('+1 day')->format('y-m-d');
            $dates[1][] = $tmpdate->modify('+1 day')->format('y-m-d');
            $dates[2][] = $tmpdate->modify('+1 day')->format('y-m-d');
            $dates[3][] = $tmpdate->modify('+1 day')->format('y-m-d');
            $dates[4][] = $tmpdate->modify('+1 day')->format('y-m-d');
            $dates[5][] = $tmpdate->modify('+1 day')->format('y-m-d');
            $dates[6][] = $tmpdate->modify('+1 day')->format('y-m-d');
        }
        
        
        $dropoffs = [];
    
        $con = connect();
        for ($x=0; $x<7; $x++) {
            $query = <<<SQL
                    select client,
                            SUM(IF(date='{$dates[$x][0]}',weight,0)) as wk1,
                            SUM(IF(date='{$dates[$x][1]}',weight,0)) as wk2,
                            SUM(IF(date='{$dates[$x][2]}',weight,0)) as wk3,
                            SUM(IF(date='{$dates[$x][3]}',weight,0)) as wk4,
                            SUM(IF(date='{$dates[$x][4]}',weight,0)) as wk5,
                            SUM(IF(date='{$dates[$x][5]}',weight,0)) as wk6,
                            SUM(IF(date='{$dates[$x][6]}',weight,0)) as wk7,
                            SUM(IF(date='{$dates[$x][7]}',weight,0)) as wk8,
                            SUM(IF(date='{$dates[$x][8]}',weight,0)) as wk9,
                            SUM(IF(date='{$dates[$x][9]}',weight,0)) as wk10,
                            SUM(IF(date='{$dates[$x][10]}',weight,0)) as wk11,
                            SUM(IF(date='{$dates[$x][11]}',weight,0)) as wk12
                                    from (
                                        SELECT
                                            REPLACE(s.client,'  ',' ') as client,
                                            date,
                                            SUM(s.weight) as weight
                                        FROM dbStops s
                                            JOIN dbClients c ON c.id = s.client
                                                AND c.donor_type = 'Rescued Food'
                                                AND c.chain_name != ''
                                            JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
                                                AND da.deliveryAreaName='$area'
                                        WHERE s.date IN ('{$dates[$x][0]}','{$dates[$x][1]}','{$dates[$x][2]}',
                                                         '{$dates[$x][3]}','{$dates[$x][4]}','{$dates[$x][5]}',
                                                         '{$dates[$x][6]}','{$dates[$x][7]}','{$dates[$x][8]}',
                                                         '{$dates[$x][9]}','{$dates[$x][10]}','{$dates[$x][11]}')
                                            AND s.weight > 0
                                        GROUP BY 1,2
                                        ORDER BY 2,1
                                    ) x
                                    group by 1
SQL;

    //        error_log($query);
            $result = mysqli_query ($con,$query);
            if (!$result) {
                error_log(mysqli_error($con). "\n");
                mysqli_close($con);
                return false;
            }
        
            $week = $this->WEEKDAYS[$x];
            $dropoffs[$week] = [
                'dates' => $dates[$x],
                'rows' => []
            ];
            while ($result_row = mysqli_fetch_assoc($result)) {
                $dropoffs[$week]['rows'][] = [
                    'client' => $result_row['client'],
                    'data' => [
                        $result_row['wk1'],
                        $result_row['wk2'],
                        $result_row['wk3'],
                        $result_row['wk4'],
                        $result_row['wk5'],
                        $result_row['wk6'],
                        $result_row['wk7'],
                        $result_row['wk8'],
                        $result_row['wk9'],
                        $result_row['wk10'],
                        $result_row['wk11'],
                        $result_row['wk12']
                    ]
                ];
            }
        }

        
        $pickups = [];
        for ($x=0; $x<7; $x++) {
            $query = <<<SQL
                    select client,
                            SUM(IF(date='{$dates[$x][0]}',weight,0)) as wk1,
                            SUM(IF(date='{$dates[$x][1]}',weight,0)) as wk2,
                            SUM(IF(date='{$dates[$x][2]}',weight,0)) as wk3,
                            SUM(IF(date='{$dates[$x][3]}',weight,0)) as wk4,
                            SUM(IF(date='{$dates[$x][4]}',weight,0)) as wk5,
                            SUM(IF(date='{$dates[$x][5]}',weight,0)) as wk6,
                            SUM(IF(date='{$dates[$x][6]}',weight,0)) as wk7,
                            SUM(IF(date='{$dates[$x][7]}',weight,0)) as wk8,
                            SUM(IF(date='{$dates[$x][8]}',weight,0)) as wk9,
                            SUM(IF(date='{$dates[$x][9]}',weight,0)) as wk10,
                            SUM(IF(date='{$dates[$x][10]}',weight,0)) as wk11,
                            SUM(IF(date='{$dates[$x][11]}',weight,0)) as wk12
                                    from (
                                        SELECT
                                            REPLACE(s.client,'  ',' ') as client,
                                            date,
                                            SUM(s.rescued_weight) as weight
                                        FROM dbStops s
                                            JOIN dbClients c ON c.id = s.client
                                                AND c.type = 'Recipient'
                                            JOIN dbDeliveryAreas da on da.deliveryAreaId = c.deliveryAreaId
                                                AND da.deliveryAreaName='$area'
                                        WHERE s.date IN ('{$dates[$x][0]}','{$dates[$x][1]}','{$dates[$x][2]}',
                                                         '{$dates[$x][3]}','{$dates[$x][4]}','{$dates[$x][5]}',
                                                         '{$dates[$x][6]}','{$dates[$x][7]}','{$dates[$x][8]}',
                                                         '{$dates[$x][9]}','{$dates[$x][10]}','{$dates[$x][11]}')
                                            AND s.rescued_weight > 0
                                        GROUP BY 1,2
                                        ORDER BY 2,1
                                    ) x
                                    group by 1
SQL;
        
            //        error_log($query);
            $result = mysqli_query ($con,$query);
            if (!$result) {
                error_log(mysqli_error($con). "\n");
                mysqli_close($con);
                return false;
            }
        
            $week = $this->WEEKDAYS[$x];
            $pickups[$week] = [
                'dates' => $dates[$x],
                'rows' => []
            ];
            while ($result_row = mysqli_fetch_assoc($result)) {
                $pickups[$week]['rows'][] = [
                    'client' => $result_row['client'],
                    'data' => [
                        $result_row['wk1'],
                        $result_row['wk2'],
                        $result_row['wk3'],
                        $result_row['wk4'],
                        $result_row['wk5'],
                        $result_row['wk6'],
                        $result_row['wk7'],
                        $result_row['wk8'],
                        $result_row['wk9'],
                        $result_row['wk10'],
                        $result_row['wk11'],
                        $result_row['wk12']
                    ]
                ];
            }
        }
        mysqli_close($con);
    
    
        return array(
            'dropoffs' => $dropoffs,
            'pickups' => $pickups
        );
    }
}