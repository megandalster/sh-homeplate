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
	$areas = array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort");
	$items = array("Meat", "Frozen", "Bakery", "Grocery", "Dairy", "Produce");	
					
    // Set necessary values using the GET routine
	$routeID = substr($_GET['stop_id'],0,12);
	$client_id = substr($_GET['stop_id'],12);
	// If the current stop has not been created then create it and add it to the database.
    // Otherwise, retrieve it from the database.
	if (!retrieve_dbStops($routeID.$client_id)){
		$stop1 = new Stop($routeID, $client_id, $client_type, $client_items, $driver_notes);
		insert_dbStops($stop1);
	}
	else{
		$stop1 = retrieve_dbStops($routeID.$client_id);
	}
	$client_type = $_GET['client_type'];
	$area = substr($_GET['stop_id'],9,3);
	$ndate = substr($_GET['stop_id'],0,8);
	$date = date('l, F j, Y', mktime(0,0,0,substr($ndate,3,2),substr($ndate,6,2),substr($ndate,0,2)));
	$client_items = "";
	
	// Weight variables for different food types and driver notes are initialized if they have not already been set.
	$meat_weight = isset($_POST["meat_weight"]) ? $_POST["meat_weight"] : $stop1->get_item_weight(0);
	$frozen_weight = isset($_POST["frozen_weight"]) ? $_POST["frozen_weight"] : $stop1->get_item_weight(1);
	$bakery_weight = isset($_POST["bakery_weight"]) ? $_POST["bakery_weight"] : $stop1->get_item_weight(2);
	$grocery_weight = isset($_POST["grocery_weight"]) ? $_POST["grocery_weight"] : $stop1->get_item_weight(3);
	$dairy_weight = isset($_POST["dairy_weight"]) ? $_POST["dairy_weight"] : $stop1->get_item_weight(4);
	$produce_weight = isset($_POST["produce_weight"]) ? $_POST["produce_weight"] : $stop1->get_item_weight(5);
	$total_weight = $meat_weight+$frozen_weight+$bakery_weight+$grocery_weight+$dairy_weight+$produce_weight;
	$driver_notes = ""; // isset($_POST["driver_notes"]) ? $_POST["driver_notes"] : $stop1->get_notes();
		
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
			<p>Area: <?php echo($areas[$area])?><br />
			   Date: <?php echo($date)?></p>
			
			<!-- The data entry field for weight types and driver notes -->
			<form method="post"?>
			<fieldset>
				<legend><b>Data Entry</b></legend><br />
				<i>Meat Weight: </i>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="10" name="meat_weight" <?php echo 'value='.$meat_weight?>> lbs.<br />
				<i>Deli Weight: </i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="10" name="frozen_weight" <?php echo 'value='.$frozen_weight?>> lbs.<br />
				<i>Bakery Weight: </i>&nbsp;<input type="text" size="10" name="bakery_weight" <?php echo 'value='.$bakery_weight?>> lbs.<br />
				<i>Grocery Weight: </i><input type="text" size="10" name="grocery_weight" <?php echo 'value='.$grocery_weight?>> lbs.<br />
				<i>Dairy Weight: </i>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="10" name="dairy_weight" <?php echo 'value='.$dairy_weight?>> lbs.<br />
				<i>Produce Weight: </i><input type="text" size="10" name="produce_weight" <?php echo 'value='.$produce_weight?>> lbs.<br /><br />	
				<i>Total Weight: <?php echo $total_weight?> lbs.	
			
		<!--	<br><br><i>Additional notes:</i><br />
			<textarea rows="3" cols="50" name="driver_notes"><?php echo $driver_notes;?></textarea>   -->
			
			<input type = "hidden" name = "submitted" value = "true"/>	
			<br><br><input type="submit" value="Save" name="Submit"/>&nbsp;&nbsp;Hit <i>Save</i> to re-total and save these weights and notes.
			<?php
			echo '<br><br><a href="editRoute.php?routeID='.$routeID.'">Return to Route</a>';
			echo '</fieldset></form><br />';
			
			// If values have been submitted, then validate and update the database if valid
			if (isset($_POST['Submit'])){
				$errors = false;
				$tw = 0;
				if ($meat_weight!="" && preg_match('/[0-9]+/',$meat_weight,$matches)==0 || $matches[0]!=$meat_weight) {  // validate as a number
					echo('<div class = "warning"><b>Please enter a valid meat weight</b>
						</div><br/><br/>'); $errors = true; }
				else $tw+=$meat_weight;
				if ($frozen_weight!="" && preg_match('/[0-9]+/',$frozen_weight,$matches)==0 || $matches[0]!=$frozen_weight) { // validate as a number
					echo('<div class = "warning"><b>Please enter a valid frozen weight</b>
						</div><br/><br/>'); $errors = true; }
				else $tw+=$frozen_weight;
				if ($bakery_weight!="" && preg_match('/[0-9]+/',$bakery_weight,$matches)==0 || $matches[0]!=$bakery_weight) { // validate as a number
					echo('<div class = "warning"><b>Please enter a valid bakery weight</b>
						</div><br/><br/>'); $errors = true; }
				else $tw+=$bakery_weight;
				if ($grocery_weight!="" && preg_match('/[0-9]+/',$grocery_weight,$matches)==0 || $matches[0]!=$grocery_weight) { // validate as a number
					echo('<div class = "warning"><b>Please enter a valid grocery weight</b>
						</div><br/><br/>'); $errors = true; }
				else $tw+=$grocery_weight;
				if ($dairy_weight!="" && preg_match('/[0-9]+/',$dairy_weight,$matches)==0 || $matches[0]!=$dairy_weight) { // validate as a number
					echo('<div class = "warning"><b>Please enter a valid dairy weight</b>
						</div><br/><br/>'); $errors = true; }
			    else $tw+=$dairy_weight;
				if ($produce_weight!="" && preg_match('/[0-9]+/',$produce_weight,$matches)==0 || $matches[0]!=$produce_weight) {  // validate as a number
					echo('<div class = "warning"><b>Please enter a valid produce weight</b>
						</div><br/><br/>'); $errors = true; }
				else $tw+=$produce_weight;
				if (preg_match('/[0-9]+/',$total_weight,$matches)==0 || $matches[0]!=$total_weight) {  // validate as a number and as the correct sum
					echo('<div class = "warning"><b>Please enter a valid total weight</b>
						</div><br/><br/>'); $errors = true; }
				if (!$errors) {
					$stop1->remove_all_items();
					$stop1->set_item(0, "Meat:".$meat_weight);
					$stop1->set_item(1, "Deli:".$frozen_weight);
					$stop1->set_item(2, "Bakery:".$bakery_weight);
					$stop1->set_item(3, "Grocery:".$grocery_weight);
					$stop1->set_item(4, "Dairy:".$dairy_weight);
					$stop1->set_item(5, "Produce:".$produce_weight);
					$stop1->set_total_weight($tw);
					$stop1->set_notes("!".$driver_notes);
					update_dbStops($stop1);
				}
			}
			// The link to return to the current route.
			echo '</div>';
			include('footer.inc');
			?>
		</div>
	</body>
</html>