<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
 * Tucker.  This program is part of Homeplate, which is free software.  It comes
 * with absolutely no warranty. You can redistribute it and/or modify it under the
 * terms of the GNU General Public License published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 */

/**
 * @version Feb 23, 2013
 * @author Allen Tucker and Richardo Hopkins
 */

include_once(dirname(__FILE__).'/../domain/Route.php');
include_once(dirname(__FILE__).'/dbStops.php');
// include_once(dirname(__FILE__).'/dbVolunteers.php');
include_once(dirname(__FILE__).'/dbClients.php');
include_once(dirname(__FILE__).'/dbSchedules.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbRoutes() {
	connect();
	mysql_query("DROP TABLE IF EXISTS dbRoutes");
	$result = mysql_query("CREATE TABLE dbRoutes(id TEXT NOT NULL, drivers TEXT, teamcaptain_id TEXT, " .
				"pickup_stops TEXT, dropoff_stops TEXT, status TEXT, notes TEXT)");
	mysql_close();
	if(!$result){
		echo (mysql_error()."Error creating database table dbRoutes. \n");
		return false;
	}
	return true;
}
/*
 * insert a route to dbRoutes table and its stops to the dbStops table:
 * if already there, return false
 */
function insert_dbRoutes($route){
        if(! $route instanceof Route) die("Error: insert_dbRoutes type mismatch");
        connect();
        $query = "SELECT * FROM dbRoutes WHERE id = '".$route->get_id()."'";
        $result = mysql_query($query);
        //if there's no entry for this id, add it
        if ($result == null || mysql_num_rows($result) == 0) {
                mysql_query('INSERT INTO dbRoutes VALUES("'.
                $route->get_id().'","'.
                implode(',', $route->get_drivers()).'","'.
                $route->get_teamcaptain_id().'","'.
                implode(',', $route->get_pickup_stops()).'","'.
                implode(',', $route->get_dropoff_stops()).'","'.
                $route->get_status().'","'.
                $route->get_notes().
                     '");');
                mysql_close();
                foreach ($route->get_pickup_stops() as $pickup_id) {
                        $pickup_stop = new Stop ($route->get_id(), substr($pickup_id,12), "pickup", "", "");
                        insert_dbStops($pickup_stop);
                }
                foreach ($route->get_dropoff_stops() as $dropoff_id) {
                        $dropoff_stop = new Stop ($route->get_id(), substr($dropoff_id,12), "dropoff", "", "");
                        insert_dbStops($dropoff_stop);
                }
                return true;
        }
        mysql_close();
        return false;
}
/*
 * insert a completed route to dbRoutes table and its stops to the dbStops table:
 * insert the weights and drivers actually recorded
 * if already there, return false
 */
function insert_completed_dbRoutes($route){
	if(! $route instanceof Route) die("Error: insert_dbRoutes type mismatch");
	connect();
	$query = "SELECT * FROM dbRoutes WHERE id = '".$route->get_id()."'";
	$result = mysql_query($query);
	mysql_close();
	//if there's no entry for this id, add it
	if ($result == null || mysql_num_rows($result) == 0) {
		$pickupids = array();
		foreach ($route->get_pickup_stops() as $stop_data) {
				$stop_data = substr($stop_data,12);
				$firstcomma = strpos($stop_data, ",");
				$stop_id = substr($stop_data,0,$firstcomma);
				$stop_data = substr($stop_data,$firstcomma+1);
				$secondcomma = strpos($stop_data,',');
				if ($secondcomma)
					$stop_data = substr($stop_data,$secondcomma+1);
				$pickup_stop = new Stop ($route->get_id(), $stop_id, "pickup", $stop_data, "");
				insert_dbStops($pickup_stop);
				$pickupids[] = $route->get_id().$stop_id;
		}
		$route->set_pickup_stops($pickupids);
		$dropoffids = array();
		foreach($route->get_dropoff_stops() as $stop_data) {
				$stop_data = substr($stop_data,12);
				$firstcomma = strpos($stop_data, ",");
				$stop_id = substr($stop_data,0,$firstcomma);
				$stop_data = substr($stop_data,$firstcomma+1);
				$dropoff_stop = new Stop ($route->get_id(), $stop_id, "dropoff", $stop_data, "");
				insert_dbStops($dropoff_stop);
				$dropoffids[] = $route->get_id().$stop_id;
		}
		$route->set_dropoff_stops($dropoffids);
		connect();
		mysql_query('INSERT INTO dbRoutes VALUES("'.
                $route->get_id().'","'.
                implode(',', $route->get_drivers()).'","'.
                $route->get_teamcaptain_id().'","'.
                implode(',', $route->get_pickup_stops()).'","'.
                implode(',', $route->get_dropoff_stops()).'","'.
                $route->get_status().'","'.
                $route->get_notes().
                     '");');
		mysql_close();
		return true;
	}
	mysql_close();
	return false;
}
/* reconstruct completed route's stops as if they are coming from the tablet
 * 
 */
