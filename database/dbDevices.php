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
 * @version February 22, 2018
 */

include_once(dirname(__FILE__).'/dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Device.php');

function insert_dbDevices ($device){
    
    $con=connect();

	$query = "SELECT * FROM dbDevices WHERE id = '" . $device->get_id() . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) != 0) {
        delete_dbDevices ($device->get_id());
        $con=connect();
    }
    $query = "INSERT INTO dbDevices VALUES ('".
                $device->get_id()."','" . 
                $device->get_status()."','".
                $device->get_base()."','".
                $device->get_owner()."','".
                $device->get_date_activated()."','".
                $device->get_last_used()."','".
                $device->get_notes()."');";
    $result = mysqli_query($con,$query);
    if (!$result) {
        echo (mysqli_error($con). " unable to insert into dbDevices: " . $device->get_id(). "\n");
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
    return new Device($result_row['id'],$result_row['status'],$result_row['base'],
    		$result_row['owner'],$result_row['date_activated'],$result_row['last_used'],$result_row['notes']);
}
function getall_dbDevices () {
    $con=connect();
    $query = "SELECT * FROM dbDevices ORDER BY id";
    $result = mysqli_query ($con,$query);
    $theDevices = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
    	$theDevices[] = new Device($result_row['id'],$result_row['status'],$result_row['base'],
    			$result_row['owner'],$result_row['date_activated'],$result_row['last_used'],$result_row['notes']);
    }
    return $theDevices; 
}
function getall_dbDeviceIds () {
	$con=connect();
	$query = "SELECT * FROM dbDevices ORDER BY id";
	$result = mysqli_query ($con,$query);
	$theDeviceIds = array();
	while ($result_row = mysqli_fetch_assoc($result)) {
		$theDeviceIds[] = $result_row['id'];
	}
	return $theDeviceIds;
}
function update_dbDevices ($device) {

	if (delete_dbDevices($device->get_id()))
	   return insert_dbDevices($device);
	else {
	   echo (mysqli_error($con)."unable to update dbDevices table: ".$id);
	   return false;
	}
}
function delete_dbDevices($id) {
	$con=connect();
    $query="DELETE FROM dbDevices WHERE id=\"".$id."\"";
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if (!$result) {
		echo (mysqli_error($con)." unable to delete from dbDevices: ".$id);
		return false;
	}
    return true;
}
function pretty($date) {
	if (strlen($date)==8)
		return substr($date,3,2)."/".substr($date,6,2)."/20".substr($date,0,2);
	else return "";
}
?>