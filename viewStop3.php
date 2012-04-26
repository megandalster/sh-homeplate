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
	
	include_once('database/dbStops.php');
	include_once('domain/Stop.php');
	
	//$area = $_GET["area"];
	$area = "Hilton Head";
	//$date = $_GET["date"];
	$date = date('l, F j, Y');
	//$id = $date."-".$area.".".$client_id;
	$ndate = date('y-m-d');
	$client_id = "Bi-Lo - HHI North";
	$client_type = "Donor";
	$client_items = "";
	
	$meat_percent = 0.25;
	$dairy_percent = 0.03;
	$bakery_percent = 0.31;
	$produce_percent = 0.18;
	$dry_goods_percent = 0.23;
	$pounds_per_carton = 32;
	
	/*if($_GET["area"]=="SUN"){
		$pounds_per_carton = 32;
	}
	if($_GET["area"]=="BFT"){
		$pounds_per_carton = 44;
	}
	*/
	
	$total_cartons = isset($_POST["total_cartons"]) ? $_POST["total_cartons"] : "0";
	$driver_notes = isset($_POST["driver_notes"]) ? $_POST["driver_notes"] : "";
	
	
	//$stop1 = retrieve_dbStops($id);
	$stop1 = new Stop($ndate."-".$area, $client_id, $client_type, $client_items, $driver_notes);
	insert_dbStops($stop1);
		
	$total_weight = $total_cartons * $pounds_per_carton;
		
	$item1 = "Meat:" . $total_weight * $meat_percent;
	$stop1->add_item($item1);
			
	$item2 = "Bakery:" . $total_weight * $bakery_percent;
	$stop1->add_item($item2);
				
	$item3 = "Dairy:" . $total_weight * $dairy_percent;
	$stop1->add_item($item3);
		
	$item4 = "Produce:" . $total_weight * $produce_percent;
	$stop1->add_item($item4);
		
	$item5 = "Dry Goods:" . $total_weight * $dry_goods_percent;
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
			   <b>1.</b> To insert the total number of cartons, tap the corresponding text box below.<br /><br />
			   <b>2.</b> Insert additional notes into the text box below if necessary.<br /><br />
			   <b>3.</b> When the values are entered, tap the "Submit" button at the bottom.<br /><br />
			   <b>4.</b> Check that the submitted values in the red box at the bottom of the screen are correct. If not, re-enter them.<br /><br /> 
			   <b>5.</b> After all values are submitted, tap the "Return to Route" button at the bottom.<br />
			</fieldset>
			
			<form method="post"?>
			<fieldset>
				<legend><b>Data Entry:</b></legend><br />
				<b>Total Cartons:</b> <input type = "text" name = "total_cartons" value = <?php echo $total_cartons?> />
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
					Total Cartons: <b>'.$total_cartons.'</b><br/><br/>
					Notes:'.$driver_notes.'
				</div>
				');
			}
			?>
			
			<br />
			<br />
			<br />
			
			<a href="<?php echo $path?>editRoute.php"><big>Return to Route</big></a>
			
			</div>
			<?PHP include('footer.inc');?>
		</div>
	</body>
</html>