function rebuild_original_stops($r, $type) {
	if ($type=="pickup") {
		$originals = array();
		foreach ($r->get_pickup_stops() as $pickup_id) {
			$pickup_stop = 	retrieve_dbStops($pickup_id);
			$pickup_stop_string = $pickup_stop->get_id().",".$pickup_stop->get_total_weight();
			if (sizeof($pickup_stop->get_items()) > 0) {
				$itemstring = implode(",",$pickup_stop->get_items());
				$pickup_stop_string = $pickup_stop_string . "," . $itemstring;
			}
			$originals[] = $pickup_stop_string;
		}
	}
	else {
		$originals = array();
		foreach ($r->get_dropoff_stops() as $dropoff_id) {
			$dropoff_stop = retrieve_dbStops($dropoff_id);
			$dropoff_stop_string = $dropoff_stop->get_id().",".$dropoff_stop->get_total_weight();
			$originals[] = $dropoff_stop_string;
		}
	}
	return $originals;
}
/*
 * remove a route from dbRoutes table and all its stops from the dbStops table.
 * If not there, return false
 */
function delete_dbRoutes($r) {
	connect();
	$query = 'SELECT * FROM dbRoutes WHERE id = "'. $r->get_id() . '"';
	$result = mysql_query($query);
	if ($result==null || mysql_num_rows($result) == 0) {
		mysql_close();
		return false;
	}
	$query='DELETE FROM dbRoutes WHERE id = "'.$r->get_id().'"';
	$result=mysql_query($query);
	mysql_close();
	foreach ($r->get_pickup_stops() as $pickup_id) {
		$i = strpos($pickup_id,",");
		if ($i>0) $pickup_id = substr($pickup_id,0,$i);
		delete_dbStops($pickup_id);
	}
	foreach ($r->get_dropoff_stops() as $dropoff_id) {
		$i = strpos($dropoff_id,",");
		if ($i>0) $dropoff_id = substr($dropoff_id,0,$i);
		delete_dbStops($dropoff_id);
	}
	return true;
}
/*
 * @return a single row from dbRoutes table matching a particular id.
 * if not in table, return false
 */
