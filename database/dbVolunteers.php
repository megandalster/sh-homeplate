<?php
/*
* Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homecheck, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).
*/

/*
* dbVolunteers class for Homeplate
* @author Alllen Tucker
* @version February 27, 2012
*/

include_once(dirname(__FILE__).'/../domain/Volunteer.php');
include_once(dirname(__FILE__).'/dbinfo.php');


function getall_dbAffiliateVolunteers($affiliateId){
	connect();
	$theQuery = "SELECT * FROM dbVolunteers WHERE affiliateId=" . $affiliateId . " ORDER BY last_name";
	
	$result = mysql_query($theQuery);
	$theVols = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'],
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],
							$result_row['ShirtSize'], $result_row['affiliateId']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}

function retrieve_dbVolunteers($id){
	connect();
	$result=mysql_query("SELECT * FROM dbVolunteers WHERE id  = '".$id."'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'],
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],$result_row['ShirtSize'], $result_row['affiliateId']);
							
							
	mysql_close();
	return $theVol;
}

function retrieve_dbVolunteersByName($first_name, $last_name){
	connect();
	$result=mysql_query("SELECT * FROM dbVolunteers WHERE first_name='" . $first_name . "' AND last_name='" . $last_name . "'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	
	
	
	$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'],
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],
							$result_row['ShirtSize'], $result_row['affiliateId']);
							
							
	mysql_close();
	return $theVol;
}



function getall_dbVolunteers(){
	connect();
	$result = mysql_query("SELECT * FROM dbVolunteers ORDER BY last_name");
	$theVols = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'],
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],
							$result_row['ShirtSize'], $result_row['affiliateId']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}



// retrieve only those volunteers that match the criteria given in the arguments
function getonlythose_dbVolunteers($area, $types, $status, $name, $availability, $affiliateId) {
	connect();
	$query = "SELECT * FROM dbVolunteers WHERE area like '%".$area. "%'" .  
	         " AND status LIKE '%".$status."%'" . 
			 " AND (first_name LIKE '%".$name."%' OR last_name LIKE '%".$name."%')" ;
	if ($types[0]!="")  { 
            $query .= "AND (";
            foreach ($types as $type)
                $query .= "type LIKE '%".$type."%' OR ";
            $query = substr($query, 0, strlen( $query ) - 4).") ";
	}
	if ($availability[0]!="")  { 
            $query .= "AND (";
            foreach ($availability as $day)
                $query .= "availability LIKE '%".$day."%' OR ";
            $query = substr($query, 0, strlen( $query ) - 4).") ";
	}
	
	if($affiliateId != ''){
		$query .= " AND affiliateId=" . $affiliateId;
	}
	
    $query .= " ORDER BY last_name";
	$result = mysql_query($query);
	$theVols = array();
		
		
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'], 
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],
							$result_row['ShirtSize'], $result_row['affiliateId']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}

function get_team_captains ($area, $day) {
	connect();
	$result=mysql_query("SELECT * FROM dbVolunteers WHERE type LIKE '%teamcaptain%' AND availability LIKE '%".$day."%' AND area  = '".$area."'");
	
	$theVols = array();	
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'],
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],
							$result_row['ShirtSize'], $result_row['affiliateId']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}

function get_all_crew($area){
	
	connect();
	$sql = "SELECT * FROM dbVolunteers WHERE  area  = '".$area."' AND (type LIKE '%driver%' OR type LIKE '%helper%' OR type LIKE '%sub%') ORDER BY last_name, first_name";
	
	$result=mysql_query($sql);
	
	$theVols = array();	
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'],
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],
							$result_row['ShirtSize'], $result_row['affiliateId']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}

function  getall_drivers_available($area, $day) {
	connect();
	$result=mysql_query("SELECT * FROM dbVolunteers WHERE status='active' AND area  = '".$area."' AND availability LIKE '%".$day."%' ORDER BY last_name, first_name");
	
	$theVols = array();	
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'],
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password'],
							$result_row['TripCount'], $result_row['LastTripDate'], $result_row['volunteerTrainingDate'],$result_row['driverTrainingDate'],
							$result_row['ShirtSize'], $result_row['affiliateId']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}


