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
 * @version April 4, 2012
 */
	session_start();
	session_cache_expire(30);
	
	include_once('database/dbStops.php');
	include_once('domain/Stop.php');
	$routeID = substr($_GET['stop_id'],0,12);
	$area = substr($_GET['stop_id'],9,3);
	$ndate = substr($_GET['stop_id'],0,8);
	$date = date('l, F j, Y', mktime(0,0,0,substr($ndate,3,2),substr($ndate,6,2),substr($ndate,0,2)));
	$client_id = substr($_GET['stop_id'],12);
	$client_type = $_GET['client_type'];
	$client_items = "";
	
	$meat_weight = isset($_POST["meat_weight"]) ? $_POST["meat_weight"] : "0";
	$bakery_weight = isset($_POST["bakery_weight"]) ? $_POST["bakery_weight"] : "0";
	$dairy_weight = isset($_POST["dairy_weight"]) ? $_POST["dairy_weight"] : "0";
	$produce_weight = isset($_POST["produce_weight"]) ? $_POST["produce_weight"] : "0";
	$grocery_weight = isset($_POST["grocery_weight"]) ? $_POST["grocery_weight"] : "0";
	$driver_notes = isset($_POST["driver_notes"]) ? $_POST["driver_notes"] : "";
	
	// $stop1 = retrieve_dbStops($id); 
	$stop1 = new Stop($ndate."-".$area, $client_id, $client_type, $client_items, $driver_notes);
	insert_dbStops($stop1);
		
	$item1 = "Meat:" . $meat_weight;
	$stop1->add_item($item1);
				
	$item2 = "Bakery:" . $bakery_weight;
	$stop1->add_item($item2);
				
	$item3 = "Dairy:" . $dairy_weight;
	$stop1->add_item($item3);
		
	$item4 = "Produce:" . $produce_weight;
	$stop1->add_item($item4);
		
	$item5 = "Grocery:" . $grocery_weight;
	$stop1->add_item($item5);
		
	$stop1->set_notes($driver_notes);
		
	update_dbStops($stop1);
	
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
  
			<p><big><b><?php echo($client_id)?></b></big></p>
			
			<p>Route: <?php echo($area)?><br />
			   Driver: <?php // echo($volunteer->get_first_name()." ".$volunteer->get_last_name())?>
			   			Nick Wetzel<br />
			   Date: <?php echo($date)?></p>
			   
			<fieldset>
				<legend><b>Instructions:</b></legend><br />
			   <b>1.</b> To insert a value, tap the corresponding cell in the table below.<br /><br />
			   <b>2.</b> Insert additional notes into the text box below the table if necessary.<br /><br />
			   <b>3.</b> When the values are entered, tap the "Submit" button at the bottom.<br /><br />
			   <b>4.</b> Check that the submitted values in the red box at the bottom of the screen are correct. If not, re-enter them.<br /><br /> 
			   <b>5.</b> After all values are submitted, tap the "Return to Route" button at the bottom.<br />
			</fieldset>
			
			<form method="post"?>
			<fieldset>
			<legend><b>Data Entry:</b></legend><br />
			<table border = "1" cellpadding = "5">
				<tr>
					<td><b>Type</b></td>
					<td><b>Weight (lbs.)</b></td>
					
				</tr>
				<tr>
					<td>Meat:</td>
					<td><input type="text" name="meat_weight" value = <?php echo $meat_weight?> /></td>
					
				</tr>
				<tr>
					<td>Bakery:</td>
					<td><input type="text" name="bakery_weight" value = <?php echo $bakery_weight?> /></td>
					
				</tr>
				<tr>
					<td>Dairy:</td>
					<td><input type="text" name="dairy_weight" value = <?php echo $dairy_weight?> /></td>
					
				</tr>
				<tr>
					<td>Produce:</td>
					<td><input type="text" name="produce_weight" value = <?php echo $produce_weight?> /></td>
					
				</tr>
				<tr>
					<td>Grocery:</td>
					<td><input type="text" name="grocery_weight" value = <?php echo $grocery_weight?> /></td>
					
					
				</tr>
			</table>	
				<br />
			<p>Enter any additional notes by tapping the text box below:</p>
			<textarea rows="10" cols="50" name="driver_notes" ></textarea>
				
			<input type = "hidden" name = "submitted" value = "true"/>
				
				<br />
				<br />
				
			<input type="submit" value="Submit" />
			</fieldset>
			</form><br />
			
			<?php 
			if (isset($_POST['submitted'])){
				echo('
				<div class = "warning">
				<b>Submitted Values:</b><br/><br/>
				Meat: <b>'.$meat_weight.'</b> lbs. <br/><br/>
				Bakery: <b>'.$bakery_weight.'</b> lbs. <br/><br/>
				Dairy: <b>'.$dairy_weight.'</b> lbs. <br/><br/>
				Produce: <b>'.$produce_weight.'</b> lbs. <br/><br/>
				Grocery: <b>'.$grocery_weight.'</b> lbs. <br/><br/>
				Total Weight: <b>'.($meat_weight + $bakery_weight + $dairy_weight + 
								$produce_weight + $grocery_weight).'</b> lbs.<br/><br/>
				Notes: '.$driver_notes.'
				</div>
				');
			}
			echo '<a href="editRoute.php?routeID='.$routeID.'"><strong>Return to Route</strong></a>';
			include('footer.inc');
			?>
			</div>
			
		</div>
	</body>
</html>