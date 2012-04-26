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
		<?php if ($_GET['id']=="SUN") echo 'selected="selected"';?>>Sun
	City</option>
	<option value="BFT"
		<?php if ($_GET['id']=="BFT") echo 'selected="selected"';?>>Beaufort</option>
</select> &nbsp;&nbsp;Report Type : <select name="report_type">
	<option value="all">All Stops</option>
	<option value="don">Donors Only</option>
	<option value="rec">Recepients Only</option>
</select> <br>

<fieldset><legend>Report Span</legend> <input type="radio"
	name="report_span" value="weekly" /> Weekly <br>
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
?>


<br>
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

<!-- submit button -->
<br>
<input type="hidden" name="submitted" value="1"><input
	type="submit" name="Generate " value="Generate Report">

</form>

<?php 
if($_POST['submitted'])
{
	echo "<hr />";
		
	if($_POST['report_span'] == "weekly")
	{
		$time = strtotime('monday this week');
		
		
		echo "<br><p>";
		echo "<b><font color='orange'>Week of ".date('l F j, Y', $time). "</b></font>";
		echo "</p><br>";
		
		echo '<table cellspacing="10">';
		echo '<style type="text/css">';
		echo "td {padding: 10px 30px 10px 30px;}";
		echo "</style>";
		
		echo "<tr>";
		//echo "<td><b><font color='orange'>Week of:</b><br><u>". date('m/d/Y', $time) ."</u></font></td>";
		echo "<th></th>";
		echo "<td><b> Donors </b></td>";
		echo "<td><b> Recipients </b></td>";
		echo "</tr>";
	
		$weekday = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	
		// each iteration generates a row in the table
		foreach($weekday as $value)
		{			
			// start row
			echo "<tr>" ;
			
			// col 1 : day of week
			echo "<td><b>".$value."</b><br>";
			echo '<font size="1">' .date("F jS, Y", strtotime($value." this week")). "</font></td>" ;

			// col 2 : status (blank)
			echo "<td>not yet created</td>";
				
			// col 3 : view/edit
			echo "<td>null</td>";

			// end row
			echo "</tr>";
		}
		
		echo "</table>";
	}
	
	if($_POST['report_span'] == "monthly")
	{
		$month = $_POST['monthly_month'];
		$year = $_POST['monthly_year'];
		
		$time = strtotime("first day of ".$month.' '.$year);
		
		echo "<br><p>";
		echo "<b><font color='orange'>".date('F Y', $time). "</b></font>";
		echo "</p><br>";
		
		echo '<table cellspacing="10">';
		echo '<style type="text/css">';
		echo "td {padding: 10px 30px 10px 30px;}";
		echo "</style>";
		
		echo "<tr>";
		echo "<th></th>";
		echo "<td><b> Donors </b></td>";
		echo "<td><b> Recipients </b></td>";
		echo "</tr>";
	
		$maxtime = strtotime("+1 month", $time);
		
		// each iteration generates a row in the table
		for($time; $time < $maxtime; $time = strtotime("+1 day", $time))
		{			
			// start row
			if(date("D", $time) == "Sun")
				echo '<tr class="border-bottom">';
			else
				echo "<tr>" ;
			
			// col 1 : day of week
			echo "<td><b>".date("l",$time)."</b><br>";
			echo '<font size="1">' .date("F jS, Y", $time). "</font></td>" ;

			// col 2 : status (blank)
			echo "<td>none</td>";
				
			// col 3 : view/edit
			echo "<td>none</td>";
				
			// end row
			echo "</tr>";
			
		}
		
		echo "</table>";
	}
}
?>


	<?php include('footer.inc');?>
</table>
</div>
</div>
</body>
</html>