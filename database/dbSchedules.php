<?php
/*
* Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homecheck, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).
*/

/*
* dbSchedules table for Homeplate -- this is the master schedule from which drivers
* are assigned to routes.  
* It is created with a fixed number of rows, 5x7=35 for each of the three areas,
* and each row initially has no drivers assigned.  The GUI will support the addition and
* removal of individual drivers from any row.    
* So the function insert_dbSchedules should not be called from anywhere outside 
* the create_dbSchedules function in this module.  
* @author Hartley Brody
* @version March 7, 2012
*/

include_once(dirname(__FILE__).'/../domain/ScheduleEntry.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbSchedules(){
	connect();
	mysql_query("DROP TABLE IF EXISTS dbSchedules");
	$result = mysql_query("CREATE TABLE dbSchedules (id TEXT NOT NULL, area TEXT, drivers TEXT, notes TEXT)");
	mysql_close();
	if(!$result){
			echo (mysql_error()."Error creating database table dbSchedules. \n");
			return false;
	}
	// populate the table with 105 rows (35 for each area).
	$areas = array("HHI","SUN","BFT");
	$days = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
	foreach ($areas as $area) 
		for ($i=1; $i<=5; $i++)   // week of the month
			foreach ($days as $day) {   // day of the week
				$se = new ScheduleEntry($area, $day.":".$i, "", "");
				insert_dbSchedules($se);
			}
	return true;
}

function retrieve_dbSchedules($area, $id){
	connect();
	$result=mysql_query("SELECT * FROM dbSchedules WHERE id  = '".$id."' AND area = '".$area."'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);

	mysql_close();
	return $theScheduleEntry;
}


function getall_dbSchedules(){
	connect();
	$result = mysql_query("SELECT * FROM dbSchedules ORDER BY last_name");
	$theScheduleEntries = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);
		$theScheduleEntries[] = $theScheduleEntry;
	}
	mysql_close();
	return $theScheduleEntries;
}
// insert_dbSchedules works as an update.  That is, if the entry is already there, it is replaced
// in the table.  Otherwise, it is added to the table.  The unique key for this table is $area . $id.
function insert_dbSchedules($scheduleentry){
	if(! $scheduleentry instanceof ScheduleEntry){
		return false;
	}
	connect();
	$query = "SELECT * FROM dbSchedules WHERE id = '" . $scheduleentry->get_id() ."' AND area = '".$scheduleentry->get_area()."'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		delete_dbScheduleEntries ($scheduleentry->get_id());
		connect();
	}
	$query = "INSERT INTO dbSchedules VALUES ('".
				$scheduleentry->get_id()."','" .
				$scheduleentry->get_area()."','" .
				implode(',',$scheduleentry->get_drivers())."','".
				$scheduleentry->get_notes().
	            "');";
	$result = mysql_query($query);
	if (!$result) {
		echo (mysql_error(). " Unable to insert into dbSchedules: " . $scheduleentry->get_area().$scheduleentry->get_id(). "\n");
		mysql_close();
		return false;
	}
	mysql_close();
	return true;
	
}

function update_dbSchedules($scheduleentry){
	if (! $scheduleentry instanceof ScheduleEntry) {
		echo ("Invalid argument for update_dbSchedules function call");
		return false;
	}
	if (delete_dbScheduleEntries($scheduleentry->get_area(), $scheduleentry->get_id()))
		return insert_dbScheduleEntries($scheduleentry);
	else {
		echo (mysql_error()."unable to update dbSchedules table: ".$scheduleentry->get_area().$scheduleentry->get_id());
		return false;
	}
}

function delete_dbSchedules($area, $id){
	connect();
	$result = mysql_query("DELETE FROM dbSchedules WHERE id =\"".$id."' AND area = '".$area."\"");
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbSchedules: ".$area.$id);
		return false;
	}
	return true;
}

function get_drivers($area, $week, $day) {
	connect();
	$result = mysql_query("SELECT * FROM dbSchedules WHERE id=" .
		$day.":".$week." AND area=".$area." ORDER BY last_name");
	if ($result_row = mysql_fetch_assoc($result))
		$theDrivers = explode(',',retrieve_dbVolunteers($result_row['drivers']));
	else $theDrivers = null;
	mysql_close();
	return $theDrivers;
}
// add a driver to a schedule
function add_driver ($area, $week, $day, $driver_id) {
	
}
// remove a driver from a schedule
function remove_driver ($area, $week, $day, $driver_id) {
	
}
?>