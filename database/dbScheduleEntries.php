<?php
/*
* Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homecheck, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).
*/

/*
* dbScheduleEntries class for Homeplate
* @author Hartley Brody
* @version March 7, 2012
*/

include_once(dirname(__FILE__).'/../domain/ScheduleEntry.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbScheduleEntries(){
	connect();
	mysql_query("DROP TABLE IF EXISTS dbScheduleEntries");
	$result = mysql_query("CREATE TABLE dbScheduleEntries (id TEXT NOT NULL, area TEXT, drivers TEXT, notes TEXT)");
	mysql_close();
	if(!$result){
			echo (mysql_error()."Error creating database table dbScheduleEntries. \n");
			return false;
	}
	return true;
}

function retrieve_dbScheduleEntries($id){
	connect();
	$result=mysql_query("SELECT * FROM dbScheduleEntries WHERE id  = '".$id."'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);

	mysql_close();
	return $theScheduleEntry;
}


function getall_dbScheduleEntries(){
	connect();
	$result = mysql_query("SELECT * FROM dbScheduleEntries ORDER BY last_name");
	$theScheduleEntries = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theScheduleEntry = new ScheduleEntry($result_row['area'], $result_row['id'], $result_row['drivers'], $result_row['notes']);
		$theScheduleEntries[] = $theScheduleEntry;
	}
	mysql_close();
	return $theScheduleEntries;
}

function insert_dbScheduleEntries($scheduleentry){
	if(! $scheduleentry instanceof ScheduleEntry){
		return false;
	}
	connect();
	$query = "SELECT * FROM dbScheduleEntries WHERE id = '" . $scheduleentry->get_id() . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		delete_dbScheduleEntries ($scheduleentry->get_id());
		connect();
	}
	$query = "INSERT INTO dbScheduleEntries VALUES ('".
				$scheduleentry->get_id()."','" .
				$scheduleentry->get_area()."','" .
				implode(',',$scheduleentry->get_drivers())."','".
				$scheduleentry->get_notes().
	            "');";
	$result = mysql_query($query);
	if (!$result) {
		echo (mysql_error(). " Unable to insert into dbScheduleEntries: " . $scheduleentry->get_id(). "\n");
		mysql_close();
		return false;
	}
	mysql_close();
	return true;
	
}

function update_dbScheduleEntries($scheduleentry){
	if (! $scheduleentry instanceof ScheduleEntry) {
		echo ("Invalid argument for update_dbScheduleEntries function call");
		return false;
	}
	if (delete_dbScheduleEntries($scheduleentry->get_id()))
		return insert_dbScheduleEntries($scheduleentry);
	else {
		echo (mysql_error()."unable to update dbScheduleEntries table: ".$scheduleentry->get_id());
		return false;
	}
}

function delete_dbScheduleEntries($id){
	connect();
	$result = mysql_query("DELETE FROM dbScheduleEntries WHERE id =\"".$id."\"");
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbScheduleEntries: ".$id);
		return false;
	}
	return true;
}
?>