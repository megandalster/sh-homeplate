<?php

include_once(dirname(__FILE__).'/dbinfo.php');

function calculate_stats_dates($thru_date) {
    if ($thru_date instanceof DateTime) {
        $thru_date = New DateTime($thru_date);
    }
    $end_date = new DateTime($thru_date->format('y-m-d'));
    $end_date->modify('first day of next month');
    
    $report_month = new DateTime($thru_date->format('y-m-d'));
    $report_month->modify('first day of month');
    
    $ytd = new DateTime($thru_date->format('y-m-d'));
    $ytd->modify('first day of January');
    
    $prior3 = new DateTime($thru_date->format('y-m-d'));
    $prior3->modify('-2 month');
    
    $priory = new DateTime($thru_date->format('y-m-d'));
    $priory->modify('first day of January');
    $priory->modify('-1 year');
    
    return array(
        'report_month' => $report_month->format('y-m-d'),
        'end_date' => $end_date->format('y-m-d'),
        'year_to_date' => $ytd->format('y-m-d'),
        'prior_3_months' => $prior3->format('y-m-d'),
        'prior_year' => $priory->format('y-m-d')
    );
}

function row_to_object($row) {
    return array(
        'last_name' => $row['last_name'],
        'first_name' => $row['first_name'],
        'area' => $row['area'],
        'roles' => $row['roles'],
        'year_to_date' => $row['YTD'],
        'prior_3_months' => $row['Prior3'],
        'prior_year' => $row['PriorYear'],
        'all_time' => $row['ALL_TIME']
    );
}

// only used when implementing route_history
function sync_to_last_trip_dates($from_date) {
    $con=connect();
    $query = <<<SQL
                SELECT
                       v.id as vid,
                       v.LastTripDates,
                       dr.id as rid
                FROM dbRoutes dr
                JOIN dbVolunteers v on FIND_IN_SET( v.id, dr.drivers ) > 0
                        OR v.id = dr.teamcaptain_id
                WHERE dr.status='completed'
                and dr.id >= '$from_date'
                and v.id is not null
SQL;
    
    $result = mysqli_query($con, $query);
    while ($result_row = mysqli_fetch_assoc($result)) {
        $routedate = substr($result_row['rid'],0,8);
        $routebase = substr($result_row['rid'], 9, 3);
        if (strpos($result_row['LastTripDates'], $routedate) !== false) {
            mysqli_query($con,
                "INSERT IGNORE INTO route_history VALUES ('$routedate','$routebase','{$result_row['vid']}')");
        }
    }
    
    mysqli_close($con);
    
}

// remove all history for route
function route_history_remove($route_id) {
    $routedate =  substr($route_id,0,8);
    $routebase = substr($route_id, 9, 3);
    $con=connect();
    $result = mysqli_query($con,
        "DELETE FROM route_history WHERE route_date='$routedate' and route_base='$routebase'");
    mysqli_close($con);
}

// remove all history for route
function route_history_add($route_id, $volunteer_ids) {
    $routedate =  substr($route_id,0,8);
    $routebase = substr($route_id, 9, 3);
    $con=connect();
    foreach($volunteer_ids as $vid) {
        $result = mysqli_query($con,
            "INSERT IGNORE INTO route_history VALUES ('$routedate','$routebase','$vid')");
    }
    mysqli_close($con);
}

function route_history_volunteers_on_route($route_id) {
    $routedate =  substr($route_id,0,8);
    $routebase = substr($route_id, 9, 3);
    $con=connect();
    $query = <<<SQL
                SELECT rh.volunteer_id
                FROM route_history rh
                WHERE rh.route_date = '$routedate'
                    AND rh.route_base = '$routebase'
SQL;
    $vids = [];
    $result = mysqli_query($con, $query);
    while ($result_row = mysqli_fetch_assoc($result)) {
        $vids[] = $result_row['volunteer_id'];
    }
    mysqli_close($con);
    return $vids;
}


function route_history_stats($thru_date) {
    $dates = calculate_stats_dates($thru_date);
    $con=connect();
    $query = <<<SQL
                SELECT
                  v.last_name
                  ,v.first_name
                  , CASE WHEN rh.route_base = 'SUN' THEN 'BLU'
                       WHEN rh.route_base = 'BFT' THEN 'BEA'
                      ELSE rh.route_base
                      END as area
                  , v.type as roles
                  ,SUM(IF(rh.route_date >= '{$dates['year_to_date']}',1,0)) as YTD
                  ,SUM(IF(rh.route_date >= '{$dates['prior_3_months']}',1,0)) as Prior3
                  ,SUM(IF(rh.route_date >= '{$dates['prior_year']}' AND rh.route_date < '{$dates['year_to_date']}',1,0)) as PriorYear
                  ,COUNT(*) as ALL_TIME
                FROM route_history rh
                JOIN dbVolunteers v on v.id = rh.volunteer_id
                WHERE rh.route_date < '{$dates['end_date']}'
                       group by 1,2,3,4
                order by 1,2
SQL;
    $stats = [];
    $result = mysqli_query($con, $query);
    while ($result_row = mysqli_fetch_assoc($result)) {
        $stats[] = row_to_object($result_row);
    }
    mysqli_close($con);
    return $stats;
}


function route_history_stats_by_volunteer($vid,$thru_date) {
    $dates = calculate_stats_dates($thru_date);
    $con=connect();
    $query = <<<SQL
                SELECT
                  v.last_name
                  ,v.first_name
                  , CASE WHEN rh.route_base = 'SUN' THEN 'BLU'
                       WHEN rh.route_base = 'BFT' THEN 'BEA'
                      ELSE rh.route_base
                      END as area
                  , v.type as roles
                  ,SUM(IF(rh.route_date >= '{$dates['year_to_date']}',1,0)) as YTD
                  ,SUM(IF(rh.route_date >= '{$dates['prior_3_months']}',1,0)) as Prior3
                  ,SUM(IF(rh.route_date >= '{$dates['prior_year']}' AND rh.route_date < '{$dates['year_to_date']}',1,0)) as PriorYear
                  ,COUNT(*) as ALL_TIME
                FROM route_history rh
                JOIN dbVolunteers v on v.id = rh.volunteer_id
                WHERE rh.volunteer_id = '{$vid}'
                      rh.route_date < '{$dates['end_date']}'
                       group by 1,2,3,4
                order by 1,2
SQL;
    
    $result = mysqli_query($con, $query);
    if ($result_row = mysqli_fetch_assoc($result)) {
        return row_to_object($result_row);
    }
    mysqli_close($con);
}


?>