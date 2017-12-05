<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * dbStops module for Homeplate
 * @author Nicholas Wetzel
 * @version May 8, 2012
 */

/*
 * This module implements all functionality with the 'Stop' database using mySQL queries. 
 */

include_once(dirname(__FILE__).'/../domain/Stop.php');
include_once(dirname(__FILE__).'/dbinfo.php');

// Create the DB stops table with the necessary column values.
function create_dbStops() {
    $con=connect();
    mysqli_query($con,"DROP TABLE IF EXISTS dbStops");
    $result = mysqli_query($con,"CREATE TABLE dbStops (id TEXT NOT NULL, route TEXT NOT NULL, client TEXT NOT NULL, 
     											 type TEXT NOT NULL, items TEXT, weight TEXT, date TEXT, notes TEXT)");
    if (!$result) {
        echo mysqli_error($con) . "Error creating dbStops table. <br>";
        mysqli_close($con);
        return false;
    }
    mysqli_close($con);
    return true;
}

// Insert a stop and all of its values into the DB.
function insert_dbStops ($stop){
    if (! $stop instanceof Stop) {
        return false;
    }
    $con=connect();

	$query = "SELECT * FROM dbStops WHERE id = '" . $stop->get_id() . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) != 0) {
        delete_dbStops ($stop->get_id());
        $con=connect();
    }
    $query = "INSERT INTO dbStops VALUES ('".
                $stop->get_id()."','" .
                $stop->get_route_id()."','".
                $stop->get_client_id()."','".
                $stop->get_type()."','".
                implode(',',$stop->get_items())."','".
                $stop->get_total_weight()."','".
                $stop->get_date()."','".
                $stop->get_notes()."');";
    $result = mysqli_query($con,$query);
    if (!$result) {
        echo (mysqli_error($con). " unable to insert into dbStops: " . $stop->get_id(). "\n");
        mysqli_close($con);
        return false;
    }
    mysqli_close($con);
    return true;
}

// Retrieve a stop from the DB by passing the stop ID.
function retrieve_dbStops ($id) {
	$con=connect();
    $query = "SELECT * FROM dbStops WHERE id = '".$id."'";
    $result = mysqli_query ($con,$query);
    if (mysqli_num_rows($result) !== 1){
    	mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    $items = $result_row['items'];
    if ($result_row['items']=="" || $result_row['items']=="Meat:,Frozen:,Bakery:,Grocery:,Dairy:,Produce:")
    	$items = $result_row['weight']; 
		
	if($result_row['items'] == "Meat:-1,Frozen:-1,Bakery:-1,Grocery:-1,Dairy:-1,Produce:-1"){
		$items = 0;
	}
	
    $theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $items, $result_row['notes']);
	mysqli_close($con); 
    return $theStop;   
}

// Return all stops from the DB.
function getall_dbStops () {
    $con=connect();
    $query = "SELECT * FROM dbStops ORDER BY id";
    $result = mysqli_query ($con,$query);
    $theStops = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
    	$items = $result_row['items'];
    	if ($result_row['items']=="")
    		$items = $result_row['weight'];
        $theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $items, $result_row['notes']);
        $theStops[] = $theStop;
    }
	mysqli_close($con);
    return $theStops; 
}

