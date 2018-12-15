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
* It is created with a fixed number of rows, 5x7=35 for the Hilton Head and Beaufort areas (5-week monthly schedule)
* and 2x7=14 rows for the Bluffton area (alternate-week schedule),
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
	$con=connect();
	mysqli_query($con,"DROP TABLE IF EXISTS dbSchedules");
	$result = mysqli_query($con,"CREATE TABLE dbSchedules (id TEXT NOT NULL, area TEXT, drivers TEXT, notes TEXT)");
	if(!$result){
			echo (mysqli_error($con)."Error creating database table dbSchedules. \n");
			mysqli_close($con);
			return false;
	}
	mysqli_close($con);
	// populate the table 
	$areas = array("HHI","BFT");
	$weekly_groups = array("odd","even");
	$monthly_groups = array("1","2", "3", "4", "5");
	$days = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
	foreach ($areas as $area)
	  foreach ($monthly_groups as $monthly_group)
		foreach ($days as $day) {   // day of the week
			$se = new ScheduleEntry($area, $day.":".$monthly_group, "", "");
			insert_dbSchedules($se);
		}
	foreach ($weekly_groups as $week)   // week of the year
			foreach ($days as $day) {   // day of the week
				$se = new ScheduleEntry("SUN", $day.":".$week, "", "");
				insert_dbSchedules($se);
			}
	return true;
}

function retrieve_dbSchedules($area, $id){
	$con=connect();
	$result=mysqli_query($con,"SELECT * FROM dbSchedules WHERE id  = '".$id."' AND area = '".$area."'");
	if(mysqli_num_rows($result) !== 1){
			mysqli_close($con);
			return false;
	}
	$result_row = mysqli_fetch_assoc($result);
	$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);

	mysqli_close($con);
	return $theScheduleEntry;
}


function getall_dbSchedules(){
	$con=connect();
	$result = mysqli_query($con,"SELECT * FROM dbSchedules ORDER BY last_name,first_name");
	$theScheduleEntries = array();
	while($result_row = mysqli_fetch_assoc($result)){
		$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);
		$theScheduleEntries[] = $theScheduleEntry;
	}
	mysqli_close($con);
	return $theScheduleEntries;
}
// insert_dbSchedules works as an update.  That is, if the entry is already there, it is replaced
// in the table.  Otherwise, it is added to the table.  The unique key for this table is $area . $id.
function insert_dbSchedules($scheduleentry){
	if(! $scheduleentry instanceof ScheduleEntry){
		return false;
	}
	$con=connect();
	$query = "SELECT * FROM dbSchedules WHERE id = '" . $scheduleentry->get_id() ."' AND area = '".$scheduleentry->get_area()."'";
	$result = mysqli_query($con,$query);
	if (mysqli_num_rows($result) != 0) {
		delete_dbSchedules ($scheduleentry->get_area(), $scheduleentry->get_id());
		$con=connect();
	}
	$query = "INSERT INTO dbSchedules VALUES ('".
				$scheduleentry->get_id()."','" .
				$scheduleentry->get_area()."','" .
				implode(',',$scheduleentry->get_drivers())."','".
				$scheduleentry->get_notes().
	            "');";
	$result = mysqli_query($con,$query);
	if (!$result) {
		echo (mysqli_error($con). " Unable to insert into dbSchedules: " . $scheduleentry->get_area().$scheduleentry->get_id(). "\n");
		mysqli_close($con);
		return false;
	}
	mysqli_close($con);
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
		$con=connect();
		echo (mysqli_error($con)."unable to update dbSchedules table: ".$scheduleentry->get_area().$scheduleentry->get_id());
		mysqli_close($con);
		return false;
	}
}

function delete_dbSchedules($area, $id){
	$con=connect();
	$result = mysqli_query($con,"DELETE FROM dbSchedules WHERE id ='".$id."' AND area = '".$area."'");
	mysqli_close($con);
	if (!$result) {
		echo (mysqli_error($con)." unable to delete from dbSchedules: ".$area.$id);
		return false;
	}
	return true;
}
//  retrieve an array of driver id's
function get_drivers_scheduled($area, $week, $day) {
	$con=connect();
	$result = mysqli_query($con,"SELECT * FROM dbSchedules WHERE id='" .
		$day.":".$week."' AND area='".$area."'");
	if ($result_row = mysqli_fetch_assoc($result))
		$theDrivers = explode(',',$result_row['drivers']);
	else $theDrivers = array();
	mysqli_close($con);
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
	$con=connect();
	$result = mysqli_query($con,"SELECT * FROM dbSchedules WHERE area = '".$area."' AND drivers LIKE '%".$driver_id."%' ");
	$theScheduleEntries = array();
	while($result_row = mysqli_fetch_assoc($result)){
		$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);
		$theScheduleEntries[] = $theScheduleEntry;
	}
	mysqli_close($con);
	return $theScheduleEntries;
}
// update all schedules with the changed availability of a particular driver
function update_volunteers_scheduled ($area, $driver_id, $availability,$deleteonly) {
	$days = array('Mon', 'Tue', 'Wed' , 'Thu', 'Fri', 'Sat', 'Sun');
    $weeks = array("1","2","3","4","5");
	$oddeven = array ('odd', 'even');
	// first remove the driver from all schedules where he is scheduled
	$entries = get_all_foradriver ($area, $driver_id);	
	foreach ($entries as $an_entry) {
		$i=strpos($an_entry->get_id(),":");		
		remove_driver($area, $an_entry->get_week(),  $an_entry->get_day(), $driver_id);		   	
	}
	// put the driver back into all the schedules, but only if not a deletion
	if ($deleteonly!="deleteonly")
	  foreach ($availability as $scheduled_day) {
		$i=strpos($scheduled_day,":");
		if ($i>=0) { // schedule one day for this person
			$day = substr($scheduled_day,0,$i);
			$week_id = substr($scheduled_day,$i+1);
			if ($area == "BFT" || $area == "HHI")
				add_driver ($area, $week_id, $day, $driver_id);
			else 
				add_driver ($area, $week_id, $day, $driver_id);
		}
		else { // schedule multiple days for this person
		    if ($area=="BFT" || $area == "HHI")
		    	foreach ($weeks as $week_id)
		    	    add_driver($area, $week_id, $scheduled_day, $driver_id);
		    else 
		    	foreach ($oddeven as $week_id)
		    		add_driver($area, $week_id, $scheduled_day, $driver_id);
		}
	  }
    	
}
?>