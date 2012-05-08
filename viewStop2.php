<?php

/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * viewStop GUI for Homeplate
 * @author Nicholas Wetzel
 * @version May 8, 2012
 */

/*
 * This file creates the data entry page for stops that record separate weights for
 * different food types.
 */
	session_start();
	session_cache_expire(30);
	
	include_once('database/dbStops.php');
	include_once('domain/Stop.php');
	include_once('database/dbVolunteers.php');
    include_once('domain/Volunteer.php');
	
    // Set necessary values using the GET routine
	$routeID = substr($_GET['stop_id'],0,12);
	$area = substr($_GET['stop_id'],9,3);
	$ndate = substr($_GET['stop_id'],0,8);
	$date = date('l, F j, Y', mktime(0,0,0,substr($ndate,3,2),substr($ndate,6,2),substr($ndate,0,2)));
	$client_id = substr($_GET['stop_id'],12);
	$client_type = $_GET['client_type'];
	$client_items = "";
	
	// Weight variables for different food types and driver notes are initialized if they have not already been set.
	$meat_weight = isset($_POST["meat_weight"]) ? $_POST["meat_weight"] : "0";
	$bakery_weight = isset($_POST["bakery_weight"]) ? $_POST["bakery_weight"] : "0";
	$dairy_weight = isset($_POST["dairy_weight"]) ? $_POST["dairy_weight"] : "0";
	$produce_weight = isset($_POST["produce_weight"]) ? $_POST["produce_weight"] : "0";
	$grocery_weight = isset($_POST["grocery_weight"]) ? $_POST["grocery_weight"] : "0";
	$driver_notes = isset($_POST["driver_notes"]) ? $_POST["driver_notes"] : "";
	
	// Retrieve the first and last name of the current driver from the database.
	$person = retrieve_dbVolunteers($_SESSION['_id']);
    $first_name = $person->get_first_name();
    $last_name = $person->get_last_name();
	
    // If the current stop has not been created then create it and add it to the database.
    // Otherwise, retrieve it from the database.
	if (!retrieve_dbStops($routeID.$client_id)){
	$stop1 = new Stop($routeID, $client_id, $client_type, $client_items, $driver_notes);
	insert_dbStops($stop1);
	}
	else{
		$stop1 = retrieve_dbStops($routeID.$client_id);
	}	
?>

<html>
	<head>
		<title>
			Viewing <?php echo($area."-".$client_id."-".$date)?>;
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?php include('header.php');?>
			<div id="content">
  			
  			<!-- Display the name of the current stop -->
			<p><big><b><?php echo($client_id)?></b></big></p>
			
			<!-- Display the associated route, driver and date of the stop -->
			<p>Route: <?php echo($area)?><br />
			   Driver: <?php echo($first_name." ".$last_name)?><br />
			   Date: <?php echo($date)?></p>
			
			<!-- The data entry field for weight types and driver notes -->
			<form method="post"?>
			<fieldset>
				<legend><b>Data Entry:</b></legend><br />
				<p><b>Enter Meat Weight:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="meat_weight" value = 0 /> lbs.<br /><br />
				<b>Enter Bakery Weight:</b>&nbsp;&nbsp;<input type="text" name="bakery_weight" value = 0 /> lbs.<br /><br />
				<b>Enter Dairy Weight:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="dairy_weight" value = 0 /> lbs.<br /><br />
				<b>Enter Produce Weight:</b> <input type="text" name="produce_weight" value = 0 /> lbs.<br /><br />
				<b>Enter Grocery Weight:</b> <input type="text" name="grocery_weight" value = 0 /> lbs.</p>	
			
			<p><i>Enter any additional notes by tapping the text box below:</i><br /><br />
			<textarea rows="5" cols="50" name="driver_notes" ></textarea></p>
			
			<!-- A hidden variable that, when submitted, is used to display submitted values and update the databases -->	
			<p><input type = "hidden" name = "submitted" value = "true"/></p>
				
			<p><input type="submit" value="Submit"/>&nbsp;&nbsp;<i>Click the Submit button to submit the values and notes.</i></p>
			</fieldset>
			</form><br />
			
			<?php 
			// If values have been submitted, then update the database and display the submitted values to the driver.
			if (isset($_POST['submitted'])){
				
				// Any pre-existing items are removed to prevent overlap.
				$stop1->remove_all_items();
		
				$item1 = "Meat:" . $meat_weight;
				$stop1->set_item(0, $item1);
				
				$item2 = "Bakery:" . $bakery_weight;
				$stop1->set_item(1, $item2);
				
				$item3 = "Dairy:" . $dairy_weight;
				$stop1->set_item(2, $item3);
		
				$item4 = "Produce:" . $produce_weight;
				$stop1->set_item(3, $item4);
		
				$item5 = "Grocery:" . $grocery_weight;
				$stop1->set_item(4, $item5);
				
				$stop1->set_notes($driver_notes);
				
				update_dbStops($stop1);
				
				echo('
				<div class = "warning">
				<b>Check that the values below are correct before "Returning to Route":</b><br/><br/>
				Meat: <b>'.$meat_weight.'</b> lbs. <br/><br/>
				Bakery: <b>'.$bakery_weight.'</b> lbs. <br/><br/>
				Dairy: <b>'.$dairy_weight.'</b> lbs. <br/><br/>
				Produce: <b>'.$produce_weight.'</b> lbs. <br/><br/>
				Grocery: <b>'.$grocery_weight.'</b> lbs. <br/><br/>
				Total Weight: <b>'.($meat_weight + $bakery_weight + $dairy_weight + 
								$produce_weight + $grocery_weight).'</b> lbs.<br/><br/>
				Notes: '.$driver_notes.'
				</div><br/><br/>
				');
			}
			
			// The link to return to the current route.
			echo '<a href="editRoute.php?routeID='.$routeID.'"><big>Return to Route</big></a><br />';
			include('footer.inc');
			?>
			
			</div>
		</div>
	</body>
</html>