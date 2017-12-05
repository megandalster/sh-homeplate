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
    $con=connect();
    mysqli_query("DROP TABLE IF EXISTS dbDevices");
    $result = mysqli_query($con,"CREATE TABLE dbDevices (id TEXT NOT NULL, status TEXT NOT NULL, 
    												route TEXT NOT NULL, nickname TEXT)");
    if (!$result) {
        echo mysqli_error($con) . "Error creating dbDevices table. <br>";
        mysqli_close($con);
        return false;
    }
    mysqli_close($con);
    return true;
}

function insert_dbDevices ($id, $status, $route, $nickname){
    
    $con=connect();

	$query = "SELECT * FROM dbDevices WHERE id = '" . $id() . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) != 0) {
        delete_dbDevices ($month->get_id());
        $con=connect();
    }

    $query = "INSERT INTO dbDevices VALUES ('".
                $id."','" . 
                $status."','".
                $route."','".
                $nickname."');";
    $result = mysqli_query($con,$query);
    if (!$result) {
        echo (mysqli_error($con). " unable to insert into dbDevices: " . $month->get_id(). "\n");
        mysqli_close($con);
        return false;
    }
    mysqli_close($con);
    return true;
}
                
function retrieve_dbDevices ($id) {
	$con=connect();
    $query = "SELECT * FROM dbDevices WHERE id = '".$id."'";
    $result = mysqli_query ($con,$query);
    if (mysqli_num_rows($result) !== 1){
    	mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $result_row;   
}
function getall_dbDevices () {
    $con=connect();
    $query = "SELECT * FROM dbDevices ORDER BY id";
    $result = mysqli_query ($con,$query);
    $theDevices = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $theDevices[] = $result_row;
    }
    return $theDevices; 
}

function update_dbDevices ($id, $status, $route, $nickname) {

	if (delete_dbDevices($id))
	   return insert_dbDevices($id, $status, $route, $nickname);
	else {
	   echo (mysqli_error($con)."unable to update dbDevices table: ".$id);
	   return false;
	}
}

function delete_dbDevices($id) {
	$con=connect();
    $query="DELETE FROM dbDevices WHERE id=\"".$id."\"";
	$result=mysqli_query($con,$query);
	mysqli_close();
	if (!$result) {
		echo (mysqli_error($con)." unable to delete from dbDevices: ".$id);
		return false;
	}
    return true;
}