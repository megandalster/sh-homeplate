<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
 */
/*
 *	routeForm.inc
 *  a form for a route to be edited from the database
 *	@author Allen Tucker
 *	@version 4/15/2012
 */
$today = date('y-m-d');
// for drivers, only today's route can be edited by adding weights for individual stops
if($_SESSION['access_level']==1 && substr($route->get_id(),0,8)==$today) {
	echo('<p><strong>Route Information Form</strong><br />');
	echo('Here you can record pickups and deliveries for today\'s route.' .
	     '<br>When your route is completed, please remember to logout.');
}
// current and future routes can be edited by adding and removing drivers 
else if ($_SESSION['access_level']>=2) {
	echo('<p><strong>Route Information Form</strong><br />');
	if (substr($route->get_id(),0,8)>$today)
		echo('You can make last-minute changes to this route at the bottom of this form.</p><p>');
}
// for all other situations, the route cannot viewed.
// else {
//	echo '<br><fieldset background="red>';
//	echo("<p id=\"error\">The time has passed for editing this route.  But you can always view its details.</p>");
//	echo '</fieldset><br>';
	//	include('footer.inc');
	//	echo('</div></div></body></html>');
	//	die();
// }

?>
<form method="POST"><input type="hidden" name="_form_submit" value="1">

<p><strong><?PHP echo($route->get_area())." route for ".$route->get_day()?></strong>
<?php 
/*
echo ('<p>Status: ');

if ($_SESSION['access_level']>=2 && substr($route->get_id(),0,8)>$today) {
	echo('<select name="change_status">');
	echo ('<option value="" SELECTED></option>');
	echo ('<option value="created"');if ($route->get_status()=='created') echo (' SELECTED'); echo('>created</option>');
	echo ('<option value="published"');if ($route->get_status()=='published') echo (' SELECTED'); echo('>published</option>');
	echo ('<option value="completed"');if ($route->get_status()=='completed') echo (' SELECTED'); echo('>completed</option>');
	echo('</select>&nbsp;&nbsp;');
}
else echo $route->get_status();
*/
echo ('<p><table><tr><td>Crew: ');echo ('</td><td>Pick-ups -- weights'); echo ('</td><td>Drop-offs -- weights'); echo ('</td></tr>');
echo "<tr><td valign='top'>";


if (sizeof($route->get_drivers())==0 && $route->get_status()=="completed")
	echo "no drivers checked in<br>";
else
foreach ($route->get_drivers() as $driver_id) {
$driver = retrieve_dbVolunteers($driver_id);
	if ($driver)
	    $name = $driver->get_first_name() . ' ' . $driver->get_last_name();
	else $name = $driver_id;
	if ($_SESSION['access_level']>=2 && substr($route->get_id(),0,8)>$today)
	echo "<input type='checkbox' name='s_driver[]' value='".$driver_id."'>".$name."<br>";
	else echo $name."<br>";
}

/*
$crewMembers = get_all_crew(substr($route->get_id(),9));
foreach ($crewMembers as $driver) {
  
	if ($driver)
	    $name = $driver->get_first_name() . ' ' . $driver->get_last_name();
	else $name = $driver_id;
	if ($_SESSION['access_level']>=2 && substr($route->get_id(),0,8)>$today)
	echo "<input type='checkbox' name='s_driver[]' value='".$driver_id."'>".$name."<br>";
	else echo $name."<br>";
  }
  */
echo "</td><td valign='top'>";

$totalPickUpWeight = 0;

foreach ($route->get_pickup_stops() as $pickup_id) {
	$client_id = substr($pickup_id,12);
	$theStop = retrieve_dbStops($routeID.$client_id);
	$client = retrieve_dbClients($client_id);
	if ($client)
	  switch ($client->get_weight_type()) {
		case "foodtype": $pickup_link = "<a href='viewStop2.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
		break;
		//case "foodtypeboxes": $pickup_link = "<a href='viewStop2.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
		//break;
		default: $pickup_link = "<a href='viewStop1.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
	}
	else { // special case for stops created en-route and client isnt in the database
		$theStop = retrieve_dbStops($routeID.$client_id);
		if (sizeof($theStop->get_items()) > 0)
			$pickup_link = "<a href='viewStop2.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
		else 
			$pickup_link = "<a href='viewStop1.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
	}
	echo $pickup_link;
	if ($theStop->get_total_weight()>0){
		echo " -- ".$theStop->get_total_weight();
		$totalPickUpWeight += $theStop->get_total_weight();
		}
		
	echo "<br>";
	
}
echo "<p style='text-align:right;'><strong>Total Weight:" . $totalPickUpWeight . "</strong><p>";
echo "</td><td valign='top'>";

