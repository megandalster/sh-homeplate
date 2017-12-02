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

function retrieve_dbClients($id){
	connect();
	$result=mysql_query("SELECT * FROM dbClients WHERE id  = '".$id."'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theClient = new Client($result_row['id'], $result_row['chain_name'], $result_row['area'], $result_row['type'], $result_row['address'],
                            $result_row['city'], $result_row['state'], $result_row['zip'], $result_row['county'], $result_row['phone1'], $result_row['address2'],
                            $result_row['city2'], $result_row['state2'], $result_row['zip2'], $result_row['county2'], 
                            $result_row['phone2'], $result_row['days'], $result_row['lcfb'], $result_row['chartrkr'], $result_row['weight_type'], $result_row['notes'],  $result_row['email'],
							$result_row['email2'], $result_row['ContactName'], $result_row['ContactName2'], $result_row['deliveryAreaId']);
	mysql_close();
	return $theClient;
}


function getall_dbClients(){
	connect();
	$result = mysql_query("SELECT * FROM dbClients ORDER BY id");
	$theClients = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theClient = new Client($result_row['id'], $result_row['chain_name'], $result_row['area'], $result_row['type'], $result_row['address'],
                            $result_row['city'], $result_row['state'], $result_row['zip'], $result_row['county'], $result_row['phone1'], $result_row['address2'],
                            $result_row['city2'], $result_row['state2'], $result_row['zip2'], $result_row['county2'], 
                            $result_row['phone2'], $result_row['days'], $result_row['lcfb'], $result_row['chartrkr'], $result_row['weight_type'], $result_row['notes'],  $result_row['email'],
							$result_row['email2'], $result_row['ContactName'], $result_row['ContactName2'], $result_row['deliveryAreaId']);
		$theClients[] = $theClient;
	}
	mysql_close();
	return $theClients;
}

function getall_clients($area, $type, $lcfb, $name, $availability, $deliveryAreaId, $county) {
	connect();
    $query = "SELECT * FROM dbClients WHERE area like '%". $area . "%' ";
            if($type)           $query .= "AND type = '". $type . "' ";
            if($lcfb)         $query .= "AND lcfb = '" . $lcfb . "' ";
            if($name)           $query .= "AND id LIKE '%" . $name ."%' ";
			if($deliveryAreaId)	$query .= "AND deliveryAreaId=" . $deliveryAreaId . " ";
			if($county)	$query .= "AND county='" . $county . "' ";
			
            if($availability && count($availability) > 0)  { 
            	$query .= "AND (";
            	foreach ($availability as $day)
                	$query .= "days LIKE '%".$day."%' OR ";
            	$query = substr($query, 0, strlen( $query ) - 4).") ";
            }
            $query .= "ORDER BY id";
    $result = mysql_query ($query);
    $theClients = array();
    while ($result_row = mysql_fetch_assoc($result)) {
        $theClient = new Client($result_row['id'], $result_row['chain_name'], $result_row['area'], $result_row['type'], $result_row['address'],
                            $result_row['city'], $result_row['state'], $result_row['zip'], $result_row['county'], $result_row['phone1'], $result_row['address2'],
                            $result_row['city2'], $result_row['state2'], $result_row['zip2'], $result_row['county2'], $result_row['phone2'], 
                            $result_row['days'], $result_row['lcfb'], $result_row['chartrkr'], $result_row['weight_type'], $result_row['notes'],  $result_row['email'],
							$result_row['email2'], $result_row['ContactName'], $result_row['ContactName2'], $result_row['deliveryAreaId']);
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
                $client->get_county()."','".
				$client->get_phone1()."','".
				$client->get_address2()."','".
				$client->get_city2()."','".
				$client->get_state2()."','".
				$client->get_zip2()."','".
                $client->get_county2()."','".
				$client->get_phone2()."','".
				implode(',',$client->get_days())."','".
				$client->get_lcfb()."','".
				$client->get_chartrkr()."','".
				$client->get_weight_type()."','".
				$client->get_notes()."','".
				$client->get_email()."','".
				$client->get_email2()."','".
				$client->get_ContactName() ."','".
				$client->get_ContactName2() ."','".
				$client->get_deliveryAreaId() . 
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


function getall_dbClientsForArea($deliveryAreaId){
	$theQuery = "SELECT * FROM dbClients WHERE deliveryAreaId=" . $deliveryAreaId . " ORDER BY id";
	
	connect();
	$result = mysql_query($theQuery);
	$theClients = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theClient = new Client($result_row['id'], $result_row['chain_name'], $result_row['area'], $result_row['type'], $result_row['address'],
                            $result_row['city'], $result_row['state'], $result_row['zip'], $result_row['county'], $result_row['phone1'], $result_row['address2'],
                            $result_row['city2'], $result_row['state2'], $result_row['zip2'], $result_row['county2'], 
                            $result_row['phone2'], $result_row['days'], $result_row['lcfb'], $result_row['chartrkr'], $result_row['weight_type'], $result_row['notes'],  $result_row['email'],
							$result_row['email2'], $result_row['ContactName'], $result_row['ContactName2'], $result_row['deliveryAreaId']);
		$theClients[] = $theClient;
	}
	mysql_close();
	return $theClients;
}

?>