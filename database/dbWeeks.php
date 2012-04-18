<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty. You can redistribute it and/or modify it under the 
 * terms of the GNU General Public License published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
*/

/**
 * @version March 6, 2013
 * @author Oliver Radwan and Allen Tucker and Richardo Hopkins
 */

include_once(dirname(__FILE__).'/../domain/Week.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbWeeks() {
		connect();
		mysql_query("DROP TABLE IF EXISTS dbWeeks");
		$result = mysql_query("CREATE TABLE dbWeeks(id TEXT NOT NULL, routes TEXT)");
        mysql_close();
        if(!$result){
			echo (mysql_error()."Error creating database table dbWeeks. \n");
			return false;
		}
		return true;
}
/*
 * add a week to dbWeeks table: if already there, return false
 */
	function add_week($week){
		if(! $week instanceof Week) die("Error: add_week type mismatch");
		connect();
		$query = "SELECT * FROM dbWeeks WHERE id = '".$week->get_id()."'";
		$result = mysql_query($query);
		//if there's no entry for this id, add it
		if ($result == null || mysql_num_rows($result) == 0) {
   			mysql_query('INSERT INTO dbWeeks VALUES("'.
		             $week->get_id().'","'.
		             implode(',', $week->get_routes()).
		             '");');
            mysql_close();
            return true;
   		}
   		mysql_close();
   		return false;
   	}
/*
 * remove a week from dbWeeks table.  If already there, return false
 */
	function remove_week($id) {
		connect();
   		$query = 'SELECT * FROM dbWeeks WHERE id = "'. $id . '"';
		$result = mysql_query($query);
		if ($result==null || mysql_num_rows($result) == 0) {
		   mysql_close();
		   return false;
		}
		$query='DELETE FROM dbWeeks WHERE id = "'.$id.'"';
		$result=mysql_query($query);
		mysql_close();
		return true;
	}
/*
 * @return a single row from dbWeeks table matching a particular id.
 * if not in table, return false
 */
	function get_week($id){
		connect();
   		$query = 'SELECT * FROM dbWeeks WHERE id = "'.$id.'"';
		$result = mysql_query($query);
		if ($result==null || mysql_num_rows($result) !== 1) {
		   mysql_close();
		   return false;
		}
		mysql_close();
   		return $result;
	}


/*
 * @update a row by deleting it and then adding it again
 */
function update_dbWeeks($r) {
	if (! $r instanceof Week)
		die ("Invalid argument for dbWeeks");
	if (delete_dbWeeks($r))
	   return insert_dbWeeks($r);
	else return false;
}
	

?>
