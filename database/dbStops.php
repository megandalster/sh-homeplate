<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * dbStops module for SH Homeplate
 * @author Nicholas Wetzel and Alex Lucyk
 * @version February 22, 2012
 */

include_once(dirname(__FILE__).'/../domain/Stop.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbStops() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbStops");
    $result = mysql_query("CREATE TABLE dbStops (id TEXT NOT NULL, route TEXT NOT NULL, client TEXT NOT NULL,
     											 type TEXT NOT NULL, items TEXT, weight TEXT, notes TEXT)");
    mysql_close();
    if (!$result) {
        echo mysql_error() . "Error creating dbStops table. <br>";
        return false;
    }
    return true;
}

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
                
function retrieve_dbStops ($id) {
	connect();
    $query = "SELECT * FROM dbStops WHERE id = '".$id."'";
    $result = mysql_query ($query);
    if (mysql_num_rows($result) !== 1){
    	mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $result_row['items'], $result_row['notes']);
	mysql_close(); 
    return $theStop;   
}
function getall_dbStops () {
    connect();
    $query = "SELECT * FROM dbStops ORDER BY id";
    $result = mysql_query ($query);
    $theStops = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $result_row['items'], $result_row['notes']);
        $theStops[] = $theStop;
    }
	mysql_close();
    return $theStops; 
}

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