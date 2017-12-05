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
	$con=connect();
	mysqli_query($con,"DROP TABLE IF EXISTS dbDeliveryAreas");
	$result = mysqli_query($con,"CREATE TABLE dbDeliveryAreas (id TEXT NOT NULL, last_name TEXT, first_name TEXT, address TEXT, city TEXT, state TEXT, zip TEXT, 
							phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), email TEXT, type TEXT, status TEXT, area TEXT, license_no TEXT, license_state TEXT, 
							license_expdate TEXT, accidents TEXT, availability TEXT, schedule TEXT, history TEXT, birthday TEXT,
							start_date TEXT, notes TEXT, password TEXT)");
	if(!$result){
			echo (mysqli_error($con)."Error creating database dbDeliveryAreas. \n");
			mysqli_close($con);
			return false;
	}
	mysqli_close($con);
	return true;
}

function retrieve_dbDeliveryAreas($id){
	$con=connect();
	$result=mysqli_query($con,"SELECT * FROM dbDeliveryAreas WHERE deliveryAreaId  = '".$id."'");
	if(mysqli_num_rows($result) !== 1){
			mysqli_close($con);
			return false;
	}
	$result_row = mysqli_fetch_assoc($result);
	$theAffiliate = new DeliveryArea($result_row['deliveryAreaId'], $result_row['deliveryAreaName']);
							
							
	mysqli_close($con);
	return $theAffiliate;
}

function getall_dbDeliveryAreas(){
	$con=connect();
	$result = mysqli_query($con,"SELECT * FROM dbDeliveryAreas ORDER BY deliveryAreaName");
	$theAffiliates = array();
	while($result_row = mysqli_fetch_assoc($result)){
		$theAffiliate = new DeliveryArea($result_row['deliveryAreaId'], $result_row['deliveryAreaName']);
		$theAffiliates[] = $theAffiliate;
	}
	mysqli_close($con);
	return $theAffiliates;
}

function insert_dbDeliveryAreas($deliveryArea){
	if(! $deliveryArea instanceof DeliveryArea){
		return false;
	}
	$con=connect();
	$query = "SELECT * FROM dbDeliveryAreas WHERE deliveryAreaId = '" . $deliveryArea->get_deliveryAreaId() . "'";
	$result = mysqli_query($con,$query);
	if (mysqli_num_rows($result) != 0) {
		delete_dbDeliveryAreas ($deliveryArea->get_deliveryAreaId());
		$con=connect();
	}
	
	
	$query = "INSERT INTO dbDeliveryAreas (deliveryAreaName) VALUES ('".
				$deliveryArea->get_deliveryAreaName().				
	            "');";
	$result = mysqli_query($con,$query);
	if (!$result) {
		echo (mysqli_error($con). " Unable to insert into dbDeliveryAreas: " . $deliveryArea->get_deliveryAreaId(). "\n");
		mysqli_close($con);
		return false;
	}
	mysqli_close($con);
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
		$con=connect();
		echo (mysqli_error($con)."unable to update dbDeliveryAreas table: ".$deliveryArea->get_deliveryAreaId());
		mysqli_close($con);
		return false;
	}
}

function delete_dbDeliveryAreas($id){
	$con=connect();
	$result = mysqli_query($con,"DELETE FROM dbDeliveryAreas WHERE deliveryAreaId =\"".$id."\"");
	mysqli_close($con);
	if (!$result) {
		echo (mysqli_error($con)." unable to delete from dbDeliveryAreas: ".$id);
		return false;
	}
	return true;
}


// retrieve only those volunteers that match the criteria given in the arguments
function getonlythose_dbDeliveryAreas($name) {
	$con=connect();
	$query = "SELECT * FROM dbDeliveryAreas WHERE deliveryAreaName like '%".$name. "%'" ;
	
    $query .= " ORDER BY deliveryAreaName";
	$result = mysqli_query($con,$query);
	$theVols = array();
		
	while($result_row = mysqli_fetch_assoc($result)){
		$theVol = new DeliveryArea($result_row['deliveryAreaId'], $result_row['deliveryAreaName']);
		$theVols[] = $theVol;
	}
	mysqli_close($con);
	return $theVols;
}


?>