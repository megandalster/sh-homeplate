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

function create_dbVolunteers(){
	connect();
	mysql_query("DROP TABLE IF EXISTS dbVolunteers");
	$result = mysql_query("CREATE TABLE dbVolunteers (id TEXT NOT NULL, last_name TEXT, first_name TEXT, address TEXT, city TEXT, state TEXT, zip TEXT, 
							phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), email TEXT, type TEXT, status TEXT, area TEXT, license_no TEXT, license_state TEXT, 
							license_expdate TEXT, convictions TEXT, accidents TEXT, availability TEXT, schedule TEXT, history TEXT, birthday TEXT,
							start_date TEXT, notes TEXT, password TEXT)");
	mysql_close();
	if(!$result){
			echo (mysql_error()."Error creating database dbVolunteers. \n");
			return false;
	}
	return true;
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
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'], $result_row['convictions'], 
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password']);
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
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'], $result_row['convictions'], 
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}

// retrieve only those volunteers that match the criteria given in the arguments
function getonlythose_dbVolunteers($area, $type, $status, $name, $availability) {
	connect();
	$query = "SELECT * FROM dbVolunteers WHERE area like '%".$area. "%'" .  
	       " AND type LIKE '%".$type."%'" . 
			 " AND status LIKE '%".$status."%'" . 
			 " AND (first_name LIKE '%".$name."%' OR last_name LIKE '%".$name."%')" .
			 " AND availability LIKE '%".$availability."%' ORDER BY last_name";
	$result = mysql_query($query);
	$theVols = array();
		
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'], $result_row['convictions'], 
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}

function get_team_captains ($area) {
	connect();
	$result=mysql_query("SELECT * FROM dbVolunteers WHERE type LIKE %teamcaptain% AND area  = '".$area."'");
	
	$theVols = array();	
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Volunteer($result_row['last_name'], $result_row['first_name'], $result_row['address'], $result_row['city'], $result_row['state'],
							$result_row['zip'], $result_row['phone1'], $result_row['phone2'], $result_row['email'], $result_row['type'], $result_row['status'],
							$result_row['area'], $result_row['license_no'], $result_row['license_state'], $result_row['license_expdate'], $result_row['convictions'], 
							$result_row['accidents'], $result_row['availability'], $result_row['schedule'], $result_row['history'], $result_row['birthday'],
							$result_row['start_date'], $result_row['notes'], $result_row['password']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}

function get_driver_ids ($area, $day) {
	connect();
	$result=mysql_query("SELECT * FROM dbVolunteers WHERE type LIKE '%driver%' AND availability LIKE '%".$day."%' AND area  = '".$area."'");
	
	$theIds = "";	
	while($result_row = mysql_fetch_assoc($result)){
		$theIds .= ','.$result_row['id'];
	}
	mysql_close();
	return substr($theIds,1);
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
	$query = "INSERT INTO dbVolunteers VALUES ('".
				$volunteer->get_id()."','" .
				$volunteer->get_last_name()."','".
				$volunteer->get_first_name()."','".
				$volunteer->get_address()."','".
				$volunteer->get_city()."','".
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
				implode(',',$volunteer->get_convictions())."','".
				implode(',',$volunteer->get_accidents())."','".
				implode(',',$volunteer->get_availability())."','".
				implode(',',$volunteer->get_schedule())."','".
				implode(',',$volunteer->get_history())."','".
				$volunteer->get_birthday()."','".
				$volunteer->get_start_date()."','".
				$volunteer->get_notes()."','".
				$volunteer->get_password().
	            "');";
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
function phone_edit($phone) {
	return substr($phone,0,3)."-".substr($phone,3,3)."-".substr($phone,6);
}
?>