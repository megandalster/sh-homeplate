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
//    include_once('database/dbLog.php');
$routeID = $_GET['routeID'];
$route = get_route($routeID);
if(! $route)
	$route = make_new_route($routeID,$_SESSION['_id']);
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
//	echo '<br><fieldset><legend>Change Summary</legend>';
	echo "<p>".$message;
//	echo '</fieldset><br>';
}
if ($_POST['deleteMe']=="DELETE") {
	echo "<br><br><a href='viewRoutes.php?area=".substr($routeID,9)."&date=".substr($routeID,0,8)."'>Back to Routes</a>";
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
function process_form($_POST, $route)
{
	/* respond to the POST
	if($_POST['change_status'] != $route->get_status() && $_POST['change_status']!="")
	{
		$route->change_status($_POST['change_status']);
		update_dbRoutes($route);
		return ("Status changed to ". $_POST['change_status'].".");
	}
	*/
	// remove a driver from the route
	if($_POST['remove_driver']){
		$selected = "";
		foreach($_POST['s_driver'] as $driver_id) {
			$driver = retrieve_dbVolunteers($driver_id);
			$selected .= ", ".$driver->get_first_name() . " " . $driver->get_last_name();
			$route->remove_driver($driver_id);
		}
		update_dbRoutes($route);
		return ("Driver removed: ". substr($selected, 2));
	}
	/*
	// remove a pick up from the route
	if($_POST['remove_pickup']){
		$selected = "";
		delete_dbRoutes($route);
		foreach($_POST['s_pickup'] as $pickup_id) {
			$selected .= ", ".substr($pickup_id,12);
			$route->remove_pick_up($pickup_id);
		}
		insert_dbRoutes($route);
		return ("Pickups removed: ". substr($selected, 2));
	}
	// remove a drop off from the route
	if($_POST['remove_dropoff']){
		$selected = "";
		delete_dbRoutes($route);
		foreach($_POST['s_dropoff'] as $dropoff_id) {
			$selected .= ", ".substr($dropoff_id,12);
			$route->remove_drop_off($dropoff_id);
		}
		insert_dbRoutes($route);
		return ("Dropoffs removed: ". substr($selected, 2));
	}
	*/
	// add a new driver to the route
	else if ($_POST['add_driver']) {
		$route->add_driver($_POST['add_driver']);
		update_dbRoutes($route);
		$driver = retrieve_dbVolunteers($_POST['add_driver']);
		return ("New crew member added: ". $driver->get_first_name() . " " . $driver->get_last_name());
	}
	/*
	// add a new pick up to the route
	if ($_POST['add_pickup']) {
		delete_dbRoutes($route);
		$route->add_pick_up($_POST['add_pickup']);
		insert_dbRoutes($route);
		return ("New pickup added: ". substr($_POST['add_pickup'],12));
	}
	// add a new drop off to the route
	if ($_POST['add_dropoff']) {
		delete_dbRoutes($route);
		$route->add_drop_off($_POST['add_dropoff']);
		insert_dbRoutes($route);
		return ("New dropoff added: ". substr($_POST['add_dropoff'],12));
	}
	*/
	else if($_POST['deleteMe']=="DELETE"){
		delete_dbRoutes($route);
		return ("Route deleted: ". $route->get_area() . " " . $route->get_day());
	}
	return "No changes made!";
}
?>
