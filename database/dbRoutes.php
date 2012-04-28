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
		foreach ($r->get_pickup_stops() as $pickup_id) 
			delete_dbStops($pickup_id);
		foreach ($r->get_dropoff_stops() as $dropoff_id) 
			delete_dbStops($dropoff_id);
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
function update_dbRoutes($r) {
	if (! $r instanceof Route)
		die ("Invalid argument for update_dbRoutes");
	if (delete_dbRoutes($r))
	   return insert_dbRoutes($r);
	else return false;
}

/*
 * create a new route for a particular day and area, add it to the dbRoutes table, 
 * and add its stops to the dbStops table
 */
function make_new_route ($routeID, $teamcaptain_id) {
  // be sure route doesn't already exist.
  if (!get_route($routeID)) {
  	$area = substr($routeID,9);
	$date = substr($routeID,0,8); 
//	$week_no = floor((substr($date,6,2)-1) / 7) + 1;
	$day = date('D',mktime(0,0,0,substr($date,3,2),substr($date,6,2),substr($date,0,2)));
	
	// find drivers for this date and area from the dbSchedules table
		$driver_ids = get_driver_ids($area, $day);
	// store pickup and dropoff stops for this date and area using the dbClients table
		$pickup_clients = getall_clients($area, "donor", "", "", array($day));
		$pickup_ids = "";
		foreach ($pickup_clients as $client) {
			$pickup_stop = new Stop ($routeID, $client->get_id(), "pickup", "", "");
			$pickup_ids .= ",".$pickup_stop->get_id();
			insert_dbStops($pickup_stop);
		}
		$pickup_ids = substr($pickup_ids,1);
		$dropoff_clients = getall_clients($area, "recipient", "", "", array($day));
  		$dropoff_ids = "";
		foreach ($dropoff_clients as $client) {
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
		return $new_Route;
  }
  else 
	return false;	//route already exists, can't add a duplicate for same day and area
}
?>
