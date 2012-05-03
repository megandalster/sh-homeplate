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

$time = time();
echo "<h4>Today is ".date('l F j, Y', $time)."</h4>";
?>

<form method="post" action="">
<p>Area : <select name="report_area">
	<option value="HHI"
	<?php if ($_GET['id']=="HHI") echo 'selected="selected"';?>>Hilton
	Head</option>
	<option value="SUN"
	<?php if ($_GET['id']=="SUN") echo 'selected="selected"';?>>Sun City</option>
	<option value="BFT"
	<?php if ($_GET['id']=="BFT") echo 'selected="selected"';?>>Beaufort</option>
</select> &nbsp;&nbsp;Report Type : <select name="report_type">
	<option value="all">All Stops</option>
	<option value="don">Donors Only</option>
	<option value="rec">Recepients Only</option>
</select> <br>

<fieldset><legend>Choose report dates</legend>
<p><input type="radio" name="report_span" value="weekly" /> Weekly <br>
<input type="radio" name="report_span" value="monthly" />
Monthly&nbsp;&nbsp; <?php 

$months = array ("Jan", "Feb", "Mar", "Apr", "May", "Jun",
				 "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

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
	echo "<hr />";

	if($_POST['report_span'] == "weekly")
	{
		$time = strtotime('monday this week');
		$endTime = strtotime("+1 week", $time);

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		echo "<br><p>";
		echo "<b><font color='orange'>Week of ".date('l F j, Y', $time). "</b></font>";
		echo "</p><br>";
	}

	if($_POST['report_span'] == "monthly")
	{
		$month = $_POST['monthly_month'];
		$year = $_POST['monthly_year'];

		$time = strtotime($month.' 01, '.$year);
		$endTime = strtotime("last day of ".$month.' '.$year);

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		echo "<br><p>";
		echo "<b><font color='orange'>".date(' F Y', $time). "</b></font>";
		echo "</p><br>";
	}

	if($_POST['report_span'] == "range")
	{
		$time = strtotime($_POST['range_day_start'].$_POST['range_month_start'].$_POST['range_year_start']);
		$endTime = strtotime($_POST['range_day_end'].$_POST['range_month_end'].$_POST['range_year_end']);

		$start_date = date('y-m-d', $time);
		$end_date = date('y-m-d', $endTime);

		echo "<br><p>";

		if($endTime >= $time)
		{
			echo "<b><font color='orange'>".date('F j, Y', $time);
			echo "&nbsp;&nbsp;->&nbsp;&nbsp;".date('F j, Y', $endTime)."</b></font>";
		}
		else
		{
			echo "<b><font color='red'>Invalid date range! </b></font>";
			echo "Starting date should be before or equal to end date";
		}

		echo "</p><br>";
	}

	// get list of all stops from database for date range
	$all_stops = getall_dbStops_between_dates($start_date, $end_date);

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

	echo '<table cellspacing="10">';
	echo '<style type="text/css">';
	echo "td {padding: 10px 30px 10px 30px;}";
	echo "</style>";

	echo "<tr>";

	echo "<th>Donor</th>";
	echo "<th>Weight</th>";
	echo "<th>Recipient</th>";
	echo "<th>Weight</th>";

	echo "</tr>";

	// iterator control
	$max = (count($pickups) > count($dropoffs)) ? count($pickups) : count($dropoffs);

	// each iteration generates a row in the table
	for($i = 0; $i < $max; ++$i)
	{
		//echo '<font size="1">' .date("F jS, Y", strtotime($value." this week")). "</font></td>" ;

		// start row
		echo "<tr>" ;

		// cols 1 & 2 : donor name and weight
		if($pickups[$i] != null)
		{
			echo "<td>".$pickups[$i]->get_client_id()."</td>";
			echo "<td>".$pickups[$i]->get_total_weight()."</td>";
			$tw_pickups += $pickups[$i]->get_total_weight();
		}
		else
		{
			echo "<td></td>";
			echo "<td></td>";
		}

		// cols 3 & 4 : recipient name and weight
		if($dropoffs[$i] != null)
		{
			echo "<td>".$dropoffs[$i]->get_client_id()."</td>";
			echo "<td>".$dropoffs[$i]->get_total_weight()."</td>";
			$tw_dropoffs += $dropoffs[$i]->get_total_weight();
		}
		else
		{
			echo "<td></td>";
			echo "<td></td>";
		}
	}

	// total weight row
	echo "<tr>";

	echo "<td><b>Total (Donors)</b></td>";
	echo "<td>".$tw_pickups."</td>";
	echo "<td><b>Total (Recipients)</b></td>";
	echo "<td>".$tw_dropoffs."</td>";

	echo "</tr>";

	echo "</table>";
}

?> <?php include('footer.inc');?>
<table></table>
</div>
</div>
</body>
</html>