function insert_dbVolunteers($volunteer){
	if(! $volunteer instanceof Volunteer){
		return false;
	}
	connect();
	$query = "SELECT * FROM dbVolunteers WHERE id = '" . $volunteer->get_id() . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		delete_dbVolunteers ($volunteer->get_id());
		connect();
	}
	
	$lastTripDate = $volunteer->get_lastTripDate();
	
	if($lastTripDate != ''){
		$phpdate = strtotime( $lastTripDate );
		$mysqldate = date( 'Y-m-d H:i:s', $phpdate );
	$lastTripDate = "'" . $mysqldate   . "'";
	}
	else{
		$lastTripDate = "NULL";
	}
	
	$tripCount = "NULL";
	
	if($volunteer->get_tripCount() != ""){
		$tripCount = $volunteer->get_tripCount();
	}
	
	$affiliateId = "NULL";
	if($volunteer->get_affiliateId() != ""){
		$affiliateId =$volunteer->get_affiliateId();
	}
	
	$query = "INSERT INTO dbVolunteers VALUES ('".
				$volunteer->get_id()."','" .
				mysql_real_escape_string($volunteer->get_last_name())."','".
				mysql_real_escape_string($volunteer->get_first_name())."','".
				mysql_real_escape_string($volunteer->get_address())."','".
				mysql_real_escape_string($volunteer->get_city())."','".
				$volunteer->get_state()."','".
				$volunteer->get_zip()."','".
				$volunteer->get_phone1()."','".
				$volunteer->get_phone2()."','".
				$volunteer->get_email()."','".
				implode(',',$volunteer->get_type())."','".
				$volunteer->get_status()."','".
				$volunteer->get_area()."','".
				$volunteer->get_license_no()."','".
				$volunteer->get_license_state()."','".
				$volunteer->get_license_expdate()."','".
				implode(',',$volunteer->get_accidents())."','".
				implode(',',$volunteer->get_availability())."','".
				implode(',',$volunteer->get_schedule())."','".
				implode(',',$volunteer->get_history())."','".
				$volunteer->get_birthday()."','".
				$volunteer->get_start_date()."','".
				mysql_real_escape_string($volunteer->get_notes())."','".
				$volunteer->get_password()."',".
				$tripCount .",".
				$lastTripDate.",'".
				$volunteer->get_volunteerTrainingDate()."','".
				$volunteer->get_driverTrainingDate()."','".
				$volunteer->get_shirtSize()."',".
				$affiliateId .
	            ");";
				
				//echo $query . "<br />";
				
	$result = mysql_query($query);
	if (!$result) {
		echo (mysql_error(). " Unable to insert into dbVolunteers: " . $volunteer->get_id(). "\n");
		mysql_close();
		return false;
	}
	mysql_close();
	return true;
	
}


function insert_dbVolunteersTest($volunteer){
	if(! $volunteer instanceof Volunteer){
		return false;
	}
	/*
	connect();
	$query = "SELECT * FROM dbVolunteers WHERE id = '" . $volunteer->get_id() . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		delete_dbVolunteers ($volunteer->get_id());
		connect();
	}
	*/
	
	$lastTripDate = $volunteer->get_lastTripDate();
	
	if($lastTripDate != ''){
	$lastTripDate = "'" .  $lastTripDate . "'";
	}
	else{
		$lastTripDate = "NULL";
	}
	
	//echo $lastTripDate . "<br />";
	
	$tripCount = "NULL";
	
	if($volunteer->get_tripCount() != ""){
		$tripCount = $volunteer->get_tripCount();
	}
	
	$affiliateId = "NULL";
	if($volunteer->get_affiliateId() != ""){
		$affiliateId =$volunteer->get_affiliateId();
	}
	
	$query = "INSERT INTO dbVolunteers VALUES ('".
				$volunteer->get_id()."','" .
				mysql_real_escape_string($volunteer->get_last_name())."','".
				mysql_real_escape_string($volunteer->get_first_name())."','".
				mysql_real_escape_string($volunteer->get_address())."','".
				mysql_real_escape_string($volunteer->get_city())."','".
				$volunteer->get_state()."','".
				$volunteer->get_zip()."','".
				$volunteer->get_phone1()."','".
				$volunteer->get_phone2()."','".
				mysql_real_escape_string($volunteer->get_email())."','".
				implode(',',$volunteer->get_type())."','".
				$volunteer->get_status()."','".
				$volunteer->get_area()."','".
				$volunteer->get_license_no()."','".
				$volunteer->get_license_state()."','".
				$volunteer->get_license_expdate()."','".
				implode(',',$volunteer->get_accidents())."','".
				implode(',',$volunteer->get_availability())."','".
				implode(',',$volunteer->get_schedule())."','".
				implode(',',$volunteer->get_history())."','".
				$volunteer->get_birthday()."','".
				$volunteer->get_start_date()."','".
				mysql_real_escape_string($volunteer->get_notes())."','".
				$volunteer->get_password()."',".
				$tripCount .",".
				$lastTripDate.",'".
				$volunteer->get_volunteerTrainingDate()."','".
				$volunteer->get_driverTrainingDate()."','".
				$volunteer->get_shirtSize()."',".
				$affiliateId .
	            ");";
				
				
		
	echo "Query:". $query . "<br />";
	$result = mysql_query($query);
	if (!$result) {
		echo (mysql_error(). " Unable to insert into dbVolunteers: " . $volunteer->get_id(). "\n");
		mysql_close();
		return false;
	}
	mysql_close();

	return true;
	
}

function update_dbVolunteers($volunteer){
	if (! $volunteer instanceof Volunteer) {
		echo ("Invalid argument for update_dbVolunteer function call");
		return false;
	}
	if (delete_dbVolunteers($volunteer->get_id()))
	return insert_dbVolunteers($volunteer);
	else {
		echo (mysql_error()."unable to update dbVolunteers table: ".$volunteer->get_id());
		return false;
	}
}

function delete_dbVolunteers($id){
	connect();
	$result = mysql_query("DELETE FROM dbVolunteers WHERE id =\"".$id."\"");
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbVolunteers: ".$id);
		return false;
	}
	return true;
}

?>