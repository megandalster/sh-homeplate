<?php

/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation recepients
 * (see <http://www.gnu.org/licenses/).
*/

session_start();
session_cache_expire(30)

?>
<html>
<head>
<title>Reports</title>
<link rel="stylesheet" href="styles.css" type="text/css" />
<link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery-ui.min.js"></script>

<script type="text/javascript">
	function setRadio(textInput, targetValue){
		if(this.value != ''){
			$radios = $("input[value='" + targetValue + "']");
			$radios[0].checked = true;
		}
	}
	
</script>

</head>
<body>
<div id="container"><?php include('header.php');?>
<div id="content"><?php 
include_once('database/dbStops.php');
include_once('domain/Stop.php');
echo "<h4>Today is ".date('l F j, Y', time())."</h4>";

 include_once('database/dbClients.php');
  include_once('database/dbDeliveryAreas.php');
      include_once('domain/DeliveryArea.php');
?>

<form method="post" action="">


<div style="float:left;">
Base : <select name="report_area">
	<option value="">--all--</option>
	<option value="HHI" <?php if($_POST['report_area'] == "HHI"){echo "selected='true'";} ?> >Hilton Head</option>
	<option value="SUN" <?php if($_POST['report_area'] == "SUN"){echo "selected='true'";} ?>>Bluffton</option>
	<option value="BFT" <?php if($_POST['report_area'] == "BFT"){echo "selected='true'";} ?>>Beaufort</option>
</select>
</div>
<div style="float:left;padding-left:8px;">
County : <select name="report_county">
	<option value="">--all--</option>
	<option value="Beaufort" <?php if($_POST['report_county'] == "Beaufort"){echo "selected='true'";} ?> >Beaufort</option>
	<option value="Hampton" <?php if($_POST['report_county'] == "Hampton"){echo "selected='true'";} ?>>Hampton</option>
	<option value="Jasper" <?php if($_POST['report_county'] == "Jasper"){echo "selected='true'";} ?>>Jasper</option>
</select>
</div>

<div style="float:left;padding-left:8px;">
Area : 
<?php 
 echo('<select name="deliveryAreaId">');
 echo ('<option value="">--all--</option>');
	
	$deliveryAreas = getall_dbDeliveryAreas();
	foreach($deliveryAreas as $deliveryArea){
		echo ('<option value="'); 
		echo($deliveryArea->get_deliveryAreaId()); 
		echo('"');
		
		
		//if ($person->get_deliveryAreaId()==$deliveryArea->get_deliveryAreaId()) 
		if($_POST['deliveryAreaId'] == $deliveryArea->get_deliveryAreaId())
			echo (' SELECTED');
		 echo('>'); echo($deliveryArea->get_deliveryAreaName()); echo('</option>');
	}
    
	echo('</select>');
?>
</div>

<div style="float:left;padding-left:8px;">
Chain : <?php
echo('<select name="chain_name">');
    	echo ('<option value=""></option>');
    	echo ('<option value="BiLo"');if ($_POST['chain_name']=='BiLo') echo (' SELECTED'); echo('>BiLo</option>');
		echo ('<option value="Food Lion"');if ($_POST['chain_name']=='Food Lion') echo (' SELECTED'); echo('>Food Lion</option>');
		echo ('<option value="Harris Teeter"');if ($_POST['chain_name']=='Harris Teeter') echo (' SELECTED'); echo('>Harris Teeter</option>');
		echo ('<option value="Piggly Wiggly"');if ($_POST['chain_name']=='Piggly Wiggly') echo (' SELECTED'); echo('>Piggly Wiggly</option>');
		echo ('<option value="Publix"');if ($_POST['chain_name']=='Publix') echo (' SELECTED'); echo('>Publix</option>');
		echo ('<option value="Target"');if ($_POST['chain_name']=='Target') echo (' SELECTED'); echo('>Target</option>');
		echo ('<option value="WalMart"');if ($_POST['chain_name']=='WalMart') echo (' SELECTED'); echo('>WalMart</option>');
    	echo('</select>');
	?>
</div>
<div style="float:left;padding-left:8px;">
Client :
<?php
	  if( !array_key_exists('client_name', $_POST) ) $client = ""; else $client = $_POST['client_name'];
						echo '<select name="client_name">';
							echo '<option value=""';            if ($client=="")            echo " SELECTED"; echo '>--all--</option>';
							
							  $allClients = getall_dbClients();
							foreach ($allClients as $clientRow) {
								echo '<option value="';            
								echo $clientRow->get_id();
								echo '"';
								if ($client==$clientRow->get_id())            
									echo ' SELECTED';
								echo '>';
								echo $clientRow->get_id();
								echo "</option>";
							}
                        echo '</select>';
				?>		
				
	</div>
	

	<div style="clear:both;"></div>
<div style="padding:10px 0px 0px 8px;">
Report Type : <select name="report_type">
	<option value="">All Stops</option>
	<option value="pickup" <?php if($_POST['report_type'] == "pickup"){echo "selected='true'";} ?> >Donors Only</option>
	<option value="dropoff" <?php if($_POST['report_type'] == "dropoff"){echo "selected='true'";} ?>>Recipients Only</option>
	<option value="publixwalmart" <?php if($_POST['report_type'] == "publixwalmart"){echo "selected='true'";} ?> >Breakdowns by Food Type</option>
	<option value="clientdetail" <?php if($_POST['report_type'] == "clientdetail"){echo "selected='true'";} ?> >Client Detail</option>
</select>
</div>


 <script>
$(function() {
$( "#dailyDatePicker" ).datepicker();
$( "#range_Start_DatePicker" ).datepicker();
$( "#range_End_DatePicker" ).datepicker();
});
</script>

<div style="clear:both;"></div>

<fieldset><legend>Select report dates</legend>
<p><input type="radio" name="report_span" value="monthly" <?php if($_POST['report_span'] == "monthly"){echo "checked='true'";} ?> /> Last Month <br>
<input type="radio" name="report_span" value="weekly" <?php if($_POST['report_span'] == "weekly"){echo "checked='true'";} ?> /> Last Week <br>
<input type="radio" name="report_span" value="daily" <?php if($_POST['report_span'] == "daily"){echo "checked='true'";} ?> />
Single Day&nbsp;&nbsp; 
<input type="text" onfocus="setRadio(this, 'daily');" onchange="setRadio(this, 'daily');" id="dailyDatePicker" name="dailyDatePicker" value="<?= $_POST['dailyDatePicker'] ?>" size="15" />
<br>
<input type="radio" name="report_span" value="range" <?php if($_POST['report_span'] == "range"){echo "checked='true'";} ?>  /> Date
Range&nbsp;&nbsp; 
<input type="text" onfocus="setRadio(this, 'range');" onchange="setRadio(this, 'range');" id="range_Start_DatePicker" name="range_Start_DatePicker" value="<?= $_POST['range_Start_DatePicker'] ?>" size="15" />
&nbsp; to 
<input type="text" onfocus="setRadio(this, 'range');" onchange="setRadio(this, 'range');" id="range_End_DatePicker" name="range_End_DatePicker" value="<?= $_POST['range_End_DatePicker'] ?>" size="15" />
</fieldset>


<!-- submit button --> <br>
<input type="hidden" name="submitted" value="1"><input type="submit"
	name="Generate " value="Generate Report"></form>

<?php
function pretty($date) {
    if ($date=="") return "";
    else 
        return substr($date,3,5)."-20".substr($date,0,2);
}
if($_POST['submitted'])
{
	$bases = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
	echo "<div id='dvReport'>";
	
	if ($_POST['report_type'] == "clientdetail") {
		$header = array("Second Helpings Client Detail Report on ".date('F j, Y', time()). " for ");
		if ($_POST['report_area']!="") $header[] = " base: ".$bases[$_POST['report_area']];
		else $header[] = " all bases ";
		if ($_POST['report_county']!="") $header[] = ", county: ".$_POST['report_county'];
		else $header[] = ", all counties ";
	}
	else {	
		$header = array("Second Helpings Truck Weight Report for ");
	if ($_POST['report_area']!="") $header[] = " base: ".$bases[$_POST['report_area']]; 
		else $header[] = " all bases ";
	if ($_POST['report_county']!="") $header[] = ", county: ".$_POST['report_county'];
		else $header[] = ", all counties ";
		
	if ($_POST['deliveryAreaId']!=""){
		$deliveryArea = retrieve_dbDeliveryAreas($_POST['deliveryAreaId']);
		$header[] =  ", delivery area: " . $deliveryArea->get_deliveryAreaName();
	}
		else $header[] = ", all delivery areas";
		
	if ($_POST['client_name']!="")
		$header[] = ",  client '".$_POST['client_name']."' only";
	else {
		if ($_POST['chain_name']!="") $header[] = ", chain: " .  $_POST['chain_name'];	
		if ($_POST['report_type']=="publixwalmart") $header[] = ", food type breakdowns";
			else if ($_POST['report_type']=="pickup") $header[] = ", donors only";
			else if ($_POST['report_type']=="dropoff" || $_POST['report_type']=="clientdetail") $header[] = ", recipients only";
			else $header[] = ", donors and recipients";
	}
	$header[] =  ".<br>";
	if($_POST['report_span'] == "monthly")
	{
		$time = strtotime('first day of last month');
		$endTime = strtotime('last day of last month');

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		$header[] =  "Month beginning ".date('F j, Y', $time) . " and ending " . date('F j, Y', $endTime);
	}
	else if($_POST['report_span'] == "weekly")
	{
		$time = strtotime('last monday', strtotime('tomorrow',time())) - 604800;
		$endTime = $time + 518400;

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		$header[] =  "Week beginning ".date('l F j, Y', $time);
	}

	else if($_POST['report_span'] == "daily")
	{
		$time = strtotime($_POST['dailyDatePicker']);
		$start_date = date('y-m-d', $time);
		$end_date = $start_date;
		
		$header[] =  date('l F j, Y', $time);
	}	
    else if($_POST['report_span'] == "range")
    {
		$time = strtotime($_POST['range_Start_DatePicker']);
		$endTime = strtotime($_POST['range_End_DatePicker']);
		
		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		$header[] =  date('F j, Y', $time) . " to " . date('F j, Y', $endTime);
		
	}
	else $header[] = "";
	}
	echo "<hr /><br><b>";
	foreach ($header as $piece) echo $piece;
	echo "</b><br><br>";
	
	if ($_POST['report_type']=="clientdetail"){
		echo '<table id="clientDetail">';
		echo "<tr><td><b>Recipient</b></td><td><b>LCFB</b></td><td><b>Charity Trkr</b></td><td><b>Survey Date</b></td><td><b>Visit Date</b></td>".
				"<td><b>Food Safe Date</b></td><td><b># Served</b></td>";
		echo "</tr>";
		 
		$allClients = getall_clients($_POST['report_area'], "recipient", "", "", "", "", $_POST['report_county']);
		$totalServed = 0;
		foreach ($allClients as $client) {
			$totalServed += $client->get_number_served();
			echo '<tr>';
			echo '<td>'.$client->get_id().'</td><td>'.$client->get_lcfb().'</td><td>'.$client->get_chartrkr().'</td><td align="right">'.
			pretty($client->get_survey_date()).'</td><td align="right">'.pretty($client->get_visit_date()).'</td>'.'<td align="right">'.
			pretty($client->get_foodsafe_date()).'</td><td align="right">'.$client->get_number_served().'</td>';
			echo "</tr>";
		}
		echo '<tr>';
		echo '<td><b>Total</b></td><td></td><td></td><td></td><td></td><td></td><td>'.$totalServed.'</td>';
		echo "</tr></table>";

	}
	
  	else if ($_POST['report_span']!="" && $_POST['report_type']!="publixwalmart") {
	// get all stops from database for given area, report type, and date range
	$all_stops = getall_dbStops_between_dates($_POST['report_area'], $_POST['report_type'], 
		$_POST['client_name'], $start_date, $end_date, $_POST['deliveryAreaId'], $_POST['chain_name'], $_POST['report_county']);

	//split all_stops into 2 different arrays - one for each
	$pickups = array();
	$dropoffs = array();

	// assign each stop by type to corresponding array
	foreach($all_stops as $stop)
	{
		if($stop->get_type() == "pickup")
		$pickups[] = $stop;
		else
		$dropoffs[] = $stop;
	}

	// keep track of total weights
	$tw_pickups  = 0;
	$tw_dropoffs = 0;

	echo '<table id="tblReport"><tr>';

	if ($_POST['report_type']!="dropoff") {
			echo "<tr><td><b>Donor</b></td>";
			echo "<td><b>Weight</b></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	}
	if ($_POST['report_type']!="pickup") {
		echo "<td><b>Recipient</b></td>";
		echo "<td><b>Weight</b></td>";
	}
	echo "</tr>";
	// iterator control
	$max = (count($pickups) > count($dropoffs)) ? count($pickups) : count($dropoffs);

	// each iteration generates a row in the table
	for($i = 0; $i < $max; ++$i)
	{
		echo "<tr>" ;
		// cols 1 & 2 : donor name and weight
		if($pickups[$i] != null)
		{
			echo "<td>".$pickups[$i]->get_client_id()."</td>";
			
			$totalWeight = $pickups[$i]->get_total_weight();
			
			if($totalWeight < 0){
				echo "<td align='right'>No Items</td><td></td>";
			}
			else{
				echo "<td align='right'>".$pickups[$i]->get_total_weight()."</td><td></td>";
				
				$pickUpWeight = $pickups[$i]->get_total_weight();
				if($pickUpWeight > 0)
					$tw_pickups += $pickUpWeight;
			}
			
		}
		else if ($_POST['report_type']!="dropoff")
		{
			echo "<td></td><td></td><td></td>";
		}

		// cols 3 & 4 : recipient name and weight
		if($dropoffs[$i] != null)
		{
			echo "<td>".$dropoffs[$i]->get_client_id()."</td>";
			$dropOffTotalWeight = $dropoffs[$i]->get_total_weight();
			
			if($dropOffTotalWeight > 0){
				$tw_dropoffs += $dropoffs[$i]->get_total_weight();
				echo "<td align='right'>". $dropOffTotalWeight ."</td>";
			}
			else{
				echo "<td align='right'>No Items</td>";
			}
		}
		else if ($_POST['report_type']!="pickup")
		{
			echo "<td></td><td></td>";
		}
		echo "</tr>";
	}

	// total weight row
	echo "<tr>";
	if ($_POST['report_type']!="dropoff")
		echo "<td><b>Totals</b></td><td align='right'>".$tw_pickups."</td><td></td>";
	if ($_POST['report_type']!="pickup")
		echo "<td><b>Totals</b></td><td align='right'>".$tw_dropoffs."</td>";
	echo "</tr>";
	echo "</table>";

  }
  
  else if ($_POST['report_span']!="") 
  {
  	// get all stops from database for given area, report type, and date range
	$all_stops = getall_dbWalmartPublixStops_between_dates($_POST['report_area'], 
			$_POST['client_name'], $start_date, $end_date, $_POST['deliveryAreaId'], $_POST['chain_name'], $_POST['report_county']);

	//split all_stops into 5 different arrays - one for each food type
	$food_types = array("Store", "Meat","Deli","Bakery","Grocery","Dairy","Produce","Total");
	$row_totals = array();
	$food_type_totals = array("Totals",0,0,0,0,0,0,0);
	
	echo '<table  id="tblReport"><tr>';
	foreach($food_types as $food_type)
		echo "<td>".$food_type."</td>";
	echo "</tr>";
	
	$all_stops[] = new Stop("","","","",""); // add a dummy stop at the end
	$prev_stop = new Stop("","","","","");   // and at the beginning
	$prev_stop_totals = array(0,0,0,0,0,0,0);
	foreach ($all_stops as $a_stop) {  
		if ($prev_stop->get_client_id() == $a_stop->get_client_id()) {
			if ($a_stop->get_client_id()==null) // we're outta here
				break;
			// accumulate totals
			$i=0;
			foreach ($a_stop->get_items() as $an_item) {
				$item_weight = substr($an_item,strpos($an_item,":")+1);
				if($item_weight > 0)
					$prev_stop_totals[$i] += $item_weight;
				$i++;
			}
			$prev_stop_totals[6] += $a_stop->get_total_weight();
			continue;
		}
		else if ($prev_stop->get_client_id()!="" || $a_stop->get_client_id()=="") { // display prev_stop
			echo "<tr>" ;
			echo "<td>".$prev_stop->get_client_id()."</td>";
			$export_row = array($prev_stop->get_client_id());
			$i=0;
			foreach ($prev_stop->get_items() as $an_item) {
				$item_weight = substr($an_item,strpos($an_item,":")+1);
				echo "<td align='right'>".$prev_stop_totals[$i]."</td>";
				$export_row[] = $prev_stop_totals[$i];
				$food_type_totals[$i+1] += $prev_stop_totals[$i];
				$i++;
			}
			echo "<td align='right'>".$prev_stop_totals[6]."</td>";
			$export_row[] = $prev_stop_totals[6];
			$row_totals[] = $export_row;
			$food_type_totals[7] += $prev_stop_totals[6];
			echo "</tr>";		
		}
		$prev_stop = clone($a_stop);  // reinitialize prev_stop
		$i=0;
		foreach ($a_stop->get_items() as $an_item) {
				$item_weight = substr($an_item,strpos($an_item,":")+1);
				if($item_weight < 0)
					$item_weight = 0;
				
				$prev_stop_totals[$i] = $item_weight;
				$i++;
		}
		$prev_stop_totals[6] = $a_stop->get_total_weight();	
	}

	// total weight row
	echo "<tr>";
	foreach ($food_type_totals as $food_type_total)
		echo "<td align='right'>".$food_type_total."</td>";
	echo "</tr>";
	echo "</table>";
  }
  echo '<div style="padding:10px;">
	<input type="button" value="Print List" onclick="showPrintWindow();" />
	</div>';
  
}

function export_publixwalmart_data($header, $food_types, $row_totals, $food_type_totals ) {
	$filename = "dataexport.csv";
	$handle = fopen($filename, "w");
	fputcsv($handle, $header);
	fputcsv($handle, $food_types);
	$max = (count($pickups) > count($dropoffs)) ? count($pickups) : count($dropoffs);
	foreach ($row_totals as $row_total) {
		fputcsv($handle, $row_total);
	}
	fputcsv($handle, $food_type_totals);
	fclose($handle);	
}

function export_data($header,$pickups,$dropoffs,$twp,$twd) {
	$filename = "dataexport.csv";
	$handle = fopen($filename, "w");
	
	fputcsv($handle, $header);
	$max = (count($pickups) > count($dropoffs)) ? count($pickups) : count($dropoffs);
	for ($i=0; $i<$max; ++$i) {
		if($pickups[$i] != null)
			$myArray = array($pickups[$i]->get_client_id(),$pickups[$i]->get_total_weight());
		else 
			$myArray = array("","");
		if($dropoffs[$i] != null){
			$myArray[] = $dropoffs[$i]->get_client_id();
			$myArray[] = $dropoffs[$i]->get_total_weight();
		}
		else {
			$myArray[] = ""; $myArray[] = "";
		}
		fputcsv($handle, $myArray);
	}
	$myArray = array("Totals", $twp, "Totals", $twd);
	fputcsv($handle, $myArray);
	fclose($handle);	
}

?>
<script type="text/javascript">
			function showPrintWindow(){
				
				var printWin = window.open('', 'winReport', 'width=690px;height:600px;resizable=1');
				var html = $("#tblReport").parent().html();
				
				printWin.document.open();
				printWin.document.write("<html><head><title>Print Donor/Recipients</title><style>#tblReport td {border:1px solid black;}</style></head><body>");
				printWin.document.write(html);
				printWin.document.write('<scr');
				printWin.document.write('ipt>');
				printWin.document.writeln('setTimeout("window.print()", 200);');
				printWin.document.write('</scr');
				printWin.document.write('ipt>');
				printWin.document.write('</body>');
				printWin.document.write('</ht>');   
				printWin.document.write('<ml>');   
				printWin.document.close();
			}
</script>
</div>
</div>
<?php include('footer.inc');?>		
</body>
</html>
