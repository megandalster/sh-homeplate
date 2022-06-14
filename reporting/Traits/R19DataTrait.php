<?php

include_once(dirname(__FILE__).'/../../database/dbRouteHistory.php');
include_once(dirname(__FILE__).'/../../database/dbinfo.php');

trait R19DataTrait {
    public function data($rpt_date=null)
    {
        $summary = route_history_stats($rpt_date);
        $detail = route_history_detail($rpt_date);
        return array(
            'summary' => $summary,
            'detail' => $detail,
        );
    }
    
    public function old_data($rpt_date=null) {
        $dates = calculate_stats_dates($rpt_date);
        $con = connect();
        $query = <<<SQL
                SELECT
                  v.last_name
                  ,v.first_name
                  ,RIGHT(dr.id,3) as area
                  , v.type as roles
                  ,SUM(IF(LEFT(dr.id,8) >= '{$dates['year_to_date']}',1,0)) as YTD
                  ,SUM(IF(LEFT(dr.id,8) >= '{$dates['prior_3_months']}',1,0)) as Prior3
                  ,SUM(IF(LEFT(dr.id,8) >= '{$dates['prior_year']}' AND LEFT(dr.id,8) < '{$dates['year_to_date']}',1,0)) as PriorYear
                  ,COUNT(*) as ALL_TIME
                , MAX(LEFT(dr.id,8)) as last_trip
                FROM dbRoutes dr
                JOIN dbVolunteers v on FIND_IN_SET( v.id, dr.drivers ) > 0
                        OR v.id = dr.teamcaptain_id
                WHERE dr.status='completed'
                and dr.id < '{$dates['end_date']}'
                and v.id is not null and v.status='active'
                       group by 1,2,3,4
                order by 1,2
SQL;
    
        $summary = array();

        $con = connect();
        //error_log($query);
        $result = mysqli_query ($con,$query);
        while ($result_row = mysqli_fetch_assoc($result)) {
//            if ($result_row['last_trip'] < $dates['report_month'])
//                continue;
            $summary[] = array(
                'last_name' => $result_row['last_name'],
                'first_name' => $result_row['first_name'],
                'area' => $result_row['area'],
                'roles' => $result_row['roles'],
                'year_to_date' => $result_row['YTD'],
                'prior_3_months' => $result_row['Prior3'],
                'prior_year' => $result_row['PriorYear'],
                'all_time' => $result_row['ALL_TIME']
            );
        }
        mysqli_close($con);
        return array(
            'summary' => $summary,
        );
    }
}

