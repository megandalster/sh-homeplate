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
	$driver_notes = "";
	
	$stop1 = new Stop($ndate."-".$area, $client_id, $client_type, $client_items, $driver_notes);
	insert_dbStops($stop1);
	
	function init_post($value){
		if(!defined($value)){
			$value = 0;
			return $value;
		}	
		return $_POST[$value];
	}	
	function note_post($value){
		if(!defined($value)){
			$value = "";
			return $value;
		}	
		return $_POST[$value];
	}
	function update_stop($theStop){
		
		$item1 = "Meat:" . init_post("meat_weight") . ":" . init_post("meat_lboxes") . ":" .
			init_post("meat_sboxes") . ":" . init_post("meat_trays") . ":" . init_post("meat_bags");
		$theStop->add_item($item1);
				
		$item2 = "Bakery:" . init_post("bakery_weight") . ":" . init_post("bakery_lboxes") . ":" .
			init_post("bakery_sboxes") . ":" . init_post("bakery_trays") . ":" . init_post("bakery_bags");
		$theStop->add_item($item2);
				
		$item3 = "Dairy:" . init_post("dairy_weight") . ":" . init_post("dairy_lboxes") . ":" .
			init_post("dairy_sboxes") . ":" . init_post("dairy_trays") . ":" . init_post("dairy_bags");
		$theStop->add_item($item3);
		
		$item4 = "Produce:" . init_post("produce_weight") . ":" . init_post("produce_lboxes") . ":" .
			init_post("produce_sboxes") . ":" . init_post("produce_trays") . ":" . init_post("produce_bags");
		$theStop->add_item($item4);
		
		$item5 = "Other:" . init_post("other_weight") . ":" . init_post("other_lboxes") . ":" .
			init_post("other_sboxes") . ":" . init_post("other_trays") . ":" . init_post("other_bags");
		$theStop->add_item($item5);
		
		$theStop->set_notes(note_post("driver_notes"));
		
		update_dbStops($theStop);
	}
	
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
			   3. When all values are entered, tap the "Submit" button at the bottom.<br />
			   4. After all values are submitted, tap the "Return to Route" button at the bottom.
			</p>
			
			<form action="viewStop.php" method="post"?>
			<table border = "1" cellpadding = "5">
				<tr>
					<td><b>Type</b></td>
					<td>Weight (lbs.)</td>
					<td>Large Boxes</td>
					<td>Small Boxes</td>
					<td>Trays</td>
					<td>Bags</td>
					
				</tr>
				<tr>
					<td><b>Meat</b></td>
					<td><input type="text" name="meat_weight" value=<?php echo(init_post("meat_weight"))?> /></td>
					<td><input type="text" name="meat_lboxes" value=<?php echo(init_post("meat_lboxes"))?> /></td>
					<td><input type="text" name="meat_sboxes" value=<?php echo(init_post("meat_sboxes"))?> /></td>
					<td><input type="text" name="meat_trays" value=<?php echo(init_post("meat_trays"))?> /></td>
					<td><input type="text" name="meat_bags" value=<?php echo(init_post("meat_bags"))?> /></td>
					
				</tr>
				<tr>
					<td><b>Bakery</b></td>
					<td><input type="text" name="bakery_weight" value=<?php echo(init_post("bakery_weight"))?> /></td>
					<td><input type="text" name="bakery_lboxes" value=<?php echo(init_post("bakery_lboxes"))?> /></td>
					<td><input type="text" name="bakery_sboxes" value=<?php echo(init_post("bakery_sboxes"))?> /></td>
					<td><input type="text" name="bakery_trays" value=<?php echo(init_post("bakery_trays"))?> /></td>
					<td><input type="text" name="bakery_bags" value=<?php echo(init_post("bakery_bags"))?> /></td>
					
				</tr>
				<tr>
					<td><b>Dairy</b></td>
					<td><input type="text" name="dairy_weight" value=<?php echo(init_post("dairy_weight"))?> /></td>
					<td><input type="text" name="dairy_lboxes" value=<?php echo(init_post("dairy_lboxes"))?> /></td>
					<td><input type="text" name="dairy_sboxes" value=<?php echo(init_post("dairy_sboxes"))?> /></td>
					<td><input type="text" name="dairy_trays" value=<?php echo(init_post("dairy_trays"))?> /></td>
					<td><input type="text" name="dairy_bags" value=<?php echo(init_post("dairy_bags"))?> /></td>
					
				</tr>
				<tr>
					<td><b>Produce</b></td>
					<td><input type="text" name="produce_weight" value=<?php echo(init_post("produce_weight"))?> /></td>
					<td><input type="text" name="produce_lboxes" value=<?php echo(init_post("produce_lboxes"))?> /></td>
					<td><input type="text" name="produce_sboxes" value=<?php echo(init_post("produce_sboxes"))?> /></td>
					<td><input type="text" name="produce_trays" value=<?php echo(init_post("produce_trays"))?> /></td>
					<td><input type="text" name="produce_bags" value=<?php echo(init_post("produce_bags"))?> /></td>
					
				</tr>
				<tr>
					<td><b>Other</b></td>
					<td><input type="text" name="other_weight" value=<?php echo(init_post("other_weight"))?> /></td>
					<td><input type="text" name="other_lboxes" value=<?php echo(init_post("other_lboxes"))?> /></td>
					<td><input type="text" name="other_sboxes" value=<?php echo(init_post("other_sboxes"))?> /></td>
					<td><input type="text" name="other_trays" value=<?php echo(init_post("other_trays"))?> /></td>
					<td><input type="text" name="other_bags" value=<?php echo(init_post("other_bags"))?> /></td>
					
				</tr>
				<tr>
					<td><b>Total</b></td>
					<td><input type="text" name="total_weight" readonly="readonly" 
								value=<?php echo(init_post("meat_weight") + init_post("bakery_weight") + 
								init_post("dairy_weight") + init_post("produce_weight") +
								init_post("other_weight"))?> /></td>
					<td><input type="text" name="total_lboxes" readonly="readonly" 
								value=<?php echo(init_post("meat_lboxes") + init_post("bakery_lboxes") + 
								init_post("dairy_lboxes") + init_post("produce_lboxes") +
								init_post("other_lboxes"))?> /></td>
					<td><input type="text" name="total_sboxes" readonly="readonly" 
								value=<?php echo(init_post("meat_sboxes") + init_post("bakery_sboxes") + 
								init_post("dairy_sboxes") + init_post("produce_sboxes") +
								init_post("other_sboxes"))?> /></td>
					<td><input type="text" name="total_trays" readonly="readonly" 
								value=<?php echo(init_post("meat_trays") + init_post("bakery_trays") + 
								init_post("dairy_trays") + init_post("produce_trays") +
								init_post("other_trays"))?> /></td>
					<td><input type="text" name="total_bags" readonly="readonly" 
								value=<?php echo(init_post("meat_bags") + init_post("bakery_bags") + 
								init_post("dairy_bags") + init_post("produce_bags") +
								init_post("other_bags"))?> /></td>
				</tr>	
			</table>	
				<br />
			<p>Enter any additional by tapping the text box below:</p>
			<textarea rows="10" cols="50" name="driver_notes"></textarea>
				<br />
				<br />
			<input type="submit" value="Submit" onclick=<?php update_stop($stop1)?> />
			</form>
			
			<br />
			
			<button type="submit" onclick="">Return to Route</button>
			
			</div>
			<?PHP include('footer.inc');?>
		</div>
	</body>
</html>