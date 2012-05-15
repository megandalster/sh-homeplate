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
				<h4> <i>
				<?php
				$areas = array("HHI"=>"Hilton Head Island", "SUN"=> "Sun City", "BFT" => "Beaufort");
				$thisArea = $_GET['area'];
				$thisDay = $_GET['date'];
				$thisUTC = mktime(0,0,0,substr($thisDay,3,2),substr($thisDay,6,2),substr($thisDay,0,2));
				$nextweekUTC = $thisUTC + 604800;
				$prevweekUTC = $thisUTC - 604800;
				echo "$areas[$thisArea]"
				?>
				</i> weekly route status summary</h4>
				
				

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
				$mondaythisweek = strtotime('monday this week',$thisUTC);
				echo "<td colspan='2'><strong>Week of ".date('l F j, Y', $mondaythisweek)."</strong></td>";
				echo "<td></td><td><a href=viewRoutes.php?area=".$thisArea."&date=".date('y-m-d',$nextweekUTC).">Next >></a></td>";
				?>
</tr>
	<tr>
		<th></th>
		<td> <b> Status </b> </td>
		<td> <b> Driver(s) </b> </td>
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
		echo "<td><b>"."<a href=editRoute.php?routeID=".$routeID.">".$weekday." ". date('F j', $dayUTC)."</a></b></td>" ;
		
		// if route exists, generate this set of cols
		if($route != NULL)
		{
			// col 2 : status
			echo "<td>".$route->get_status()."</td>";

			// *note : this design requires driver in position 0 of array
			$volunteers = $route->get_drivers();
			
			//col 3 : driver
			echo "<td>";
			foreach($volunteers as $driverID)
			{
				$driver = retrieve_dbVolunteers($driverID);
				echo $driver->get_first_name()." ".$driver->get_last_name()."<br>";
			}
			echo "</td>";
			
			//col 4 : pickups
			echo "<td align='center'>".$route->get_num_pickups()."</td>";
			
			//col 5 : dropoffs
			echo "<td align='center'>".$route->get_num_dropoffs()."</td>";

		}
		
		// else, use defaults
		else
		{
			// col 2 : status (blank)
			echo "<td>not yet created</td>";
			
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
			</div>
			<?php include('footer.inc');?>
		</div>
	</body>
</html>