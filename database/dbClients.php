<?php
/*
* Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homecheck, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).
*/

/*
* dbClients class for Homeplate
* @author Hartley Brody
* @version March 1, 2012
*/

include_once(dirname(__FILE__).'/../domain/Client.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbClients(){
	connect();
	mysql_query("DROP TABLE IF EXISTS dbClients");
	$result = mysql_query("CREATE TABLE dbClients (id TEXT NOT NULL, chain_name TEXT, area TEXT, type TEXT, address TEXT, city TEXT, state TEXT,
                            zip TEXT, geocoordinates TEXT, phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), days TEXT, feed_america TEXT, 
                            weight_type TEXT, notes TEXT)");
	mysql_close();
	if(!$result){
			echo (mysql_error()."Error creating database table dbClients. <br>");
			return false;
	}
	return true;
}

function retrieve_dbClients($id){
	connect();
	$result=mysql_query("SELECT * FROM dbClients WHERE id  = '".$id."'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theClient = new Client($result_row['id'], $result_row['chain_name'], $result_row['area'], $result_row['type'], $result_row['address'],
                            $result_row['city'], $result_row['state'], $result_row['zip'], $result_row['geocoordinates'], $result_row['phone1'], 
                            $result_row['phone2'], $result_row['days'], $result_row['feed_america'], $result_row['weight_type'], $result_row['notes']);
	mysql_close();
	return $theClient;
}


function getall_dbClients(){
	connect();
	$result = mysql_query("SELECT * FROM dbClients ORDER BY id");
	$theClients = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theClient = new Client($result_row['id'], $result_row['chain_name'], $result_row['area'], $result_row['type'], $result_row['address'],
                            $result_row['city'], $result_row['state'], $result_row['zip'], $result_row['geocoordinates'], $result_row['phone1'], $result_row['phone2'],
							$result_row['days'], $result_row['feed_america'], $result_row['weight_type'], $result_row['notes']);
		$theClients[] = $theClient;
	}
	mysql_close();
	return $theClients;
}

function getall_clients($area, $type, $status, $name, $availability) {
	connect();
    $query = "SELECT * FROM dbClients WHERE area = '". $area . "' ";
            if($type)           $query .= "AND type = '". $type . "' ";
            if($status)         $query .= "AND feed_america = '" . $status . "' ";
            if($name)           $query .= "AND id LIKE '%" . $name ."%' ";
            if($availability)  { 
            	$query .= "AND ";
            	foreach ($availability as $day)
                	$query .= "days LIKE '%".$day."%' OR ";
            	$query = substr($query, 0, strlen( $query ) - 3);
            }
            $query .= "ORDER BY id";
    //print $query;
    $result = mysql_query ($query);
    $theClients = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $theClient = new Client($result_row['id'], $result_row['chain_name'], $result_row['area'], $result_row['type'], $result_row['address'],
                            $result_row['city'], $result_row['state'], $result_row['zip'], $result_row['geocoordinates'], $result_row['phone1'], $result_row['phone2'],
							$result_row['days'], $result_row['feed_america'], $result_row['weight_type'], $result_row['notes']);
		$theClients[] = $theClient;
    }
	mysql_close();
    return $theClients; 
}

function insert_dbClients($client){
	if(! $client instanceof Client){
		return false;
	}
	connect();
	$query = "SELECT * FROM dbClients WHERE id = '" . $client->get_id() . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		delete_dbClients ($client->get_id());
		connect();
	}
	$query = "INSERT INTO dbClients VALUES ('".
				$client->get_id()."','" .
				$client->get_chain_name()."','".
				$client->get_area()."','".
				$client->get_type()."','".
				$client->get_address()."','".
				$client->get_city()."','".
				$client->get_state()."','".
				$client->get_zip()."','".
                implode(',',$client->get_geo())."','".
				$client->get_phone1()."','".
				$client->get_phone2()."','".
				implode(',',$client->get_days())."','".
				$client->is_feed_america()."','".
				$client->get_weight_type()."','".
				$client->get_notes().
	            "');";
	$result = mysql_query($query);
	if (!$result) {
		echo (mysql_error(). " Unable to insert into dbClients: " . $client->get_id(). "\n");
		mysql_close();
		return false;
	}
	mysql_close();
	return true;
	
}

function update_dbClients($client){
	if (! $client instanceof Client) {
		echo ("Invalid argument for update_dbClients function call");
		return false;
	}
	if (delete_dbClients($client->get_id()))
	return insert_dbClients($client);
	else {
		echo (mysql_error()."unable to update dbClients table: ".$client->get_id());
		return false;
	}
}

function delete_dbClients($id){
	connect();
	$result = mysql_query("DELETE FROM dbClients WHERE id =\"".$id."\"");
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbClients: ".$id);
		return false;
	}
	return true;
}
?>