<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * dbStops module for Homeplate
 * @author Nicholas Wetzel
 * @version May 8, 2012
 */

/*
 * This module implements all functionality with the 'Stop' database using mySQL queries. 
 */

include_once(dirname(__FILE__).'/../domain/Stop.php');
include_once(dirname(__FILE__).'/dbinfo.php');

// Create the DB stops table with the necessary column values.
function create_dbStops() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbStops");
    $result = mysql_query("CREATE TABLE dbStops (id TEXT NOT NULL, route TEXT NOT NULL, client TEXT NOT NULL, 
     											 type TEXT NOT NULL, items TEXT, weight TEXT, date TEXT, notes TEXT)");
    mysql_close();
    if (!$result) {
        echo mysql_error() . "Error creating dbStops table. <br>";
        return false;
    }
    return true;
}

// Insert a stop and all of its values into the DB.
function insert_dbStops ($stop){
    if (! $stop instanceof Stop) {
        return false;
    }
    connect();

	$query = "SELECT * FROM dbStops WHERE id = '" . $stop->get_id() . "'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) != 0) {
        delete_dbStops ($stop->get_id());
        connect();
    }
    $query = "INSERT INTO dbStops VALUES ('".
                $stop->get_id()."','" .
                $stop->get_route_id()."','".
                $stop->get_client_id()."','".
                $stop->get_type()."','".
                implode(',',$stop->get_items())."','".
                $stop->get_total_weight()."','".
                $stop->get_date()."','".
                $stop->get_notes()."');";
    $result = mysql_query($query);
    if (!$result) {
        echo (mysql_error(). " unable to insert into dbStops: " . $stop->get_id(). "\n");
        mysql_close();
        return false;
    }
    mysql_close();
    return true;
}

// Retrieve a stop from the DB by passing the stop ID.
function retrieve_dbStops ($id) {
	connect();
    $query = "SELECT * FROM dbStops WHERE id = '".$id."'";
    $result = mysql_query ($query);
    if (mysql_num_rows($result) !== 1){
    	mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $items = $result_row['items'];
    if ($result_row['items']=="" || $result_row['items']=="Meat:,Frozen:,Bakery:,Grocery:,Dairy:,Produce:")
    	$items = $result_row['weight']; 
    $theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $items, $result_row['notes']);
	mysql_close(); 
    return $theStop;   
}

// Return all stops from the DB.
function getall_dbStops () {
    connect();
    $query = "SELECT * FROM dbStops ORDER BY id";
    $result = mysql_query ($query);
    $theStops = array();
    while ($result_row = mysql_fetch_assoc($result)) {
    	$items = $result_row['items'];
    	if ($result_row['items']=="")
    		$items = $result_row['weight'];
        $theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $items, $result_row['notes']);
        $theStops[] = $theStop;
    }
	mysql_close();
    return $theStops; 
}

// Returns all stops within a certain date range.
function getall_dbStops_between_dates ($area, $type, $start_date, $end_date) {
	connect();
	$query = "SELECT route, client, type, SUM(weight), notes FROM dbStops where ".
			"route like '%".$area."%' AND ".
			"type like '%".$type."%' AND ".
			"date >= '". $start_date . "' AND date <= '". $end_date . "' GROUP BY client";
    $result = mysql_query ($query);
    $theStops = array();
    while ($result_row = mysql_fetch_assoc($result)) {
    	// The total weight of the stop is returned instead of its items for the purpose
    	// of generating reports with each stop's total weight.
    	$theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $result_row['SUM(weight)'], $result_row['notes']);
        $theStops[] = $theStop;
    }
	mysql_close();
    return $theStops; 
}

// Returns all stops within a certain date range.
function getall_dbWalmartPublixStops_between_dates ($area, $start_date, $end_date) {
	connect();
	$query = "SELECT route, client, type, items, notes FROM dbStops where ".
			"route like '%".$area."%' AND ".
			"items like '%:%' and ".
			"date >= '". $start_date . "' AND date <= '". $end_date . "' ORDER BY client";
    $result = mysql_query ($query);
    $theStops = array();
    while ($result_row = mysql_fetch_assoc($result)) {
    	// The total weight of the stop is returned instead of its items for the purpose
    	// of generating reports with each stops total weight.
        $theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $result_row['items'], $result_row['notes']);
        $theStops[] = $theStop;
    }
	mysql_close();
    return $theStops; 
}


// Update the values of a specified stop by removing it from the DB and then
// inserting it again.
function update_dbStops ($stop) {
if (! $stop instanceof Stop) {
		echo ("Invalid argument for update_dbStops function call");
		return false;
	}
	if (delete_dbStops($stop->get_id()))
	   return insert_dbStops($stop);
	else {
	   echo (mysql_error()."unable to update dbStops table: ".$stop->get_id());
	   return false;
	}
}

// Remove a stop and all of its values from the DB.
function delete_dbStops($id) {
	connect();
    $query="DELETE FROM dbStops WHERE id=\"".$id."\"";
	$result=mysql_query($query);
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbStops: ".$id);
		return false;
	}
    return true;
}