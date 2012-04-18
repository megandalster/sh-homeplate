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
 *  a form for a route to be edited or deleted from the database
 *	@author Allen Tucker
 *	@version 4/15/2012
 */
$today = date(y-m-d); 
// for drivers, only today's route can be edited by adding weights for individual stops
if($_SESSION['access_level']==1 && substr($route->get_id(),0,8)==$today && $route->get_status()!="created") {
    echo('<p><strong>Route Information Form</strong><br />');
	echo('Here you can record pickups and deliveries for today\'s route.' .
	     '<br>When your route is completed, hit <b>Submit</b> at the bottom of this page and logout.');
}
// for team captains, current and future routes can be edited by adding and removing drivers or stops and by publishing
else if ($_SESSION['access_level']>=2 && substr($route->get_id(),0,8)>=$today && $route->get_status()!="completed") {
	echo('<p><strong>Route Information Form</strong><br />');
	echo('Here you can change any future route\'s drivers, pick-ups, or drop-offs.</p><p>');
}
// for all other situations, the route cannot be edited.
else {
    echo("<p id=\"error\">You do not have sufficient permissions to edit this route.</p>");
	include('footer.inc');
	echo('</div></div></body></html>');
	die();
}

?>
<form method="POST">
	<input type="hidden" name="_form_submit" value="1">
	
<p><strong><?PHP echo($route->get_area())." route for ".$route->get_day()?></strong>	
<?php echo ('<p>Status: ');
    
	echo('<select name="change_status">');
    echo ('<option value=""></option>');
    echo ('<option value="created"');if ($route->get_status()=='created') echo (' SELECTED'); echo('>created</option>');
    echo ('<option value="published"');if ($route->get_status()=='published') echo (' SELECTED'); echo('>published</option>');
	echo ('<option value="completed"');if ($route->get_status()=='completed') echo (' SELECTED'); echo('>completed</option>');
	echo('</select>&nbsp;&nbsp;');
	echo ('<input type="submit" value="Change Status">');

echo ('<p><table><tr><td>Drivers: ');echo ('</td><td>Pick-ups: '); echo ('</td><td>Drop-offs: '); echo ('</td></tr>');
echo "<tr><td valign='top'>";
foreach ($route->get_drivers() as $driver_id) { 
	$driver = retrieve_dbVolunteers($driver_id);
	$name = $driver->get_first_name() . ' ' . $driver->get_last_name();
	echo "<input type='checkbox' name='s_driver[]' value='".$driver_id."' />".$name.
		"<br>";
}
echo "</td><td valign='top'>";
foreach ($route->get_pickup_stops() as $pickup_id) {
	echo "<input type='checkbox' name='s_pickup[]' value='".$pickup_id."' />".substr($pickup_id,12).
		"<br>";
}
echo "</td><td valign='top'>";
foreach ($route->get_dropoff_stops() as $dropoff_id) {
	echo "<input type='checkbox' name='s_dropoff[]' value='".$dropoff_id."' />".substr($dropoff_id,12).
		"<br>";
}
echo "</td><tr><td>";
	echo "<input type='submit' name='remove_driver' value='Remove checked' />";
echo "</td><td>";
	echo "<input type='submit' name='remove_pickup' value='Remove checked' />";
echo "</td><td>";
	echo "<input type='submit' name='remove_dropoff' value='Remove checked' />";
echo "</td><tr><td>";
	echo "Add Driver:<br>";
	$drivers = getonlythose_dbVolunteers(substr($route,9,3), "driver", "active", "", "");
	echo('<select name="add_driver">');
    echo ('<option value=""></option>');
    foreach ($drivers as $driver) {
    	$name = $driver->get_first_name().' '.$driver->get_last_name();
    	echo ('<option value="s_driver[]"');
    	echo('>'.$name.'</option>');
    }
    echo('</select>');
	
echo "</td><td>";
	echo "Add Pick-up:<br>";
	$pickups = getall_clients(substr($route,9,3), "", "donor");
	echo('<select name="add_pickup">');
    echo ('<option value=""></option>');
    foreach ($pickups as $pickup) {
    	$name = $pickup->get_id();
    	echo ('<option value="s_pickup[]"');
    	echo('>'.$name.'</option>');
    }
    echo('</select>');
	
echo "</td><td>";
	echo "Add Drop-off:<br>";
	$dropoffs = getall_clients(substr($route,9,3), "", "recipient");
	echo('<select name="add_dropoff">');
    echo ('<option value=""></option>');
    foreach ($dropoffs as $dropoff) {
    	$name = $dropoff->get_id();
    	echo ('<option value="s_dropoff[]"');
    	echo('>'.$name.'</option>');
    }
    echo('</select>');
	
echo '</td></tr></table>';
 echo('<p>Hit <input type="submit" value="Submit" name="Submit Edits"> to complete these changes.<br /><br />');
?>

</form>