<?php
/*
* Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homecheck, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).mysql_ connect()
*/

/*
* Volunteer class for Homecheck
* @author Alex Edison
* @version updated February 28, 2012
*/

function connect() {

	$host = "localhost";
	$database = "homeplatedb";
	$user = "homeplatedb";
	$password = "foodyWr1!";
	$connected = mysqli_connect($host,$user,$password);
	if (!$connected) { echo "not connected"; return mysqli_error($connected);}
	$selected = mysqli_select_db($connected,$database);
	if (!$selected) { echo "not selected"; return mysqli_error($connected); }
	else return $connected;
}
?>