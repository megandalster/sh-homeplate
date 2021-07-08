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
session_cache_expire(30);
include_once('database/dbRoutes.php');
include_once('domain/Route.php');
include_once('domain/Client.php');
include_once('database/dbClients.php');
include_once('domain/Volunteer.php');
include_once('database/dbVolunteers.php');
include_once('domain/Stop.php');
include_once('database/dbStops.php');
//    include_once('database/dbLog.php');
$routeID = $_GET['routeID'];
$route = get_route($routeID);
$day = date("D",mktime(0,0,0,substr($routeID,3,2),substr($routeID,6,2),substr($routeID,0,2)));
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

if($_POST['_form_submit'] == 1)
{
	$message = process_form($_POST, $route);
	echo "<p>".$message;
	include('routeForm.inc');
}
else if ($_POST['deleteMe']=="DELETE" && $_SESSION['access_level']>=2) {
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
function process_form($_POST_PARAM, &$route)
{
	// add a new driver to the route, but don't disturb weights on existing stops
	if ($_POST['add_driver']) {
		$route->add_driver($_POST['add_driver']);
		mild_update_dbRoutes($route);
		$driver = retrieve_dbVolunteers($_POST['add_driver']);
		return ("New crew member added: ". $driver->get_first_name() . " " . $driver->get_last_name());
	}
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
