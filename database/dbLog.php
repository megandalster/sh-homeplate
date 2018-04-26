<?php
/*
 * Copyright 2008 by Oliver Radwan, Maxwell Palmer, Nolan McNair,
 * Taylor Talmage, and Allen Tucker.  This program is part of RMH Homebase.
 * and RMH Homeroom, which is free software.  It comes with absolutely no 
 * warranty. You can redistribute it and/or modify it under the terms of 
 * the GNU General Public License as published by the Free Software 
 * Foundation (see <http://www.gnu.org/licenses/ for more information).
*/

/**
 * Functions to create, update, and retrieve information from the
 * dbLog table in the database.  dbLog is not linked to an object
 * class.
 * @version May 1, 2008
 * @author Maxwell Palmer
 */

include_once(dirname(__FILE__).'/dbinfo.php');

 /**
  * Sets up a new dbLog table by dropping and recreating
  * id - auto increment
  * time - timestamp time()
  * message - text
  */
function create_dbLog(){
	$con=connect();
	mysqli_query($con,"DROP TABLE IF EXISTS dbLog");
	//NOTE: primary key set to id.  id is text in the form: yy-mm-dd
	$result=mysqli_query($con,"CREATE TABLE dbLog (id INT(3) NOT NULL AUTO_INCREMENT,time TEXT, message TEXT, PRIMARY KEY(id))");
	
	if(!$result) {
		echo mysqli_error($con);
		mysqli_close($con);
		return false;
	}
	mysqli_close($con);
	return true;
}

/**
 * adds a new log entry, using the current time for the timestamp
 */
function add_log_entry($message){
	$time=time();
	$con=connect();
	$query = "INSERT INTO dbLog (time, message) VALUES (\"".$time."\",\"".$message."\")";
	$result=mysqli_query($con,$query);
	if(!$result){
		echo mysqli_error($con);
	}
	mysqli_close($con);
}

/**
 * deletes a log entry
 */
function delete_log_entry($id){
	$con=connect();
	$query="DELETE FROM dbLog WHERE id=\"".$id."\"";
	$result=mysqli_query($con,$query);
	if(!$result)
		echo mysqli_error($con);
   mysqli_close($con);
}

/**
 * deletes log entries with ids specified in array $ids
 * @param $ids: array of log ids
 */
function delete_log_entries($ids) {
	$con=connect();
	for($i=0;$i<count($ids);++$i) {
		$query="DELETE FROM dbLog WHERE id=\"".$ids[$i]."\"";
		$result=mysqli_query($con,$query);
		if(!$result)
			echo mysqli_error($con);
	}
	mysqli_close($con);
}
/**
 * returns all entries in the log, sorted by timestamp
 * @return array of id, time, and text
 */
function get_full_log(){
	$con=connect();
	$query="SELECT * FROM dbLog ORDER BY time DESC";
	$result=mysqli_query($con,$query);
	mysqli_close($con);
	if(!$result) {
		die("error getting log");
	}
	else{
		for($i=0;$i<mysqli_num_rows($result);++$i) {
			$result_row=mysqli_fetch_assoc($result);
			if($result_row) {
				$log[]=array($result_row['id'],date("n/j/y g:ia",$result_row['time']),$result_row['message']);
			}
		}
	}
	return $log;
}

/**
 * returns the last $num log entries
 * @return array of log entries
 */
function get_last_log_entries($num) {
	$l=get_full_log();
	$c=count($l);
	if($num>$c)
		$num=$c;
	for($i=0;$i<$num;++$i) {
		$log[]=$l[$i];
	}
	return $log;
}

?>
