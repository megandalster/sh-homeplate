<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * dbDevices module -- keeps track of Android devices in use by SH Homeplate trucks
 * @author Allen Tucker
 * @version August 22, 2012
 */

include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbDevices() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbDevices");
    $result = mysql_query("CREATE TABLE dbDevices (id TEXT NOT NULL, status TEXT NOT NULL, 
    												route TEXT NOT NULL, nickname TEXT)");
    mysql_close();
    if (!$result) {
        echo mysql_error() . "Error creating dbDevices table. <br>";
        return false;
    }
    return true;
}

function insert_dbDevices ($id, $status, $route, $nickname){
    
    connect();

	$query = "SELECT * FROM dbDevices WHERE id = '" . $id() . "'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) != 0) {
        delete_dbDevices ($month->get_id());
        connect();
    }

    $query = "INSERT INTO dbDevices VALUES ('".
                $id."','" . 
                $status."','".
                $route."','".
                $nickname."');";
    $result = mysql_query($query);
    if (!$result) {
        echo (mysql_error(). " unable to insert into dbDevices: " . $month->get_id(). "\n");
        mysql_close();
        return false;
    }
    mysql_close();
    return true;
}
                
function retrieve_dbDevices ($id) {
	connect();
    $query = "SELECT * FROM dbDevices WHERE id = '".$id."'";
    $result = mysql_query ($query);
    if (mysql_num_rows($result) !== 1){
    	mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    return $result_row;   
}
function getall_dbDevices () {
    connect();
    $query = "SELECT * FROM dbDevices ORDER BY id";
    $result = mysql_query ($query);
    $theDevices = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $theDevices[] = $result_row;
    }
    return $theDevices; 
}

function update_dbDevices ($id, $status, $route, $nickname) {

	if (delete_dbDevices($id))
	   return insert_dbDevices($id, $status, $route, $nickname);
	else {
	   echo (mysql_error()."unable to update dbDevices table: ".$id);
	   return false;
	}
}

function delete_dbDevices($id) {
	connect();
    $query="DELETE FROM dbDevices WHERE id=\"".$id."\"";
	$result=mysql_query($query);
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbDevices: ".$id);
		return false;
	}
    return true;
}