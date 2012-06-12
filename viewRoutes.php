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
				<br><strong>
				<?php
				$areas = array("HHI"=>"Hilton Head", "SUN"=> "Bluffton", "BFT" => "Beaufort");
				$thisArea = $_GET['area'];
				$thisDay = $_GET['date'];
				$today = date('y-m-d');
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
<table cellspacing="10">
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
				echo "<td></td><td><a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$nextweekUTC).">Next >></a></td>";
				?>
</tr>
	<tr>
		<th></th>
		<td> <b> Action* </b> </td>
		<td> <b> Drivers </b> </td>
		<td> <b> Pickups </b> </td>
		<td> <b> Dropoffs </b> </td>
	</tr>
	
	<?php
	
	$weekdays = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	
	// each iteration generates a row in the table
	$dayUTC = $mondaythisweek;
	foreach ($weekdays as $weekday)
	{
		$routeID = date('y-m-d', $dayUTC).'-'.$_GET[area];
		$route = get_route($routeID);
		// start row
		echo "<tr>" ;
		
		// col 1 : day of week
		echo "<td>".$weekday." ". date('F j', $dayUTC)."</td>" ;
		
		// if route exists, generate this set of cols
		if($route != NULL)
		{
			// col 2 : action
			echo "<td>"."<a href=editRoute.php?routeID=".$routeID.">view</a>"."</td>";

			// *note : this design requires driver in position 0 of array
			$volunteers = $route->get_drivers();
			
			//col 3 : driver
			echo "<td align='center'>".sizeof($volunteers);
		/*	foreach($volunteers as $driverID)
			{
				$driver = retrieve_dbVolunteers($driverID);
				if ($driver)
	    			$name = $driver->get_first_name() . ' ' . $driver->get_last_name();
				else $name = $driver_id;
				echo $name."<br>";
			}
		*/	echo "</td>";
			
			//col 4 : pickups
			echo "<td align='center'>".$route->get_num_pickups()."</td>";
			
			//col 5 : dropoffs
			echo "<td align='center'>".$route->get_num_dropoffs()."</td>";

		}
		
		// else, use defaults
		else
		{
			// col 2 : action
		//	if (substr($routeID,0,8)>$today)
				echo "<td>"."<a href=editRoute.php?routeID=".$routeID.">create</a></td>";
		//	else echo "<td></td>";
			
			// col 3 : driver (blank)
			echo "<td></td>";
			
			// col 4 : pickups (blank)
			echo "<td></td>";
			
			// col 5 : dropoffs (blank)
			echo "<td></td>";
		}
		
		// end row
		echo "</tr>";
		$dayUTC += 86400;
	}
	?>
</table>	
<br><strong>*</strong> You can create any future route and you can view any route.
	
			</div>
			<?php include('footer.inc');?>
		</div>
	</body>
</html>