function get_route($id){
	connect();
	$query = 'SELECT * FROM dbRoutes WHERE id = "'.$id.'"';
	
	//echo $query . "<br />";
	
	$result = mysql_query($query);
	if ($result==null || mysql_num_rows($result) !== 1) {
		mysql_close();
		return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theRoute = new Route($result_row['id'],
	$result_row['drivers'],
	$result_row['teamcaptain_id'],
	$result_row['pickup_stops'],
	$result_row['dropoff_stops'],
	$result_row['status'],
	$result_row['notes']);
	mysql_close();
	return $theRoute;
}
/*
 * @update a row by deleting it and then adding it again
 */
function update_completed_dbRoutes($r) {
	if (! $r instanceof Route)
	die ("Invalid argument for update_dbRoutes");
	if (delete_dbRoutes($r))
		return insert_completed_dbRoutes($r);
	else return false;
}
/*
 * @update a row by deleting it and then adding it again
 */
function update_dbRoutes($r) {
	if (! $r instanceof Route)
	die ("Invalid argument for update_dbRoutes");
	if (delete_dbRoutes($r))
		return insert_dbRoutes($r);
	else return false;
}

/*
 * create a new route for a particular day and base, add it to the dbRoutes table,
 * and add its stops to the dbStops table
 */
function make_new_route ($routeID, $teamcaptain_id) {
	// be sure route doesn't already exist.
	if (!get_route($routeID))
	{
		$area = substr($routeID,9);
		$date = substr($routeID,0,8);
		$month_weeks = array(1=>"1st",2=>"2nd", 3=>"3rd", 4=>"4th", 5=>"5th");
		if ($area=="BFT") {
			$week = substr($month_weeks[floor((substr($date,6,2)-1) / 7) + 1],0,1);
			//floor(($dayCount-1) / 7)
		}
		else {
			$week_of_year = date ("W",mktime(0,0,0,substr($date,3,2),substr($date,6,2),substr($date,0,2)));
			if ($week_of_year % 2 == 0)
				$week = "even";
			else $week = "odd";
		}
		$day = date('D',mktime(0,0,0,substr($date,3,2),substr($date,6,2),substr($date,0,2)));
		
		// find drivers for this date and area (aka base) from the dbSchedules table
		// calculate the week of the month or year, depending on the area
		$driver_ids = implode(',',get_drivers_scheduled($area, $week, $day));

		// store pickup and dropoff stops for this date and area using the dbClients table
		$pickup_clients = getall_clients($area, "donor", "", "", array($day), "", "");
		$pickup_ids = "";

		foreach ($pickup_clients as $client)
		{
			$pickup_stop = new Stop ($routeID, $client->get_id(), "pickup", "", "");
			$pickup_ids .= ",".$pickup_stop->get_id();
			insert_dbStops($pickup_stop);
		}
		$pickup_ids = substr($pickup_ids,1);
		
		
		$dropoff_clients = getall_clients($area, "recipient", "", "", array($day), "", "");
		$dropoff_ids = "";
		
		foreach ($dropoff_clients as $client) 
		{
			$dropoff_stop = new Stop ($routeID, $client->get_id(), "dropoff", "", "");
			$dropoff_ids .= ",".$dropoff_stop->get_id();
			insert_dbStops($dropoff_stop);
		}
		$dropoff_ids = substr($dropoff_ids,1);
		
		// build route for this date and area
		$new_route = new Route ($routeID, $driver_ids, $teamcaptain_id,
		$pickup_ids, $dropoff_ids, "", "");
		
		if (!$new_route) echo ("route wasnt created ".$routeID);
		// add route to the dbRoutes table
		else if (!insert_dbRoutes($new_route))
		echo "route not added to the database";
		
		return $new_route;
	}
	else
		return false;	//route already exists, can't add a duplicate for same day and area
}

// automatically regenerate all routes for the next 2 weeks from today
function autogenerate_routes () {
	$areas = array("BFT", "HHI", "SUN");
	$todayUTC = time();
	foreach ($areas as $area) {
	  for ($dayUTC = $todayUTC; $dayUTC <= $todayUTC + 1209600; $dayUTC += 86400) {
		$routeID = date('y-m-d', $dayUTC).'-'.$area;
		$route = get_route($routeID);
		if (!$route) {
			$day = date("D",mktime(0,0,0,substr($routeID,3,2),substr($routeID,6,2),substr($routeID,0,2)));
			$team_captains = get_team_captains(substr($routeID,9), $day);
			if (sizeof($team_captains)==0)
				$team_captain = "no day captain";   // force a day captain if there are none
			else $team_captain = $team_captains[0]->get_id();
			$route = make_new_route($routeID,$team_captain);
		}
	  }
	}
}
// for completed routes, the total weights follow the first comma in the pickup/
// dropoff id.  That is, the route $r is not a properly formed route in this regard,
// so we inspect the total weights to determine if the route is worth keeping (has any
// non-zero weights).
function has_nonzero_pickup_weight($r) {
	$ps = $r->get_pickup_stops();
	foreach($ps as $p) {
		$i = strpos($p, ",");
		$weight = substr($p, $i+1);
		if (substr($weight,0,1)!="0") 
			return true;
	}
	return false;	
}
function has_nonzero_dropoff_weight($r) {
	$ds = $r->get_dropoff_stops();
	foreach($ds as $d) {
		$i = strpos($d, ",");
		$weight = substr($d, $i+1);
		if (substr($weight,0,1)!="0") 
			return true;
	}
	return false;	
}
?>
