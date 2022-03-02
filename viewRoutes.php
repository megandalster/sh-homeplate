<?php

/*
 *  Our copyright noticerouteid.clientid =
 */

session_start();
////session_cache_expire(30)

if (!array_key_exists('weekDatePicker',$_POST)) {
    $_POST['weekDatePicker'] = '';
}

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
				echo "<br><a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$prevweekUTC).">Previous&nbsp;&nbsp;&nbsp;&nbsp;</a>";
				echo "<strong>  Week of ".date('F j, Y', $mondaythisweek)."</strong>  ";
				echo "<a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$nextweekUTC).">&nbsp;&nbsp;&nbsp;&nbsp;Next>> </a>";
				?>
				<strong>&nbsp;&nbsp;&nbsp;&nbsp;(Another week: </strong>
				<input type="text" onfocus="setRadio(this, 'weekDatePicker');" onchange="setRadio(this, 'weekDatePicker');" id="weekDatePicker" name="weekDatePicker" value="<?= $_POST['weekDatePicker'] ?>" size="10" />
    			<input type="hidden" name="submitted" value="1"><input type="submit" name="submit" value="SUBMIT and wait 5 sec...">
    			<?php 
                if (array_key_exists('submitted',$_POST))
                    $go_date = substr($_POST['weekDatePicker'],8,2).'-'.substr($_POST['weekDatePicker'],0,2).'-'.substr($_POST['weekDatePicker'],3,2);
                else $go_date = date('y-m-d',$thisUTC);
                echo '<a href=viewRoutes.php?area='.$thisArea.'&date='.$go_date.'>... GO)</a>'; 
                ?>
</form>
				<?php 
				echo "<strong>".$areas[$thisArea]."</strong>";
				?>
				 Daily Route Status Summary  (View other bases:
				<?php 
				$count=1;
				foreach ($areas as $area=>$areaName) {
				    if ($thisArea!=$area) {
				   	  echo "<a href=viewRoutes.php?area=".$area."&date=".$thisDay."> $areaName</a>";
				   	  if ($count==1) {echo ", "; $count++;}
				    }
				}
				echo")<br><br>";
				?>

<table><tr>
</tr>
	<tr>
		<td> <b> Route * </b> </td>
		<td align='right'> <b> Received </b> </td>
		<td align='right'> <b> Delivered </b> </td>
		<td align='right'> <b> Balance </b> </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<b>Entered by </b> </td>
	</tr>
	
	<?php
	
	$weekdays = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	
	// each iteration generates a row in the table
	$dayUTC = $mondaythisweek;
	$route = array();
	$days = array();
	$pickups = array();
	$dropoffs = array();
	foreach ($weekdays as $weekday)
	{
		$routeID = date('y-m-d', $dayUTC).'-'.$_GET['area'];
		$route[$weekday] = get_route($routeID);
        if (!$route[$weekday] && $thisUTC <= $todayUTC + 1209600) {  // autogenerate routes 2 weeks out from today 
	        $day = date("D",mktime(0,0,0,substr($routeID,3,2),substr($routeID,6,2),substr($routeID,0,2)));
			$team_captains = get_team_captains(substr($routeID,9), $day);
			if (sizeof($team_captains)==0)
				$team_captain = "";   // no day captain
			else $team_captain = $team_captains[0]->get_id();
			$route[$weekday] = make_new_route($routeID,$team_captain);
        }
		// start row
		echo "<tr>" ;
		
		// col 1 : day of week
		$days[$weekday] = $weekday." ". date('M j', $dayUTC);
		echo "<td>"."<a href=editRoute.php?routeID=".$routeID.">".$days[$weekday]."</a></td>" ;
		
        echo "<!-- status='".$route[$weekday]->get_status()."' -->";
		// if route exists, generate this set of cols
		if($route[$weekday] != NULL)
		{	
			//col 2 : status
		    $status = "No Data";
            error_log("status='".$route[$weekday]->get_status()."'");
		    if (false && $route[$weekday]->get_status() == "completed")
		      $status = "entered";
		    else {
		        $stopids = $route[$weekday]->get_pickup_stops();
		        $pickupweight = 0;
		        foreach ($stopids as $pickup_id) {
		          $client_id = substr($pickup_id,12);
		          $theStop = retrieve_dbStops($routeID.$client_id);
		          if (!$theStop || $theStop->get_total_weight()==0) continue; //echo "routeid.clientid = ".$routeID.$client_id;
		          else {$status="entered"; $pickupweight+= $theStop->get_total_weight();}
		        }
		        $stopids = $route[$weekday]->get_dropoff_stops();
		        $dropoffweight = 0;
		        foreach ($stopids as $dropoff_id) {
		            $client_id = substr($dropoff_id,12);
		            $theStop = retrieve_dbStops($routeID.$client_id);
		            if (!$theStop || $theStop->get_total_weight()==0) continue; //echo "routeid.clientid = ".$routeID.$client_id;
		            else {$status="entered"; $dropoffweight+= $theStop->get_total_weight();}
		        }
		    }
		    // col 3 : weight received
		    if ($status=="entered") {
		      echo "<td align='right'>".$pickupweight."</td>";
		    // col 4 : weight delivered
		      echo "<td align='right'>".$dropoffweight."</td>";
		    // col 5: balance
		      echo "<td align='right'>".($pickupweight - $dropoffweight)."</td>";
		    }
		    else echo "<td align='right'>No Data</td><td></td><td></td>";
			
			//col 6 : data sources
			if ($route[$weekday]->get_notes() != "") {
			    $first_tablet = $route[$weekday]->get_notes();
			    $j = strpos($first_tablet,","); // see if there's more than one enterer -- tablet or person
				$times = "";
				while ($j > 0) {
					$this_tablet = substr($first_tablet,0,$j);
					$first_tablet = substr($first_tablet,$j+1);
					$i = strpos($this_tablet,";");
					if ($i>0) // it's a tablet
					    $times = $times . substr($this_tablet,0,4)."...".substr($this_tablet,$i+1) . ", ";
					else //it's a person
					    $times = $times . substr($this_tablet,0,$j). ", ";
					$j = strpos($first_tablet,","); 
				}
				$i = strpos($first_tablet,";");
				if ($i>0) // it's a tablet
				    $times = $times . substr($first_tablet,0,4)."...".substr($first_tablet,$i+1);
				else // it's a person
				    $times = $times . $first_tablet;
				//echo "<td>".substr($first_tablet,0,$i)."</td><td>".$times."</td>";
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;".$times."</td>";
		  }
		  else echo "<td></td>";
			
		}
		// else, use defaults
		else
		{	
			// col 2 : status
			echo "<td></td>";
			
			// col 3 : tablet ids
			echo "<td></td>";
		}
		
		// end row
		echo "</tr>";
		$dayUTC += 86400;
	}
	?>
</table>	
<br><strong>*</strong> View an entered route's weights by clicking its date.

<?php

 echo "<br><br><br><strong>$areas[$thisArea]</strong> Weekly Route Schedule";
 echo " for  Week of <strong>".date('F j, Y', $mondaythisweek)."</strong><br><br>  ";
 //	show_daily_schedule($areas[$thisArea],$mondaythisweek);
 echo "<table><tr>";
 foreach ($weekdays as $weekday)
   echo "<td><strong>". $days[$weekday] . "</strong></td>";
 echo "</tr><tr>";
 
 foreach ($weekdays as $weekday) {
   if ($route[$weekday]) {
     $pickups[$weekday] = $route[$weekday]->get_pickup_stops();
     $dropoffs[$weekday] = $route[$weekday]->get_dropoff_stops();
     echo "<td valign='top'><table>";

     echo "<tr><td><strong>PICK UPS</strong></td></tr>";
     foreach ($pickups[$weekday] as $pickup)
       echo "<tr><td>".substr($pickup,12)."</td></tr>";
 
     echo "<tr><td><strong>DROP OFFS</strong></td></tr>";
     foreach ($dropoffs[$weekday] as $dropoff)
       echo "<tr><td>".substr($dropoff,12)."</td></tr>";
 
     echo "</table></td>";
   }
   else echo "<td valign='top'><table></table></td>";
  
 }
 echo "</tr></table>";

?>
			</div>
			<?php include('footer.inc');?>
		</div>
	</body>
</html>
