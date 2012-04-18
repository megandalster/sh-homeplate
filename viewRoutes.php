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
			Route View
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
				?>
				<h4> <i>
				<?php
				$areas = array("HHI"=>"Hilton Head Island", "SUN"=> "Sun City", "BFT" => "Beaufort");
				$thisArea = $_SESSION[_area];
				echo "$areas[$thisArea]"
				?>
				</i> weekly route status summary</h4>
				
				<?php
				$time = strtotime('monday this week');
				echo "<h5>Week of ".date('l F j, Y', $time)."</h5>"
				?>

<form action="editRoute.php?routeID=12-04-18-HHI" method="POST">				
<table cellspacing="10">
<style type="text/css">
td
{
padding:10px 10px 10px 10px;
}
</style>
	<tr>
		<th></th>
		<td> <b> Status </b> </td>
		<td> <b> <i> View </i> </b> </td>
		<td> <b> Driver(s) </b> </td>
		<td> <b> Pickups </b> </td>
		<td> <b> Dropoffs </b> </td>
	</tr>
	
	<?php
	
	$weekday = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	
	// each iteration generates a row in the table
	foreach($weekday as $value)
	{
		$routeDay = strtotime($value." this week");
		$routeID = date('y\-m\-d', $routeDay).'-'.$_GET[area];
		$route = get_route($routeID);
		
		// start row
		echo "<tr>" ;
		
		// col 1 : day of week
		echo "<td><b>".$value."</b></td>" ;
		
		// if route exists, generate this set of cols
		if($route != NULL)
		{
			// col 2 : status
			echo "<td>".$route->get_status()."</td>";

			// col 3 : view/edit
			echo "<td><input type='radio' name='routeWeek' value='".$routeID."'></td>";
			
			// *note : this design requires driver in position 0 of array
			$volunteers = $route->get_drivers();
			
			//col 4 : driver
			echo "<td>";
			foreach($volunteers as $driverID)
			{
				$driver = retrieve_dbVolunteers($driverID);
				echo $driver->get_first_name()." ".$driver->get_last_name()."<br>";
			}
			echo "</td>";
			
			//col 5 : pickups
			echo "<td>".$route->get_num_pickups()."</td>";
			
			//col 6 : dropoffs
			echo "<td>".$route->get_num_dropoffs()."</td>";

		}
		
		// else, use defaults
		else
		{
			// col 2 : status (blank)
			echo "<td>not yet created</td>";
			
			// col 3 : view/edit
			echo "<td><input type='radio' value='".$routeID."'></td>";
			
			// col 4 : driver (blank)
			echo "<td>--</td>";
			
			// col 5 : pickups (blank)
			echo "<td>--</td>";
			
			// col 6 : dropoffs (blank)
			echo "<td>--</td>";
		}
		
		// end row
		echo "</tr>";
	}
	?>
</table>

<br>
<input type="submit" name="Submit" value="View selected route"/>
</form>
				
			</div>
			<?php include('footer.inc');?>
		</div>
	</body>
</html>