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
* It is created with a fixed number of rows, 5x7=35 for the Beaufort area (5-week monthly schedule)
* and 2x7=14 rows for each of the Hilton Head and Bluffton areas (alternate-week schedule),
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
	// populate the table 
	$areas = array("HHI","SUN");
	$weekly_groups = array("odd","even");
	$monthly_groups = array("1","2", "3", "4", "5");
	$days = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
	foreach ($monthly_groups as $monthly_group)
		foreach ($days as $day) {   // day of the week
			$se = new ScheduleEntry("BFT", $day.":".$monthly_group, "", "");
			insert_dbSchedules($se);
		}
	foreach ($areas as $area) 
		foreach ($weekly_groups as $week)   // week of the year
			foreach ($days as $day) {   // day of the week
				$se = new ScheduleEntry($area, $day.":".$week, "", "");
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
	$result = mysql_query("SELECT * FROM dbSchedules ORDER BY last_name,first_name");
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
		delete_dbSchedules ($scheduleentry->get_area(), $scheduleentry->get_id());
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
	if (delete_dbSchedules($scheduleentry->get_area(), $scheduleentry->get_id())) {
		return insert_dbSchedules($scheduleentry);
	}
	else {
		echo (mysql_error()."unable to update dbSchedules table: ".$scheduleentry->get_area().$scheduleentry->get_id());
		return false;
	}
}

function delete_dbSchedules($area, $id){
	connect();
	$result = mysql_query("DELETE FROM dbSchedules WHERE id ='".$id."' AND area = '".$area."'");
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbSchedules: ".$area.$id);
		return false;
	}
	return true;
}
//  retrieve an array of driver id's
function get_drivers_scheduled($area, $week, $day) {
	connect();
	$result = mysql_query("SELECT * FROM dbSchedules WHERE id='" .
		$day.":".$week."' AND area='".$area."'");
	if ($result_row = mysql_fetch_assoc($result))
		$theDrivers = explode(',',$result_row['drivers']);
	else $theDrivers = array();
	mysql_close();
	return $theDrivers;
}
function get_total_openings($area, $week, $day) {
	$d = get_drivers_scheduled($area, $week, $day);
	return max(2-sizeof($d), 0);
}
function get_total_slots($area, $week, $day) {
	$d = get_drivers_scheduled($area, $week, $day);
	return max(sizeof($d),2);
}

// remove a driver from a schedule
function remove_driver ($area, $week, $day, $driver_id) {
	$se = retrieve_dbSchedules($area, $day.":".$week);
	$remaining = array();
	foreach($se->get_drivers() as $adriver) 
		if ($adriver != $driver_id) 
			$remaining[] = $adriver;
	$se->set_drivers($remaining);
	update_dbSchedules($se);
	return true;
}
// add a driver to a schedule
function add_driver ($area, $week, $day, $driver_id) {
	$se = retrieve_dbSchedules($area, $day.":".$week);
	if ($se && !in_array($driver_id,$se->get_drivers())) {
		$scheduled = $se->get_drivers();
		$scheduled[] = $driver_id;
		$se->set_drivers($scheduled);
		update_dbSchedules($se);
		return true;
	}
	else return false;
}
function get_all_foradriver($area, $driver_id){
	connect();
	$result = mysql_query("SELECT * FROM dbSchedules WHERE area = '".$area."' AND drivers LIKE '%".$driver_id."%' ");
	$theScheduleEntries = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);
		$theScheduleEntries[] = $theScheduleEntry;
	}
	mysql_close();
	return $theScheduleEntries;
}
// update all schedules with the changed availability of a particular driver
function update_volunteers_scheduled ($area, $driver_id, $availability) {
	$days = array('Mon', 'Tue', 'Wed' , 'Thu', 'Fri', 'Sat', 'Sun');
    $weeks = array("1","2","3","4","5");
	$oddeven = array ('odd', 'even');
	// first remove the driver from all schedules where he is scheduled
	$entries = get_all_foradriver ($area, $driver_id);	
	foreach ($entries as $an_entry) {
		$i=strpos($an_entry->get_id(),":");		
		remove_driver($area, $an_entry->get_week(),  $an_entry->get_day(), $driver_id);		   	
	}
	// now add the driver back to all the schedules that he/she has checked
	foreach ($availability as $scheduled_day) {
		$i=strpos($scheduled_day,":");
		if ($i>=0) { // schedule one day for this person
			$day = substr($scheduled_day,0,$i);
			$week_id = substr($scheduled_day,$i+1);
			if ($area == "BFT")
				add_driver ($area, $week_id, $day, $driver_id);
			else 
				add_driver ($area, $week_id, $day, $driver_id);
		}
		else { // schedule multiple days for this person
			if ($area=="BFT")
		    	foreach ($weeks as $week_id)
		    	    add_driver($area, $week_id, $scheduled_day, $driver_id);
		    else 
		    	foreach ($oddeven as $week_id)
		    		add_driver($area, $week_id, $scheduled_day, $driver_id);
		}
	}
    	
}
?>