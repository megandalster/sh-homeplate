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
include_once(dirname(__FILE__).'/dbVolunteers.php');
include_once(dirname(__FILE__).'/dbClients.php');
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
 * add a route to dbRoutes table: if already there, return false
 */
	function add_route($route){
		if(! $route instanceof Route) die("Error: add_route type mismatch");
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
		             $route->get_status().
		             $route->get_notes().
                     '");');
            mysql_close();
            return true;
   		}
   		mysql_close();
   		return false;
   	}
/*
 * remove a route from dbRoutes table.  If already there, return false
 */
	function remove_route($id) {
		connect();
   		$query = 'SELECT * FROM dbRoutes WHERE id = "'. $id . '"';
		$result = mysql_query($query);
		if ($result==null || mysql_num_rows($result) == 0) {
		   mysql_close();
		   return false;
		}
		$query='DELETE FROM dbRoutes WHERE id = "'.$id.'"';
		$result=mysql_query($query);
		mysql_close();
		return true;
	}
/*
 * @return a single row from dbRoutes table matching a particular id.
 * if not in table, return false
 */
	function get_route($id){
		connect();
   		$query = 'SELECT * FROM dbRoutes WHERE id = "'.$id.'"';
		$result = mysql_query($query);
		if ($result==null || mysql_num_rows($result) !== 1) {
		   mysql_close();
		   return false;
		}
		mysql_close();
   		return $result;
	}


/*
 * @update a row by deleting it and then adding it again
 */
function update_dbRoutes($r) {
	if (! $r instanceof Route)
		die ("Invalid argument for dbRoutes");
	if (delete_dbRoutes($r))
	   return insert_dbRoutes($r);
	else return false;
}

/*
 * create a new route for a particular day and area, add it to the dbRoutes table, 
 * and add its stops to the dbStops table
 */
function make_new_route ($date, $area) {
  // be sure route doesn't already exist.
  if (!get_route($date."-".$area)) {
	$week_no = floor(substr($date,6,2)-1 / 7) + 1;
	$day = date("D",mktime(substr($date,3,2),substr($date,6,2), substr($date(0,2))));
	// find drivers for this date and area from the dbSchedules table
		$drivers = get_driver_ids($area, $week_no, $day);
	// find pickups and dropoffs for this date and area from the dbClients table
		$pickup_stops = getall_clients($area, $day, "donor");
		$dropoff_stops = getall_clients($area, $day, "recipient");
	// find the team captain for this area
	    $teamcaptains = get_team_captains($area);
	// build route and add its stops to dbStops table
		$pickup_ids = array();
		foreach ($pickup_stops as $stop) {
			$pickup_ids[] = $stop->get_id();
			insert_dbStops($stop);
		}
		$dropoff_ids = array();
		foreach ($dropoff_stops as $stop) {
			$dropoff_ids[] = $stop->get_id();
			insert_dbStops($stop);
		}
		$new_route = new Route ($date."-".$area, $drivers, $teamcaptains[0]->get_id(), 
			$pickup_ids, $dropoff_ids, "", "");
	// try to add route to the dbRoutes table
		add_route($new_route);
		return $new_Route;
  }
  else 
	return false;	//route already exists, 
	//so it must be removed from the database before this function is called again.
}
?>
