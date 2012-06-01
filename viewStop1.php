<?php

/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * viewStop1 GUI for Homeplate
 * @author Nicholas Wetzel
 * @version May 8, 2012
 */

/*
 * This file creates the data entry page for stops that record only total weight.
 */
	session_start();
	session_cache_expire(30);
	
	include_once('database/dbStops.php');
	include_once('domain/Stop.php');
	include_once('database/dbVolunteers.php');
    include_once('domain/Volunteer.php');
	$areas = array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort");
					
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
	
	// Total weight variable and driver notes are initialized if they have not already been set.
	$total_weight = isset($_POST["total_weight"]) ? $_POST["total_weight"] : $stop1->get_total_weight();
	$driver_notes = isset($_POST["driver_notes"]) ? $_POST["driver_notes"] : $stop1->get_notes();
	
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
			
			<!-- The data entry field for total weight and driver notes -->
			<form method = "post">
			<fieldset>
				<legend><b>Data Entry</b></legend><br />
				<i>Total Weight:</i> <input type="text" name="total_weight" <?php echo 'value='.$total_weight?>> lbs.
				
			<br><br><i>Additional notes:</i><br />
			<textarea rows="3" cols="50" name="driver_notes"><?php echo $driver_notes;?></textarea>
			
			<!-- A hidden variable that, when submitted, is used to display submitted values and update the databases -->	
			<br><input type = "hidden" name = "submitted" value = "true"/>		
			<br><input type="submit" value="Submit"/>&nbsp;&nbsp;<i>Hit the Submit button to save this weight and notes.</i>
			</fieldset>
			</form><br />
			
			<?php 
			// If values have been submitted, then update the database and display the submitted values to the driver.
			if (isset($_POST['submitted'])){
				if (preg_match('/[0-9]+/',$total_weight,$matches)==0 || $matches[0]!=$total_weight)  // validate total weight as a number
					echo('<div class = "warning"><b>Please enter a valid total weight</b>
						</div><br/><br/>');
				else {
					$stop1->set_total_weight($total_weight);
					$stop1->set_notes($driver_notes);
					update_dbStops($stop1);
					echo('<div class = "warning"><b>Please check that the weight you submitted is correct before "Returning to Route"</b>
						</div><br/>');
				}
			}
			
			// The link to return to the current route.
			echo '<a href="editRoute.php?routeID='.$routeID.'"><big>Return to Route</big></a><br><br>';
			echo '</div>';
			include('footer.inc');
			?>
		</div>
	</body>
</html>