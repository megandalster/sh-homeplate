<?php

include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R19DataTrait {
    public function data($rpt_date=null) {
        // need: Current YTD
        //              date >= 'ytd'
        // need: Prior 3 Months
        //              date >= 'last3months'
        // need: Prior Year
        //              date >= 'begPriorYear' and date < 'ytd'
        // need: All Time
        
        // have last full month 04/2022
        $end_date = new DateTime($rpt_date->format('y-m-d'));
        $end_date->modify('first day of next month');
    
        $report_month = new DateTime($rpt_date->format('y-m-d'));
        $report_month->modify('first day of month');
    
        $ytd = new DateTime($rpt_date->format('y-m-d'));
        $ytd->modify('first day of January');
    
        $prior3 = new DateTime($rpt_date->format('y-m-d'));
        $prior3->modify('-2 month');
    
        $priory = new DateTime($rpt_date->format('y-m-d'));
        $priory->modify('first day of January');
        $priory->modify('-1 year');
    
        $report_month = $report_month->format('y-m-d');
        $end_date = $end_date->format('y-m-d');
        $ytd = $ytd->format('y-m-d');
        $prior3 = $prior3->format('y-m-d');
        $priory = $priory->format('y-m-d');
        
        error_log("   end_date: ".$end_date);
        error_log("   ytd date: ".$ytd);
        error_log("prior3 date: ".$prior3);
        error_log("priory date: ".$priory);

        $con = connect();
        $query = <<<SQL
                SELECT
                  v.last_name
                  ,v.first_name
                  ,RIGHT(dr.id,3) as area
                  , v.type as roles
                  ,SUM(IF(LEFT(dr.id,8) >= '$ytd',1,0)) as YTD
                  ,SUM(IF(LEFT(dr.id,8) >= '$prior3',1,0)) as Prior3
                  ,SUM(IF(LEFT(dr.id,8) >= '$priory' AND LEFT(dr.id,8) < '$ytd',1,0)) as PriorYear
                  ,COUNT(*) as ALL_TIME
                , MAX(LEFT(dr.id,8)) as last_trip
                FROM dbRoutes dr
                JOIN dbVolunteers v on FIND_IN_SET( v.id, dr.drivers ) > 0
                        OR v.id = dr.teamcaptain_id
                WHERE dr.status='completed'
                and dr.id < '$end_date'
                and v.id is not null and v.status='active'
                       group by 1,2,3,4
                order by 1,2
SQL;

        $summary = array();

        $con = connect();
        //error_log($query);
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
            if ($result_row['last_trip'] < $report_month)
                continue;
            $summary[] = array(
                'last_name' => $result_row['last_name'],
                'first_name' => $result_row['first_name'],
                'area' => $result_row['area'],
                'roles' => $result_row['roles'],
                'YTD' => $result_row['YTD'],
                'Prior3' => $result_row['Prior3'],
                'PriorYear' => $result_row['PriorYear'],
                'ALL_TIME' => $result_row['ALL_TIME'],
            );
        }
        mysqli_close($con);
        return array(
            'summary' => $summary,
        );
    }
}

