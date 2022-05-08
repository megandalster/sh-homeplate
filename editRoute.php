<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
 */

/*
 *	editRoute.php
 *  oversees the editing of a route to be added, changed, or deleted from the database
 *	@author Allen Tucker and Nick Wetzel
 *	@version April 15, 2012
 */
session_start();
////session_cache_expire(30);
include_once('database/dbRoutes.php');
include_once('domain/Route.php');
include_once('domain/Client.php');
include_once('database/dbClients.php');
include_once('domain/Volunteer.php');
include_once('database/dbVolunteers.php');
include_once('domain/Stop.php');
include_once('database/dbStops.php');
include_once('database/dbRouteHistory.php');
//    include_once('database/dbLog.php');
$routeID = $_GET['routeID'];
$route = get_route($routeID);
$day = date("D",mktime(0,0,0,substr($routeID,3,2),substr($routeID,6,2),substr($routeID,0,2)));
$today = date('y-m-d');

$team_captains = get_team_captains(substr($routeID,9), $day);
if (sizeof($team_captains)==0)
	$team_captain = "Lisa8437152491";   // force a day captain if there are none
else $team_captain = $team_captains[0]->get_id();
if(! $route)
	$route = make_new_route($routeID,$team_captain);
?>
<html>
<head>
<title>Editing Route</title>
<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>
<div id="container"><?PHP include('header.php');?>
<div id="content"><?PHP

if(array_key_exists('_form_submit',$_POST) && $_POST['_form_submit'] == 1)
{
	$message = process_form($_POST, $route, $today);
	echo "<p>".$message;
	include('routeForm.inc');
}
else if (array_key_exists('deleteMe',$_POST) && $_POST['deleteMe']=="DELETE" && $_SESSION['access_level']>=2) {
	echo "<br><br><a href='viewRoutes.php?area=".substr($routeID,9)."&date=".substr($routeID,0,8)."'>Return to routes.</a>";
	echo('</div></div>');
	include('footer.inc');
	echo('</body></html>');
}
else {
	include('routeForm.inc');
}

/**
 * process_form changes the status of a route,
 * adds and removes drivers, pick-ups, and drop-offs and
 * returns a message reporting the result
 */
function process_form($_POST_PARAM, &$route, $today)
{
    // only used to start route_history
//    if ($_POST['sync_route_history']) {
//        sync_to_last_trip_dates('22-05-01');
//        return ("Synced");
//    }
    
    
    // add a new pick up to the route, but don't disturb weights on existing stops
    if ($_POST['add_pickup']) {
        $route->add_pick_up($_POST['add_pickup']);
        mild_update_dbRoutes($route);
        return ("New pickup added: ". substr($_POST['add_pickup'],12));
    }
    // add a new drop off to the route, but don't disturb weights on existing stops
    if ($_POST['add_dropoff']) {
        $route->add_drop_off($_POST['add_dropoff']);
        mild_update_dbRoutes($route);
        return ("New dropoff added: ". substr($_POST['add_dropoff'],12));
    }
    // add a new driver to the route, but don't disturb weights on existing stops
    if ($_POST['add_driver']) {
        $add_id = $_POST['add_driver'];
        $route->add_driver($add_id);
        mild_update_dbRoutes($route);
        $driver = retrieve_dbVolunteers($add_id);
    
        // add trip to driver if trip was completed
        if ($route->get_status()=="completed") {
            $routedate =  substr($route->get_id(),0,8);
            $result = $driver->insert_lastTripDates($routedate);
            if ($result) {
                update_dbVolunteers($driver); // update only if there is a change
            }
        }
        return ("New crew member added: ". $driver->get_first_name() . " " . $driver->get_last_name());
    }
    
    // update drivers' Trip Count and Last Trip Date
    if ($_POST['onboard']) {
        $routedate =  substr($route->get_id(),0,8);
        route_history_remove($route->get_id());
        $vids = [];
        foreach ($route->get_drivers() as $driver_id) {
            $driver = retrieve_dbVolunteers($driver_id);
            $result = false;
            if (!in_array($driver_id, $_POST['onboard'])) {  // driver is ununchecked -- remove this date from his last trips
 //               echo "<br><br>before removal ".$routedate; var_dump($driver->get_lastTripDates(), $driver->get_tripCount());
                $result = $driver->remove_lastTripDates($routedate);
 //               echo "<br>after removal ".$routedate; var_dump($driver->get_lastTripDates(), $driver->get_tripCount());
                if ($result)
                    update_dbVolunteers($driver); // update only if there is a change
            }
            else {  // driver is checked -- add this date to his last trips
//                echo "<br><br>before insert ".$routedate; var_dump($driver->get_lastTripDates(), $driver->get_tripCount());
                $vids[] = $driver->get_id();
                $result = $driver->insert_lastTripDates($routedate);
//                echo "<br>after insert ".$routedate; var_dump($driver->get_lastTripDates(), $driver->get_tripCount());
                if ($result)
                    update_dbVolunteers($driver); // update only if there is a change
            }
        }
        
        route_history_add($route->get_id(),$vids);
        return ("Crew onboard updated");
    }
    if($_POST['deleteMe']=="DELETE"){
        delete_dbRoutes($route);
        return ("Route deleted: ". $route->get_area() . " " . $route->get_day());
    }
	return "No changes made!";
}
function add_enterer(&$theStop, &$route) {
    if (substr($theStop->get_notes(),0,1)=="!") {
        $theStop->set_notes(substr($theStop->get_notes(),1)); // remove marker
        update_dbStops($theStop);
        if (strpos($route->get_notes(),$_SESSION['name'])!==false) 
            return;  // don't duplicate the enterer's name
        else {
            $route->merge_notes($_SESSION['name']); // otherwise track the enterer
            $route->set_status("completed");
            mild_update_dbRoutes($route);
        }
    }
    return;
}
?>
