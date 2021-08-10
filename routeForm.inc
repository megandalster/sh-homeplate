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
 *	@version 7/23/2021
 */
$routedate = substr($route->get_id(),0,8);
$thisUTC = mktime(0,0,0,substr($today,3,2),substr($today,6,2),substr($today,0,2));
$twoweeksagoUTC = $thisUTC - 1209600;
$twoweeksago = date('y-m-d',$twoweeksagoUTC);
echo '<p><strong>'.$route->get_area()." route for ".$route->get_day().'</strong>';
// for today's route only, a crew member can record crew names and add weights for individual stops
if($_SESSION['access_level']==1 && $routedate==$today) {
    echo('<p>Here you can record in the onboard crew and record the weights for each stop on today\'s route.' .
        '<br>&nbsp;&nbsp;&nbsp;&nbsp;1. Check the Scheduled <b>Crew</b> members who are onboard the truck, and hit <b>Submit</b> below.' .
        '<br>&nbsp;&nbsp;&nbsp;&nbsp;2. For each Pick-up or Drop-off <b>stop</b>, select it below to record its food weights.</p><p>' .
        '<i>At the end of the route</i>:' . 
	     '<br>&nbsp;&nbsp;&nbsp;&nbsp;1. Check that the <b>Balance</b> below is 0 (the truck is empty), and' .  
         '<br>&nbsp;&nbsp;&nbsp;&nbsp;2. Hit <b>logout</b> above to sign out.</p><p>');
}
// recent routes can be edited by coordinators by adjusting crew members and weights for individual stops 
else if ($_SESSION['access_level']>=2) {
    if ($routedate < $twoweeksago)
        echo('<p>Here you can update the weights for any of this route\'s <b>stops</b>.');
    else if ($routedate > $today)
        echo('<p>Here you can add to the <b>Scheduled Crew</b> or <b>stops</b> to this route.');
    else echo('<p>Here you can record the onboard crew and enter the weights for any of this route\'s <b>stops</b>.');
	if ($today>=$routedate && $routedate >= $twoweeksago) {
	   echo('<br>&nbsp;&nbsp;1.  Check/uncheck the onboard crew members for this route and hit <b>Submit</b>.');
	   echo('<br>&nbsp;&nbsp;&nbsp;&nbsp;(Trip counts are automatically adjusted when you make changes.)');
	   echo('<br>&nbsp;&nbsp;2.  For each Pick-up or Drop-off <b>stop</b>, select it below to record its food weights.');
	}
	echo('<br><br>When you are done, hit <b>Return to routes</b> at the bottom.</p><p>');
}

?>
<form method="POST"><input type="hidden" name="_form_submit" value="1">

<?php 

echo ('<p><table><tr><td><b>Scheduled Crew</b>: ');
echo ('</td><td>Pick-up <b>stops</b> -- weights'); 
echo ('</td><td>Drop-off <b>stops</b> -- weights'); 
echo ('</td></tr>');
echo "<tr><td valign='top'>";

$priorcrew = array();
if (sizeof($route->get_drivers())==0 && $route->get_status()=="completed")
	echo "no drivers checked in<br>";
else 
  foreach ($route->get_drivers() as $driver_id) {
    $driver = retrieve_dbVolunteers($driver_id);
    if ($driver) 
	    $name = $driver->get_first_name() . ' ' . $driver->get_last_name();
    else $name = $driver->get_id();
    if ($routedate >= $twoweeksago && $routedate <= $today) {
        echo '<input type="checkbox" name="onboard[]" value=' . $driver_id;
        if (in_array ($routedate, $driver->get_lastTripDates() )) {
            echo(' CHECKED');
            $priorcrew[] = $driver->get_id();
        }
        echo '>  ' . $name .'<br>';
    }
    else echo $name ."<br>";
  }

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
		default: $pickup_link = "<a href='viewStop1.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
	}
	else { // special case for stops created en-route and client isnt in the database
		$theStop = retrieve_dbStops($routeID.$client_id);
		if ($theStop && sizeof($theStop->get_items()) > 0)
			$pickup_link = "<a href='viewStop2.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
		else 
			$pickup_link = "<a href='viewStop1.php?client_type=donor&stop_id=".$pickup_id."'>".$client_id."</a>";
	} 
	echo $pickup_link;
	//var_dump($theStop); echo "routeid, clientid = ".$routeID.", ".$client_id;
	if ($theStop && $theStop->get_total_weight()>0){
	    if (substr($theStop->get_notes(),0,1)=="!")
	        add_enterer($theStop, $route);    
	    echo " -- ".$theStop->get_total_weight();
		$totalPickUpWeight += $theStop->get_total_weight();
	}	
	echo "<br>";
	
}
echo "<p style='text-align:right;'><strong>Total Weight: " . $totalPickUpWeight . "</strong></p>";
echo "</td><td valign='top'>";

