<?php
/*
* Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homecheck, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).
*/

/*
* dbAffiliates class for Homeplate
* @author Alllen Tucker
* @version February 27, 2012
*/

include_once(dirname(__FILE__).'/../domain/Affiliate.php');
include_once(dirname(__FILE__).'/dbinfo.php');

function create_dbAffiliates(){
	connect();
	mysql_query("DROP TABLE IF EXISTS dbAffiliates");
	$result = mysql_query("CREATE TABLE dbAffiliates (id TEXT NOT NULL, last_name TEXT, first_name TEXT, address TEXT, city TEXT, state TEXT, zip TEXT, 
							phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), email TEXT, type TEXT, status TEXT, area TEXT, license_no TEXT, license_state TEXT, 
							license_expdate TEXT, accidents TEXT, availability TEXT, schedule TEXT, history TEXT, birthday TEXT,
							start_date TEXT, notes TEXT, password TEXT)");
	mysql_close();
	if(!$result){
			echo (mysql_error()."Error creating database dbAffiliates. \n");
			return false;
	}
	return true;
}

function retrieve_dbAffiliates($id){
	connect();
	$result=mysql_query("SELECT * FROM dbAffiliates WHERE affiliateId  = '".$id."'");
	if(mysql_num_rows($result) !== 1){
			mysql_close();
			return false;
	}
	$result_row = mysql_fetch_assoc($result);
	$theAffiliate = new Affiliate($result_row['affiliateId'], $result_row['affiliateName']);
							
							
	mysql_close();
	return $theAffiliate;
}

function getall_dbAffiliates(){
	connect();
	$result = mysql_query("SELECT * FROM dbAffiliates ORDER BY affiliateName");
	$theAffiliates = array();
	while($result_row = mysql_fetch_assoc($result)){
		$theAffiliate = new Affiliate($result_row['affiliateId'], $result_row['affiliateName']);
		$theAffiliates[] = $theAffiliate;
	}
	mysql_close();
	return $theAffiliates;
}

function insert_dbAffiliates($affiliate){
	if(! $affiliate instanceof Affiliate){
		return false;
	}
	connect();
	$query = "SELECT * FROM dbAffiliates WHERE affiliateId = '" . $affiliate->get_affiliateId() . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) != 0) {
		delete_dbAffiliates ($affiliate->get_affiliateId());
		connect();
	}
	
	
	$query = "INSERT INTO dbAffiliates (affiliateName) VALUES ('".
				$affiliate->get_affiliateName().				
	            "');";
	$result = mysql_query($query);
	if (!$result) {
		echo (mysql_error(). " Unable to insert into dbAffiliates: " . $affiliate->get_affiliateId(). "\n");
		mysql_close();
		return false;
	}
	mysql_close();
	return true;
	
}

function update_dbAffiliates($affiliate){
	if (! $affiliate instanceof Affiliate) {
		echo ("Invalid argument for update_dbAffiliate function call");
		return false;
	}
	if (delete_dbAffiliates($affiliate->get_affiliateId()))
	return insert_dbAffiliates($affiliate);
	else {
		echo (mysql_error()."unable to update dbAffiliates table: ".$affiliate->get_affiliateId());
		return false;
	}
}

function delete_dbAffiliates($id){
	connect();
	$result = mysql_query("DELETE FROM dbAffiliates WHERE affiliateId =\"".$id."\"");
	mysql_close();
	if (!$result) {
		echo (mysql_error()." unable to delete from dbAffiliates: ".$id);
		return false;
	}
	return true;
}


// retrieve only those volunteers that match the criteria given in the arguments
function getonlythose_dbAffiliates($name) {
	connect();
	$query = "SELECT * FROM dbAffiliates WHERE affiliateName like '%".$name. "%'" ;
	
    $query .= " ORDER BY affiliateName";
	$result = mysql_query($query);
	$theVols = array();
		
	while($result_row = mysql_fetch_assoc($result)){
		$theVol = new Affiliate($result_row['affiliateId'], $result_row['affiliateName']);
		$theVols[] = $theVol;
	}
	mysql_close();
	return $theVols;
}


?>