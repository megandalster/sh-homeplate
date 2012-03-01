<?php
/*
 * Copyright 2008 by Oliver Radwan, Maxwell Palmer, Nolan McNair,
 * Taylor Talmage, and Allen Tucker.  This program is part of RMH Homebase.
 * RMH Homebase is free software.  It comes with absolutely no warranty.
 * You can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
*/

/**
 * Initializes the database by creating the table:
 * dbWeeks, dbDates, dbShifts, dbLog, dbSCL,
 * dbPersons, dbSchedules
 * @version May 1, 2008
 * @author Oliver Radwan and Maxwell Palmer
 */
?>

<html>
<title>
Database Initialization
</title>
<body>
<?php
	echo("Installing Tables...<br />");
	include_once('dbinfo.php');
	include_once('dbRoutes.php');

	// connect
	$connected=connect();
 	if (!$connected) echo mysql_error();
 	echo("connected...<br />");
   echo("database selected...<br />");

	// Routes
	setup_dbRoutes();
	echo("dbRoutes added...<br />");

	echo("Installation of mysql tables complete.");
	echo(" To prevent data loss, run this program only if you want to clear all the tables.</p>");


?>
</body>
</html>