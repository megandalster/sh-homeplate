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
			   1. To insert a value, tap the corresponding cell in the table below.<br />
			   2. Insert additional notes into the text box below the table if necessary.<br />
			   3. When the values are entered, tap the "Submit" button at the bottom.<br />
			   4. After all values are submitted, tap the "Return to Route" button at the bottom.
			</p>
			
			<form method="post"?>
			<table border = "1" cellpadding = "5">
				<tr>
					<td><b>Type</b></td>
					<td>Weight (lbs.)</td>
					
				</tr>
				<tr>
					<td><b>Meat</b></td>
					<td><input type="text" name="meat_weight" value= <?php echo $meat_weight?> /></td>
					
				</tr>
				<tr>
					<td><b>Bakery</b></td>
					<td><input type="text" name="bakery_weight" value= <?php echo $bakery_weight?> /></td>
					
				</tr>
				<tr>
					<td><b>Dairy</b></td>
					<td><input type="text" name="dairy_weight" value= <?php echo $dairy_weight?> /></td>
					
				</tr>
				<tr>
					<td><b>Produce</b></td>
					<td><input type="text" name="produce_weight" value= <?php echo $produce_weight?> /></td>
					
				</tr>
				<tr>
					<td><b>Grocery</b></td>
					<td><input type="text" name="grocery_weight" value= <?php echo $grocery_weight?> /></td>
					
					
				</tr>
				<tr>
					<td><b>Total</b></td>
					<td><input type="text" name="total_weight" readonly="readonly" 
								value=<?php echo($meat_weight + $bakery_weight + $dairy_weight + 
								$produce_weight + $grocery_weight)?> /></td>
				</tr>	
			</table>	
				<br />
			<p>Enter any additional notes by tapping the text box below:</p>
			<textarea rows="10" cols="50" name="driver_notes" ></textarea>
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