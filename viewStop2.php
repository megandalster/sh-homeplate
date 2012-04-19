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
	
	
	// $stop1 = retrieve_dbStops($id);
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
	</head>
	<body>
		<div id="container">
			<?php include('header.php');?>
			<div id="content">
  
			<p><big><?php echo($client_id)?></big></p>
			
			<p>Route: <?php echo($area)?><br />
			   Driver: <?php // echo($volunteer->get_first_name()." ".$volunteer->get_last_name())?>
			   			Nick Wetzel<br />
			   Date: <?php echo($date)?></p>
			   
			<p>Instructions:<br /><br />
			   1. To insert the total number of cartons, tap the corresponding text box below.<br />
			   2. Insert additional notes into the text box below if necessary.<br />
			   3. When the values are entered, tap the "Submit" button at the bottom.<br />
			   4. After all values are submitted, tap the "Return to Route" button at the bottom.
			</p>
			
			<form method="post"?>
				Total Cartons: <input type = "text" name = "total_cartons" value = <?php echo $total_cartons?> />
				<br />
			<p>Enter any additional notes by tapping the text box below:</p>
			
			<textarea rows="10" cols="50" name="driver_notes"></textarea>
				<br />
				<br />
			<input type="submit" value="Submit" />
			</form>
				<br />
			<button type="submit" onclick="">Return to Route</button>
			
			</div>
			<?PHP include('footer.inc');?>
		</div>
	</body>
</html>