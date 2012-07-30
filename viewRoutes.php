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
					
				?>
				<br><strong>&nbsp;&nbsp;
				<?php
				$areas = array("HHI"=>"Hilton Head", "SUN"=> "Bluffton", "BFT" => "Beaufort");
				$thisArea = $_GET['area'];
				$thisDay = $_GET['date'];
				$today = date('y-m-d');
				$todayUTC = time();
				$thisUTC = mktime(0,0,0,substr($thisDay,3,2),substr($thisDay,6,2),substr($thisDay,0,2));
				$nextweekUTC = $thisUTC + 604800;
				$prevweekUTC = $thisUTC - 604800;
				echo "$areas[$thisArea]</strong>"
				?>
				 weekly route summary  (View other areas:
				<?php 
				foreach ($areas as $area=>$areaName) {
				   if ($thisArea!=$area)
				   	  echo "<a href=viewRoutes.php?area=".$area."&date=".$thisDay."> $areaName</a>";	
				}
				?>
)
<br><br><table cellspacing="10">
	<style type="text/css">
td
{
padding:10px 10px 10px 10px;
}
</style>
<tr>
<?php
				echo "<td><a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$prevweekUTC)."><< Previous</a></td>";
				$mondaythisweek = strtotime('last monday', strtotime('tomorrow',$thisUTC));
				echo "<td colspan='2'><strong>Week of ".date('F j, Y', $mondaythisweek)."</strong></td>";
				echo "<td><a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$nextweekUTC).">Next >></a></td>";
				?>
</tr>
	<tr>
		<td> <b> Route * </b> </td>
		<td> <b> Drivers </b> </td>
		<td> <b> Pickups </b> </td>
		<td> <b> Dropoffs </b> </td>
		<td> <b> Completed? </b> </td>
	</tr>
	
	<?php
	
	$weekdays = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	
	// each iteration generates a row in the table
	$dayUTC = $mondaythisweek;
	foreach ($weekdays as $weekday)
	{
		$routeID = date('y-m-d', $dayUTC).'-'.$_GET[area];
		$route = get_route($routeID);
        if (!$route && $thisUTC <= $todayUTC + 1209600) {  // autogenerate routes 2 weeks out from today 
	        $day = date("D",mktime(0,0,0,substr($routeID,3,2),substr($routeID,6,2),substr($routeID,0,2)));
			$team_captains = get_team_captains(substr($routeID,9), $day);
			if (sizeof($team_captains)==0)
				$team_captain = "Lisa8437152491";   // force a day captain if there are none
			else $team_captain = $team_captains[0]->get_id();
			$route = make_new_route($routeID,$team_captain);
        }
		// start row
		echo "<tr>" ;
		
		// col 1 : day of week
		echo "<td>"."<a href=editRoute.php?routeID=".$routeID.">".$weekday." ". date('F j', $dayUTC)."</a></td>" ;
		
		// if route exists, generate this set of cols
		if($route != NULL)
		{	
			//col 2 : drivers
			$volunteers = $route->get_drivers();
			echo "<td align='center'>".sizeof($volunteers)."</td>";
			
			//col 3 : pickups
			echo "<td align='center'>".$route->get_num_pickups()."</td>";
			
			//col 4 : dropoffs
			echo "<td align='center'>".$route->get_num_dropoffs()."</td>";
			
			//col 5 : status
			if ($route->get_status()=="completed") 
				echo "<td align='center'>"."yes"."</td>";
			else echo "<td align='center'>"."no"."</td>";

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
			echo "<td></td>";
		}
		
		// end row
		echo "</tr>";
		$dayUTC += 86400;
	}
	?>
</table>	
<br><strong>*</strong> View any route by clicking its date.
	
			</div>
			<?php include('footer.inc');?>
		</div>
	</body>
</html>