// Returns all stops within a certain date range.
function getall_dbStops_between_dates ($area, $type, $client_name, $start_date, $end_date, $deliveryAreaId, $chain, $county) {
//	echo "area,type,client_name,start_date,end_date,deliveryareaid,chain,county=".
//			$area.",".$type.",".$client_name.",".$start_date.",".$end_date.",".$deliveryAreaId.",".$chain.",".$county;
	$con=connect();
	$query = "SELECT route, client, type, SUM(if(weight < 0,0,weight)) as 'SUM(weight)', notes FROM dbStops where ".
			"route like '%".$area."%' AND ".
			"client like '%".$client_name."%' AND ".
			"type like '%".$type."%' AND (weight > 0 OR weight < 0) ";
			
			
		if($deliveryAreaId > 0){
			$query = $query . " AND client IN (SELECT id from dbClients WHERE deliveryAreaId = " . $deliveryAreaId . ")";
		}
			
		if(!empty($county)){
			$query = $query . " AND client IN (SELECT id from dbClients WHERE county = '" . $county . "')";
		}
			
		if(!empty($chain)){
			$query = $query . " AND client IN (SELECT id from dbClients WHERE chain_name = '" . $chain . "')";
		}
			
		$query =  $query . " AND date >= '". $start_date . "' AND date <= '". $end_date . "' GROUP BY client";
			
			echo "<!--" . $query . "-->\n";
			
    $result = mysqli_query ($con,$query);
    $theStops = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
    	// The total weight of the stop is returned instead of its items for the purpose
    	// of generating reports with each stop's total weight.
		$weight = $result_row['SUM(weight)'];
		$clientName = $result_row['client'];
		if($area != ''){
		
			$query = "SELECT SUM(weight) FROM dbStops ".
" WHERE client = '" . $clientName . "' AND id LIKE '%". $area  . "%'" .
" AND weight > 0".
" AND date >= '". $start_date . "' AND date <= '". $end_date . "' ".
" GROUP BY client";

//echo "<!--" . $query . "-->\n";

			$weightQueryResult = mysqli_query($con,$query);
			$weight = 0;
			 while ($weightResult_row = mysqli_fetch_assoc($weightQueryResult)) {
				$thisWeight = $weightResult_row['SUM(weight)'];
				//if($thisWeight > 0)
					$weight += $thisWeight;
				
			 }
		}
		
    	$theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $weight , $result_row['notes']);
        $theStops[] = $theStop;
    }
	mysqli_close($con);
    return $theStops; 
}

// Returns all stops within a certain date range.
function getall_dbWalmartPublixStops_between_dates ($area, $client_name, $start_date, $end_date, $deliveryAreaId, $chain, $county) {
	$con=connect();
	$query = "SELECT route, client, type, items, notes FROM dbStops where ".
			"route like '%".$area."%' AND ".
			"client like '%".$client_name."%' AND ".
			"items like '%:%' AND weight > 0 ";
			
			
		if($deliveryAreaId > 0){
			$query = $query . " AND client IN (SELECT id from dbClients WHERE deliveryAreaId = " . $deliveryAreaId . ")";
		}
		if($county){
			$query = $query . " AND client IN (SELECT id from dbClients WHERE county = " . $county . ")";
		}
			
		if(!empty($chain)){
			$query = $query . " AND client IN (SELECT id from dbClients WHERE chain_name = '" . $chain . "')";
		}
		
		$query = $query . 	" AND date >= '". $start_date . "' AND date <= '". $end_date . "' ORDER BY client";
    $result = mysqli_query ($con,$query);
    $theStops = array();
	
	
    while ($result_row = mysqli_fetch_assoc($result)) {
		//weight tally was here 
		
		//echo "<!--" . $result_row['items'] . "-->\n";
		
		$theStop = new Stop($result_row['route'], $result_row['client'], $result_row['type'], $result_row['items'] , $result_row['notes']);
        $theStops[] = $theStop;
    }
	mysqli_close($con);
    return $theStops; 
}

// Update the values of a specified stop by removing it from the DB and then
// inserting it again.
function update_dbStops ($stop) {
if (! $stop instanceof Stop) {
		echo ("Invalid argument for update_dbStops function call");
		return false;
	}
	if (delete_dbStops($stop->get_id()))
	   return insert_dbStops($stop);
	else {
	   $con=connect();
	   echo (mysqli_error($con)."unable to update dbStops table: ".$stop->get_id());
	   mysqli_close($con);
	   return false;
	}
}

// Remove a stop and all of its values from the DB.
function delete_dbStops($id) {
	$con=connect();
    $query="DELETE FROM dbStops WHERE id=\"".$id."\"";
	$result=mysqli_query($con,$query);
	if (!$result) {
		echo (mysqli_error($con)." unable to delete from dbStops: ".$id);
		mysqli_close($con);
		return false;
	}
	mysqli_close($con);
    return true;
}