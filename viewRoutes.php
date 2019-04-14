<?php

/*
 *  Our copyright notice
 */
 
	session_start();
	session_cache_expire(30)
?>
<html>
	<head>
		<title>
			Weekly Route View
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
		<link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			$(function() {
			$( "#weekDatePicker" ).datepicker();
			});
		</script>		
	</head>
	<body>
		<div id="container">
			<?php include('header.php');?>
			<div id="content">
				<?php 
					include_once('database/dbRoutes.php');
					include_once('domain/Route.php');
					include_once('database/dbVolunteers.php');
					include_once('domain/Volunteer.php');
					
				$areas = array("HHI"=>"Hilton Head", "SUN"=> "Bluffton", "BFT" => "Beaufort");
				$thisArea = $_GET['area'];
				
			    $today = date('y-m-d');
			    $thisDay = $_GET['date'];
			
				$todayUTC = time();
				$thisUTC = mktime(0,0,0,substr($thisDay,3,2),substr($thisDay,6,2),substr($thisDay,0,2));
				$nextweekUTC = $thisUTC + 604800;
				$prevweekUTC = $thisUTC - 604800;
				?>
<form method="post" action="">
				<?php
				$mondaythisweek = strtotime('last monday', strtotime('tomorrow',$thisUTC));
				echo "<br><<<a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$prevweekUTC).">Previous&nbsp;&nbsp;&nbsp;&nbsp;</a>";
				echo "<strong>  Week of ".date('F j, Y', $mondaythisweek)."</strong>  ";
				echo "<a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$nextweekUTC).">&nbsp;&nbsp;&nbsp;&nbsp;Next>> </a>";
				?>
				<strong>&nbsp;&nbsp;&nbsp;&nbsp;(Another week: </strong>
				<input type="text" onfocus="setRadio(this, 'weekDatePicker');" onchange="setRadio(this, 'weekDatePicker');" id="weekDatePicker" name="weekDatePicker" value="<?= $_POST['weekDatePicker'] ?>" size="10" />
    			<input type="hidden" name="submitted" value="1"><input type="submit" name="submit" value="SUBMIT and wait 5 sec...">
    			<?php 
                if ($_POST['submitted'])
                    $go_date = substr($_POST['weekDatePicker'],8,2).'-'.substr($_POST['weekDatePicker'],0,2).'-'.substr($_POST['weekDatePicker'],3,2);
                else $go_date = date('y-m-d',$thisUTC);
                echo '<a href=viewRoutes.php?area='.$thisArea.'&date='.$go_date.'>... GO)</a>'; 
                ?>
</form>
				<?php 
				echo "<strong>".$areas[$thisArea]."</strong>";
				?>
				 Daily Route Summary  (View other bases:
				<?php 
				foreach ($areas as $area=>$areaName) {
				   if ($thisArea!=$area)
				   	  echo "<a href=viewRoutes.php?area=".$area."&date=".$thisDay."> $areaName</a>";	
				}
				echo")<br><br>";
				?>

<table><tr>
</tr>
	<tr>
		<td> <b> Route * </b> </td>
		<td align='right'> <b> Crew </b> </td>
		<td align='right'> <b> P/U </b> </td>
		<td align='right'> <b> D/O </b> </td>
		
		<!--
		<td> <b> Data received from truck? </b> </td>
		<td> <b> Tablet id </b> </td>
		-->

		<td> <b> P/U Weight </b> </td>
		<td> <b> D/O Weight </b> </td>
		<td> <b> Balance </b> </td>
		<td> <b> Start-end time </b> </td>
	</tr>
	
	<?php
	
	$weekdays = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	
	// each iteration generates a row in the table
	$dayUTC = $mondaythisweek;
	$route = array();
	$days = array();
	foreach ($weekdays as $weekday)
	{
		$routeID = date('y-m-d', $dayUTC).'-'.$_GET[area];
		$route[$weekday] = get_route($routeID);
        if (!$route[$weekday] && $thisUTC <= $todayUTC + 1209600) {  // autogenerate routes 2 weeks out from today 
	        $day = date("D",mktime(0,0,0,substr($routeID,3,2),substr($routeID,6,2),substr($routeID,0,2)));
			$team_captains = get_team_captains(substr($routeID,9), $day);
			if (sizeof($team_captains)==0)
				$team_captain = "Lisa8437152491";   // force a day captain if there are none
			else $team_captain = $team_captains[0]->get_id();
			$route[$weekday] = make_new_route($routeID,$team_captain);
        }
		// start row
		echo "<tr>" ;
		
		// col 1 : day of week
		$days[$weekday] = $weekday." ". date('M j', $dayUTC);
		echo "<td>"."<a href=editRoute.php?routeID=".$routeID.">".$days[$weekday]."</a></td>" ;
		
		// if route exists, generate this set of cols
		if($route[$weekday] != NULL)
		{	
			//col 2 : drivers
			$volunteers = $route[$weekday]->get_drivers();
			echo "<td align='right'>".sizeof($volunteers)."</td>";
			
			//col 3 : pickups
			echo "<td align='right'>".$route[$weekday]->get_num_pickups()."</td>";
			
			//col 4 : dropoffs
			echo "<td align='right'>".$route[$weekday]->get_num_dropoffs()."</td>";

		  	//col 5 : status Changed to P/U and D/O weights
			
			$pickUpWeight = 0;		
			echo "<td align='center'>";
			foreach ($route[$weekday]->get_pickup_stops() as $pickup_id) {
				$client_id = substr($pickup_id,12);

				//echo "routeID.client_id:". $routeID.$client_id . "<br />";
				$theStop = retrieve_dbStops($routeID.$client_id);
				$stopWeight = $theStop->get_total_weight();
				
				if($stopWeight > 0){
					$pickUpWeight += $theStop->get_total_weight();
				}
				
			//	echo $theStop->get_total_weight() . "<br />";
			}
			echo $pickUpWeight ."</td>";
			
			$dropWeight = 0;			
			foreach ($route[$weekday]->get_dropoff_stops() as $dropoff_id) {
				$client_id = substr($dropoff_id,12);

				$theStop = retrieve_dbStops($routeID.$client_id);
				$stopWeight = $theStop->get_total_weight();
				if($stopWeight > 0){
				$dropWeight += $theStop->get_total_weight();
				}
			}
			echo "<td align='center'>".$dropWeight ."</td>";
			echo "<td align='center'>".($pickUpWeight - $dropWeight) ."</td>";
			
			if ($route[$weekday]->get_status()=="completed") {
			
				$first_tablet = $route[$weekday]->get_notes();
				$j = strpos($first_tablet,","); // see if there's more than one tablet checking in
				//echo "<td align='center'>"."yes"."</td>";
				while ($j > 0) {
					$this_tablet = substr($first_tablet,0,$j);
					$first_tablet = substr($first_tablet,$j+1);
					$i = strpos($this_tablet,";");
					$times = substr($this_tablet,$i+1);
					//echo "<td>".substr($this_tablet,0,$i)."</td><td>".$times."</td>";
					//echo "</tr><tr><td></td><td></td><td></td><td></td><td></td>";
					$j = strpos($first_tablet,",");
				}
				$i = strpos($first_tablet,";");
				$times = substr($first_tablet,$i+1);
				//echo "<td>".substr($first_tablet,0,$i)."</td><td>".$times."</td>";
				echo "<td>".$times."</td>";
		  }
		  else echo "<td></td>";
			
		}
		// else, use defaults
		else
		{	
			// col 2 : driver (blank)
			echo "<td></td>";
			
			// col 3 : pickups (blank)
			echo "<td></td>";
			
			// col 4 : dropoffs (blank)
			echo "<td></td>";
			
			//col 5 : status
			echo "<td></td><td></td><td></td><td></td>";
		}
		
		// end row
		echo "</tr>";
		$dayUTC += 86400;
	}
	?>
</table>	
<br><strong>*</strong> View any route's weights by clicking its date.
<?php
echo "<br><br><br><strong>$areas[$thisArea]</strong> Weekly Route Schedule";
echo " for  Week of <strong>".date('F j, Y', $mondaythisweek)."</strong><br><br>  ";
		//	show_daily_schedule($areas[$thisArea],$mondaythisweek);	
?>	
<table>
<tr>
<?php 
foreach ($weekdays as $weekday)
    echo "<td><strong>". $days[$weekday] . "</strong></td>";
?>
</tr>
<tr>
<?php 
if ($route[$weekday]) {
foreach ($weekdays as $weekday) {
    echo "<td valign='top'><table>";
    $pickups = $route[$weekday]->get_pickup_stops();
    echo "<tr><td><strong>PICK UPS</strong></td></tr>";
    foreach ($pickups as $pickup)
        echo "<tr><td>".substr($pickup,12)."</td></tr>";
    
    $dropoffs = $route[$weekday]->get_dropoff_stops();
    echo "<tr><td><strong>DROP OFFS</strong></td></tr>";
    foreach ($dropoffs as $dropoff)
        echo "<tr><td>".substr($dropoff,12)."</td></tr>";
        
    echo "</table></td>";
}
}
?>
</tr>
</table>
			</div>
			<?php include('footer.inc');?>
		</div>
	</body>
</html>