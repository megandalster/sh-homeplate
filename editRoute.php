<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/

/*
 *	editRoute.php
 *  oversees the editing of a route to be added, changed, or deleted from the database
 *	@author Allen Tucker
 *	@version April 15, 2012
 */
	session_start();
	session_cache_expire(30);
    include_once('database/dbRoutes.php');
    include_once('domain/Route.php');
//    include_once('database/dbLog.php');
	$routeID = $_GET['routeID'];
	$route = get_route($routeID);
	if (!$route) {
		$route = make_new_route($routeID,$_SESSION['_id']);
	}
?>
<html>
	<head>
		<title>
			Editing <?PHP echo($route->get_id());?>
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
<body>
  <div id="container">
    <?PHP include('header.php');?>
	<div id="content">
<?PHP
	if($_POST['_form_submit']!=1)
	//in this case, the form has not been submitted, so show it
		include('routeForm.inc');
	else {
		$message = process_form($route);
		echo "<p>".$message;
		include('routeForm.inc');		
		die();
	}
	include('footer.inc');
	echo('</div></div></body></html>');

/**
* process_form changes the status of a route,
* adds and removes drivers, pick-ups, and drop-offs and 
* returns a message reporting the result
*/
function process_form($route)	{
	// respond to the POST
		if($_POST['change_status']){
			$route->change_status($_POST['change_status']);
			update_dbRoutes($route);
			return ("status changed to ". $_POST['change_status']);
		}
		// remove a driver from the route
		else if($_POST['remove_driver']){
				
		}
		// add a new driver to the route
		else if ($_POST['add_driver']) {
			 
		}
		// remove a driver from the route
		else if($_POST['remove_driver']){
				
		}
		// add a new driver to the route
		else if ($_POST['add_driver']) {
			 
		}
		// remove a driver from the route
		else if($_POST['remove_driver']){
				
		}
		// add a new driver to the route
		else if ($_POST['add_driver']) {
			 
		}
}
?>
    </div>
  </div>
</body>
</html>