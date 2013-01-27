<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * dbMonths module for SH Homeplate
 * @author Nicholas Wetzel and Alex Lucyk
 * @version February 29, 2012
 */

include_once(dirname(__FILE__).'/../domain/Month.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbMonths() {
    connect();
    mysql_query("DROP TABLE IF EXISTS dbMonths");
    $result = mysql_query("CREATE TABLE dbMonths (id TEXT NOT NULL, status TEXT NOT NULL, 
    												routes TEXT NOT NULL, notes TEXT)");
    mysql_close();
    if (!$result) {
        echo mysql_error() . "Error creating dbMonths table. <br>";
        return false;
    }
    return true;
}

function insert_dbMonths ($month){
    if (! $month instanceof Month) {
        return false;
    }
    connect();

	$query = "SELECT * FROM dbMonths WHERE id = '" . $month->get_id() . "'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) != 0) {
        delete_dbMonths ($month->get_id());
        connect();
    }

    $query = "INSERT INTO dbMonths VALUES ('".
                $month->get_id()."','" . 
                $month->get_status()."','".
                implode(',',$month->get_routes())."','".
                $month->get_notes()."');";
    $result = mysql_query($query);
    if (!$result) {
        echo (mysql_error(). " unable to insert into dbMonths: " . $month->get_id(). "\n");
        mysql_close();
        return false;
    }
    mysql_close();
    return true;
}
                
function retrieve_dbMonths ($id) {
	connect();
    $query = "SELECT * FROM dbMonths WHERE id = '".$id."'";
    $result = mysql_query ($query);
    if (mysql_num_rows($result) !== 1){
    	mysql_close();
        return false;
    }
    $result_row = mysql_fetch_assoc($result);
    $theMonth = new Month($result_row['id'], $result_row['status'], $result_row['routes'], $result_row['notes']);
    return $theMonth;   
}
function getall_Months () {
    connect();
    $query = "SELECT * FROM dbMonths ORDER BY id";
    $result = mysql_query ($query);
    $theMonths = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $theMonth = new Month($resutl_row['id'], $result_row['status'], $result_row['routes'], $result_row['notes']);
        $theMonths[] = $theMonth;
    }
    return $theMonths; 
}

function update_dbMonths ($month) {
if (! $month instanceof Month) {
		echo ("Invalid argument for update_dbMonths function call");
		return false;
	}
	if (delete_dbMonths($month->get_id()))
	   return insert_dbMonths($month);
	else {
	   echo (mysql_error()."unable to update dbMonths table: ".$month->get_id());
	   return false;
	}
}

function delete_dbMonths($id) {
	connect();
    $query="DELETE FROM dbMonths WHERE id=\"".$id."\"";
	$result=mysql_query($query);
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbMonths: ".$id);
		return false;
	}
    return true;
}