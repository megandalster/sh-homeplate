<?php

/*
 *  Our copyright notice
 */

session_start();
session_cache_expire(30)
?>
<html>
<head>
<title>Reports</title>
<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>
<div id="container"><?php include('header.php');?>
<div id="content"><?php 
include_once('database/dbStops.php');
include_once('domain/Stop.php');
echo "<h4>Today is ".date('l F j, Y', time())."</h4>";
?>

<form method="post" action="">
<p>Area : <select name="report_area">
	<option value="">--all--</option>
	<option value="HHI">Hilton Head</option>
	<option value="SUN">Bluffton</option>
	<option value="BFT">Beaufort</option>
</select> &nbsp;&nbsp;Report Type : <select name="report_type">
	<option value="">All Stops</option>
	<option value="pickup">Donors Only</option>
	<option value="dropoff">Recepients Only</option>
	<option value="publixwalmart">Publix and Walmart Only</option>
</select> <br>

<fieldset><legend>Select report dates</legend>
<p><input type="radio" name="report_span" value="weekly" /> Last Week <br>
<input type="radio" name="report_span" value="monthly" />
Monthly&nbsp;&nbsp; <?php 

$months = array ("Jan", "Feb", "Mar", "Apr", "May", "Jun",
				 "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
$areas = array("SUN"=>"Bluffton","HHI"=>"Hilton Head","BFT"=>"Beaufort");

// month selector
echo '<select name="monthly_month">';
foreach($months as $month)
{
	echo '<option value="'.$month.'">'.$month.'</option>';
}
echo '</select>&nbsp;';

$current_time = getdate(time());

// year selector
echo '<select name="monthly_year">';
for($i = $current_time[year]; $i >= 1990; $i--)
{
	echo '<option value="'.$i.'">'.$i.'</option>';
}
echo '</select>';
?> <br>
<input type="radio" name="report_span" value="range" /> Date
Range&nbsp;&nbsp; <?php 

// starting month selector
echo '<select name="range_month_start">';
foreach($months as $month)
{
	echo '<option value="'.$month.'">'.$month.'</option>';
}
echo '</select>&nbsp;';

// starting day selector
echo '<select name="range_day_start">';
for($i = 1; $i <=31; $i++)
{
	echo '<option value="'.$i.'">'.$i.'</option>';
}
echo '</select>';

$current_time = getdate(time());

// starting year selector
echo '<select name="range_year_start">';
for($i = $current_time[year]; $i >= 1990; $i--)
{
	echo '<option value="'.$i.'">'.$i.'</option>';
}
echo '</select>&nbsp; to ';


// end month selector
echo '<select name="range_month_end">';
foreach($months as $month)
{
	echo '<option value="'.$month.'">'.$month.'</option>';
}
echo '</select>&nbsp;';

// end day selector
echo '<select name="range_day_end">';
for($i = 1; $i <=31; $i++)
{
	echo '<option value="'.$i.'">'.$i.'</option>';
}
echo '</select>';

$current_time = getdate(time());

// end year selector
echo '<select name="range_year_end">';
for($i = $current_time[year]; $i >= 1990; $i--)
{
	echo '<option value="'.$i.'">'.$i.'</option>';
}
echo '</select>';
?>

</fieldset>
</p>

<!-- submit button --> <br>
<input type="hidden" name="submitted" value="1"><input type="submit"
	name="Generate " value="Generate Report"></form>

<?php
if($_POST['submitted'])
{
	$header = array("Second Helpings Truck Weight Report for ");
	if ($_POST['report_area']!="") $header[] = $areas[$_POST['report_area']]." area, "; 
		else $header[] = " all areas, ";
	if ($_POST['report_type']=="publixwalmart") $header[] = " Publix and Walmart only ";
		else if ($_POST['report_type']=="pickup") $header[] = " donors only ";
		else if ($_POST['report_type']=="dropoff") $header[] = " recipients only ";
		else $header[] = " donors and recipients ";
	$header[] =  "<br>";
	if($_POST['report_span'] == "weekly")
	{
		$time = strtotime('monday this week') - 604800;
		$endTime = $time + 518400;

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		$header[] =  "Week of ".date('l F j, Y', $time);
	}

	else if($_POST['report_span'] == "monthly")
	{
		$month = $_POST['monthly_month'];
		$year = $_POST['monthly_year'];

		$time = strtotime($month.' 01, '.$year);
		$endTime = $time + 86400*(date("t",$time)-1);

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);
		
		$header[] =  date(' F Y', $time);
	}	
    else if($_POST['report_span'] == "range")
    {
		$time = strtotime($_POST['range_day_start'].$_POST['range_month_start'].$_POST['range_year_start']);
		$endTime = strtotime($_POST['range_day_end'].$_POST['range_month_end'].$_POST['range_year_end']);

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		$header[] =  date('F j, Y', $time) . " to " . date('F j, Y', $endTime);
		
	}
	else $header[] = "-- no dates selected --";
	echo "<hr /><br><b>";
	foreach ($header as $piece) echo $piece;
	echo "</b><br><br>";
	
  if ($_POST['report_span']!="" && $_POST['report_type']!="publixwalmart") {
	// get all stops from database for given area, report type, and date range
	$all_stops = getall_dbStops_between_dates($_POST['report_area'], $_POST['report_type'], $start_date, $end_date);

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

	echo '<table><tr>';

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
			echo "<td align='right'>".$pickups[$i]->get_total_weight()."</td><td></td>";
			$tw_pickups += $pickups[$i]->get_total_weight();
		}
		else if ($_POST['report_type']!="dropoff")
		{
			echo "<td></td><td></td><td></td>";
		}

		// cols 3 & 4 : recipient name and weight
		if($dropoffs[$i] != null)
		{
			echo "<td>".$dropoffs[$i]->get_client_id()."</td>";
			echo "<td align='right'>".$dropoffs[$i]->get_total_weight()."</td>";
			$tw_dropoffs += $dropoffs[$i]->get_total_weight();
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
	//$start = date('F j, Y',mktime(0,0,0,substr($start_date,3,2),substr($start_date,6,2),substr($start_date,0,2)));
	//$end = date('F j, Y',mktime(0,0,0,substr($end_date,3,2),substr($end_date,6,2),substr($end_date,0,2)));
	export_data($header, $pickups, $dropoffs,$tw_pickups,$tw_dropoffs);
	echo "<br>(This weight data has been exported. <br> Set your browser <a href='http://homeplate.secondhelpingslc.org/dataexport.csv'>here</a> to copy/paste it to your computer.)";
  }
  else if ($_POST['report_span']!="") 
  {
  	// get all stops from database for given area, report type, and date range
	$all_stops = getall_dbWalmartPublixStops_between_dates($_POST['report_area'], $start_date, $end_date);

	//split all_stops into 5 different arrays - one for each food type
	$food_types = array("Store", "Meat","Frozen","Bakery","Grocery","Dairy","Produce","Total");
	$row_totals = array();
	$food_type_totals = array("Totals",0,0,0,0,0,0,0);
	
	echo '<table><tr>';
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
	
	export_publixwalmart_data($header, $food_types, $row_totals, $food_type_totals);
	echo "<br>(This weight data has been exported. <br> Set your browser <a href='http://homeplate.secondhelpingslc.org/dataexport.csv'>here</a> to copy/paste it to your computer.)";
  	
  }
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
<table></table>
</div>
<?php include('footer.inc');?>
</div>
</body>
</html>