$totalDropoffWeight = 0;

foreach ($route->get_dropoff_stops() as $dropoff_id) {
	$client_id = substr($dropoff_id,12);
	//$client = retrieve_dbClients($client_id);
	$dropoff_link = "<a href='viewStop1.php?client_type=recipient&stop_id=".$dropoff_id."'>".$client_id."</a>";
	//if ($_SESSION['access_level']>=2 && substr($route->get_id(),0,8)>$today)
	//echo "<input type='checkbox' name='s_dropoff[]' value='".$dropoff_id."' />".$dropoff_link."<br>";
	//else 
	$theStop = retrieve_dbStops($routeID.$client_id);
	echo $dropoff_link;
	if ($theStop->get_total_weight()>0) echo " -- ".$theStop->get_total_weight();
	$totalDropoffWeight += $theStop->get_total_weight();
	echo "<br>";
	
}

	echo "<p style='text-align:right;'><strong>Total Weight:" . $totalDropoffWeight . "</strong><p>";
if ($_SESSION['access_level']>=2 && substr($route->get_id(),0,8)>$today) {
	echo "</td><tr><td>";
	if (sizeof($route->get_drivers())>0)
	echo "<input type='submit' name='remove_driver' value='Remove checked' />";
	/*
	echo "</td><td>";
	if ($route->get_num_pickups()>0)
	echo "<input type='submit' name='remove_pickup' value='Remove checked' />";
	echo "</td><td>";
	if ($route->get_num_dropoffs()>0)
	echo "<input type='submit' name='remove_dropoff' value='Remove checked' />";
	*/
	echo "</td><tr><td>";
	echo "Add Volunteer:<br>";
	//$drivers = getall_drivers_available(substr($route->get_id(),9,3),substr($route->get_day(),0,3));
	$drivers = get_all_crew(substr($route->get_id(),9));

	
if ($_SESSION['access_level']>=2 && substr($route->get_id(),0,8)>$today) {
	echo('<select name="add_driver">');
	echo ('<option value=""></option>');
	foreach ($drivers as $driver)
	if (!in_array($driver->get_id(),$route->get_drivers())) {
		$name = $driver->get_last_name().', '.$driver->get_first_name();
		echo ('<option value="'.$driver->get_id().'"');
		echo('>'.$name.'</option>');
	}
	echo('</select>');

	echo "</td><td>";
	echo "Add Pick-up:<br>";
	$pickups = getall_clients(substr($route->get_id(),9,3), "donor", "", "", "","","");
	echo('<select name="add_pickup">');
	echo ('<option value=""></option>');
	foreach ($pickups as $pickup) {
		if (!in_array($route->get_id().$pickup->get_id(),$route->get_pickup_stops())) {
			$name = $pickup->get_id();
			echo ('<option value="'.$route->get_id().$pickup->get_id().'"');
			echo('>'.$name.'</option>');
		}
	}
	echo('</select>');

	echo "</td><td>";
	echo "Add Drop-off:<br>";
	$dropoffs = getall_clients(substr($route->get_id(),9,3), "recipient", "", "", "","","");
	echo('<select name="add_dropoff">');
	echo ('<option value=""></option>');
	foreach ($dropoffs as $dropoff) {
		if (!in_array($route->get_id().$dropoff->get_id(),$route->get_dropoff_stops())) {
			$name = $dropoff->get_id();
			echo ('<option value="'.$route->get_id().$dropoff->get_id().'"');
			echo('>'.$name.'</option>');
		}
	}
	echo('</select>');
	
}
echo '</td></tr>';
echo('<tr><td colspan="2"> Hit <input type="hidden" name="submitted" value="1">');
echo('<input type="submit" value="Submit" name="Submit"> to add Volunteer, Pick-up, or Drop-off.</td></tr></table>');

}
else echo "</td></tr></table>";
if ($route->get_status()!="completed")
	echo ('<br><input type="checkbox" name="deleteMe" value="DELETE"> Check this box and then hit ' .
				'<input type="submit" value="Delete" name="Delete Entry"> to delete this route from the database. ');

echo "<br><br><a href='viewRoutes.php?area=".substr($routeID,9)."&date=".substr($routeID,0,8)."'>Click here to update and return to routes.</a>";
echo('</div></div>');
	include('footer.inc');
	echo('</body></html>');
?>

</form>
