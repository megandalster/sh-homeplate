<?php
/*
* Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homecheck, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).
*/

/*
* dbDeliveryArea class for Homeplate
* @author Alllen Tucker
* @version February 27, 2012
*/

include_once(dirname(__FILE__).'/../domain/DeliveryArea.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbDeliveryAreas(){
	connect();
	mysql_query("DROP TABLE IF EXISTS dbDeliveryAreas");
	$result = mysql_query("CREATE TABLE dbDeliveryAreas (id TEXT NOT NULL, last_name TEXT, first_name TEXT, address TEXT, city TEXT, state TEXT, zip TEXT, 
							phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), email TEXT, type TEXT, status TEXT, area TEXT, license_no TEXT, license_state TEXT, 
							license_expdate TEXT, accidents TEXT, availability TEXT, schedule TEXT, history TEXT, birthday TEXT,
							start_date TEXT, notes TEXT, password TEXT)");
	mysql_close();
	if(!$result){
			echo (mysql_error()."Error creating database dbDeliveryAreas. \n");
			return false;
	}
	return true;
}

function retrieve_dbDeliveryAreas($id){
	connect();
	$result=mysql_query("SELECT * FROM dbDeliveryAreas WHERE deliveryAreaId  = '".$id."'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theAffiliate = new DeliveryArea($result_row['deliveryAreaId'], $result_row['deliveryAreaName']);
							
							
	mysql_close();
	return $theAffiliate;
}

function getall_dbDeliveryAreas(){
	connect();
	$result = mysql_query("SELECT * FROM dbDeliveryAreas ORDER BY deliveryAreaName");
	$theAffiliates = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theAffiliate = new DeliveryArea($result_row['deliveryAreaId'], $result_row['deliveryAreaName']);
		$theAffiliates[] = $theAffiliate;
	}
	mysql_close();
	return $theAffiliates;
}

function insert_dbDeliveryAreas($deliveryArea){
	if(! $deliveryArea instanceof DeliveryArea){
		return false;
	}
	connect();
	$query = "SELECT * FROM dbDeliveryAreas WHERE deliveryAreaId = '" . $deliveryArea->get_deliveryAreaId() . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		delete_dbDeliveryAreas ($deliveryArea->get_deliveryAreaId());
		connect();
	}
	
	
	$query = "INSERT INTO dbDeliveryAreas (deliveryAreaName) VALUES ('".
				$deliveryArea->get_deliveryAreaName().				
	            "');";
	$result = mysql_query($query);
	if (!$result) {
		echo (mysql_error(). " Unable to insert into dbDeliveryAreas: " . $deliveryArea->get_deliveryAreaId(). "\n");
		mysql_close();
		return false;
	}
	mysql_close();
	return true;
	
}

function update_dbDeliveryAreas($deliveryArea){
	if (! $deliveryArea instanceof DeliveryArea) {
		echo ("Invalid argument for update_dbDeliveryArea function call");
		return false;
	}
	if (delete_dbDeliveryAreas($deliveryArea->get_deliveryAreaId()))
	return insert_dbDeliveryAreas($deliveryArea);
	else {
		echo (mysql_error()."unable to update dbDeliveryAreas table: ".$deliveryArea->get_deliveryAreaId());
		return false;
	}
}

function delete_dbDeliveryAreas($id){
	connect();
	$result = mysql_query("DELETE FROM dbDeliveryAreas WHERE deliveryAreaId =\"".$id."\"");
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbDeliveryAreas: ".$id);
		return false;
	}
	return true;
}


// retrieve only those volunteers that match the criteria given in the arguments
function getonlythose_dbDeliveryAreas($name) {
	connect();
	$query = "SELECT * FROM dbDeliveryAreas WHERE deliveryAreaName like '%".$name. "%'" ;
	
    $query .= " ORDER BY deliveryAreaName";
	$result = mysql_query($query);
	$theVols = array();
		
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new DeliveryArea($result_row['deliveryAreaId'], $result_row['deliveryAreaName']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}


?>