$totalDropoffWeight = 0;
foreach ($route->get_dropoff_stops() as $dropoff_id) {
	$client_id = substr($dropoff_id,12);
	$theStop = retrieve_dbStops($routeID.$client_id);
	if ($theStop && $theStop->get_total_weight()>0) { 
	    if (substr($theStop->get_notes(),0,1)=="!")
	        add_enterer($theStop, $route);
		$totalDropoffWeight += $theStop->get_total_weight();
	}
}
$balance = $totalPickUpWeight - $totalDropoffWeight;
foreach ($route->get_dropoff_stops() as $dropoff_id) { 
    $client_id = substr($dropoff_id,12);
    $dropoff_link = "<a href='viewStop1.php?client_type=recipient&stop_id=".$dropoff_id."&balance=".$balance."'>".$client_id."</a>";
    echo $dropoff_link;
    $theStop = retrieve_dbStops($routeID.$client_id);
    if ($theStop && $theStop->get_total_weight()>0) {
        echo " -- ".$theStop->get_total_weight();
    }
    echo "<br>";
}
	echo "<p style='text-align:right;'><strong>Total Weight: " . $totalDropoffWeight . "</strong></p>";
	echo "<p style='text-align:right;'><strong>Balance: " . $balance . "</strong></p>";
// if ($_SESSION['access_level']>=2 /* && substr($route->get_id(),0,8)>$today */) {
	echo "</td><tr><td>";
	
	echo "Add Crew:<br>";
	//$drivers = getall_drivers_available(substr($route->get_id(),9,3),substr($route->get_day(),0,3));
	$drivers = get_all_crew(substr($route->get_id(),9));

	echo('<select name="add_driver">');
	echo ('<option value=""></option>');
	foreach ($drivers as $driver) {
	  if (!in_array($driver->get_id(),$route->get_drivers())) {
		$name = $driver->get_last_name().', '.$driver->get_first_name();
		echo ('<option value="'.$driver->get_id().'"');
		echo('>'.$name.'</option>');
	  }
	}
	echo('</select>');

	echo "</td><td>";
	echo "Add Pick-up:<br>";
	$pickups = getall_clients("", "donor", "", "", "","","","","","active","");
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
	$dropoffs = getall_clients("", "recipient", "", "","","", "","","","active","");
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

	echo '</td></tr>';
	echo '<tr><td colspan="3"> Hit <input type="hidden" name="submitted" value="1">';
	echo '<input type="submit" value="Submit" name="Submit"> to ';
	if ($_SESSION['access_level']==1 && $today==substr($route->get_id(),0,8)) 
	    echo 'record today\'s Crew, ';
	else echo 'record the onboard crew, ';
	echo 'add a Crew member, add a Pick-up, or add a Drop-off (one at a time).</td></tr></table>';

if ($_SESSION['access_level']>=2){
    if (substr($route->get_id(),0,8)>$today) 
        echo ('<br><input type="checkbox" name="deleteMe" value="DELETE"> Check this box and then hit ' .
    				'<input type="submit" value="Refresh" name="Delete Entry"> to refresh this route in the database. ');
    echo "<br><br><a href='viewRoutes.php?area=".substr($routeID,9)."&date=".substr($routeID,0,8)."'>Return to routes.</a>";
}
echo('</div></div>');
	include('footer.inc');
	echo('</body></html>');
?>

</form>
