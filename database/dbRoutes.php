<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty. You can redistribute it and/or modify it under the 
 * terms of the GNU General Public License published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
*/

/**
 * @version Feb 23, 2013
 * @author Oliver Radwan and Allen Tucker and Richardo Hopkins
 */

include_once(dirname(__FILE__).'/../domain/Route.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbRoutes() {
		connect();
		mysql_query("DROP TABLE IF EXISTS dbRoutes");
		$result = mysql_query("CREATE TABLE dbRoutes(id TEXT NOT NULL, drivers TEXT, teamcaptain_id TEXT, " .
				"stops TEXT, notes TEXT)");
        mysql_close();
        if(!$result){
			echo (mysql_error()."Error creating database table dbRoutes. \n");
			return false;
		}
		return true;
}
/*
 * add a route to dbRoutes table: if already there, return false
 */
	function add_route($route){
		if(! $route instanceof Route) die("Error: add_route type mismatch");
		connect();
		$query = "SELECT * FROM dbRoutes WHERE id = '".$route->get_id()."'";
		$result = mysql_query($query);
		//if there's no entry for this id, add it
		if ($result == null || mysql_num_rows($result) == 0) {
   			mysql_query('INSERT INTO dbRoutes VALUES("'.
		             $route->get_id().'","'.
		             implode(',', $route->get_drivers()).'","'.
		             $route->get_teamcaptain_id().'","'.
		             implode(',', $route->get_stops()).'","'.
		             $route->get_notes().
                     '");');
            mysql_close();
            return true;
   		}
   		mysql_close();
   		return false;
   	}
/*
 * remove a route from dbRoutes table.  If already there, return false
 */
	function remove_route($id) {
		connect();
   		$query = 'SELECT * FROM dbRoutes WHERE id = "'. $id . '"';
		$result = mysql_query($query);
		if ($result==null || mysql_num_rows($result) == 0) {
		   mysql_close();
		   return false;
		}
		$query='DELETE FROM dbRoutes WHERE id = "'.$id.'"';
		$result=mysql_query($query);
		mysql_close();
		return true;
	}
/*
 * @return a single row from dbRoutes table matching a particular id.
 * if not in table, return false
 */
	function get_route($id){
		connect();
   		$query = 'SELECT * FROM dbRoutes WHERE id = "'.$id.'"';
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
function update_dbRoutes($r) {
	if (! $r instanceof Route)
		die ("Invalid argument for dbRoutes");
	if (delete_dbRoutes($r))
	   return insert_dbRoutes($r);
	else return false;
}
	

?>
