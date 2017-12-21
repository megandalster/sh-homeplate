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
	$con=connect();
	mysqli_query($con,"DROP TABLE IF EXISTS dbAffiliates");
	$result = mysqli_query($con,"CREATE TABLE dbAffiliates (id TEXT NOT NULL, last_name TEXT, first_name TEXT, address TEXT, city TEXT, state TEXT, zip TEXT, 
							phone1 VARCHAR(12) NOT NULL, phone2 VARCHAR(12), email TEXT, type TEXT, status TEXT, area TEXT, license_no TEXT, license_state TEXT, 
							license_expdate TEXT, accidents TEXT, availability TEXT, schedule TEXT, history TEXT, birthday TEXT,
							start_date TEXT, notes TEXT, password TEXT)");
	mysqli_close($con);
	if(!$result){
			echo (mysqli_error($con)."Error creating database dbAffiliates. \n");
			return false;
	}
	return true;
}

function retrieve_dbAffiliates($id){
	$con=connect();
	$result=mysqli_query($con,"SELECT * FROM dbAffiliates WHERE affiliateId  = '".$id."'");
	if(mysqli_num_rows($result) !== 1){
			mysqli_close($con);
			return false;
	}
	$result_row = mysqli_fetch_assoc($result);
	$theAffiliate = new Affiliate($result_row['affiliateId'], $result_row['affiliateName']);
							
							
	mysqli_close($con);
	return $theAffiliate;
}

function getall_dbAffiliates(){
	$con=connect();
	$result = mysqli_query($con,"SELECT * FROM dbAffiliates ORDER BY affiliateName");
	$theAffiliates = array();
	while($result_row = mysqli_fetch_assoc($result)){
		$theAffiliate = new Affiliate($result_row['affiliateId'], $result_row['affiliateName']);
		$theAffiliates[] = $theAffiliate;
	}
	mysqli_close($con);
	return $theAffiliates;
}

function insert_dbAffiliates($affiliate){
	if(! $affiliate instanceof Affiliate){
		return false;
	}
	$con=connect();
	$query = "SELECT * FROM dbAffiliates WHERE affiliateId = '" . $affiliate->get_affiliateId() . "'";
	$result = mysqli_query($con,$query);
	if (mysqli_num_rows($result) != 0) {
		delete_dbAffiliates ($affiliate->get_affiliateId());
		$con=connect();
	}
	
	
	$query = "INSERT INTO dbAffiliates (affiliateName) VALUES ('".
				$affiliate->get_affiliateName().				
	            "');";
	$result = mysqli_query($con,$query);
	if (!$result) {
		echo (mysqli_error($con). " Unable to insert into dbAffiliates: " . $affiliate->get_affiliateId(). "\n");
		mysqli_close($con);
		return false;
	}
	mysqli_close($con);
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
		$con=connect();
		echo (mysqli_error($con)."unable to update dbAffiliates table: ".$affiliate->get_affiliateId());
		return false;
	}
}

function delete_dbAffiliates($id){
	$con=connect();
	$result = mysqli_query($con,"DELETE FROM dbAffiliates WHERE affiliateId =\"".$id."\"");
	mysqli_close($con);
	if (!$result) {
		echo (mysqli_error($con)." unable to delete from dbAffiliates: ".$id);
		return false;
	}
	return true;
}


// retrieve only those volunteers that match the criteria given in the arguments
function getonlythose_dbAffiliates($name) {
	$con=connect();
	$query = "SELECT * FROM dbAffiliates WHERE affiliateName like '%".$name. "%'" ;
	
    $query .= " ORDER BY affiliateName";
	$result = mysqli_query($con,$query);
	$theVols = array();
		
	while($result_row = mysqli_fetch_assoc($result)){
		$theVol = new Affiliate($result_row['affiliateId'], $result_row['affiliateName']);
		$theVols[] = $theVol;
	}
	mysqli_close($con);
	return $theVols;
}


?>