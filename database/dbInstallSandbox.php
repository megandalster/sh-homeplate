<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
* Tucker.  This program is part of Homeplate, which is free software.  It comes
* with absolutely no warranty.  You can redistribute and/or modify it under the
* terms of the GNU Public License as published by the Free Software Foundation
* (see <http://www.gnu.org/licenses/).
*/

/**
 * Initializes the database by creating the tables
 * and seeding them with test data for volunteers, clients, and schedules
 * @version April 1, 2012
 * @author Allen Tucker
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
	include_once('dbClients.php');
	include_once('dbMonths.php');
	include_once('dbRoutes.php');
	include_once('dbSchedules.php');
	include_once('dbStops.php');
	include_once('dbVolunteers.php');
	include_once('dbWeeks.php');
	
	// connect
	$connected=connect();
 	if (!$connected) echo mysql_error();
 	echo("connected...<br />");
    echo("database selected...<br />");

	// Clients
	create_dbClients(); //echo("dbClients added...<br />");	  
	// Months
	create_dbMonths(); //echo("dbRoutes added...<br />");   
	// Routes
	create_dbRoutes(); //echo("dbRoutes added...<br />");  
	// Driver Master Schedules
	create_dbSchedules(); //echo("dbSchedules added...<br />");  
	// Stops
	create_dbStops(); //echo("dbStops added...<br />");   
	// Volunteers
	create_dbVolunteers(); //echo("dbVolunteers added...<br />");
	// Weeks
	create_dbWeeks(); //echo("dbWeeks added...<br />");
	
	// now add some data to the volunteers and clients tables
	fill_the_sandbox();
	
	echo("Installation of sandbox database complete.");
	echo(" To prevent data loss, run this program only if you want to reinitialize the tables.</p>");

function fill_the_sandbox() {
	
	// add some volunteer data
	    $vol1 = new Volunteer("Brody", "Hartley", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"Hartley.Brody@gmail.com", "driver", "active", "HHI", "123456789","SC", "14-01-29", "", "Tue,Thu","",
    				"", "59-01-01","98-01-01", "", "");
        $vol2 = new Volunteer("Hopkins", "Richardo", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"milkywayw@gmail.com", "driver", "active", "HHI", "234567890","SC", "14-01-29", "", "Wed,Fri","",
    				"", "59-01-01","98-01-01", "", "");
        $vol3 = new Volunteer("Wetzel", "Nick", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"nwetzel41@gmail.com", "driver", "active", "HHI", "345678901","SC", "14-01-29", "", "Mon,Fri","",
    				"", "59-01-01","98-01-01", "", "");
        $vol4 = new Volunteer("Peluso", "Jon", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"jon25T@gmail.com", "driver,teamcaptain", "active", "HHI", "456789012","SC", "14-01-29", "", "Thu,Fri","",
    				"", "59-01-01","98-01-01", "", "");
        $vol5 = new Volunteer("Tucker", "Allen", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"allen@bowdoin.edu", "driver,teamcaptain", "active", "BFT", "567890123","SC", "14-01-29", "", "Sat,Fri","",
    				"", "59-01-01","98-01-01", "", "");
		insert_dbVolunteers($vol1);
		insert_dbVolunteers($vol2);
		insert_dbVolunteers($vol3);
		insert_dbVolunteers($vol4);
		insert_dbVolunteers($vol5);
		
	// add some client data
	    $client1 = new Client("Atlanta Bread", "", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client1);
		$client2 = new Client("Bi-Lo North", "", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client2);
		$client3 = new Client("Bi-Lo South", "", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client3);
		$client4 = new Client("Publix North End", "Publix", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "foodtype", "");insert_dbClients($client4);
		$client5 = new Client("Wal-Mart HHI", "Wal-Mart", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "foodtypeboxes", "");insert_dbClients($client5);
		$client6 = new Client("Fresh Market", "", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client6);
		$client7 = new Client("Pepperidge Farm", "", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client7);
		$client8 = new Client("Publix North", "Publix", "HHI", "donor", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client1);
		$client1 = new Client("Bluffton Self Help", "", "HHI", "recipient", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client1);
		$client2 = new Client("Boys and Girls Club", "", "HHI", "recipient", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client2);
		$client3 = new Client("Church of the Cross", "", "HHI", "recipient", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client3);
		$client4 = new Client("Deep Well", "", "HHI", "recipient", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client4);
		$client5 = new Client("First African Baptist", "", "HHI", "recipient", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client5);
		$client6 = new Client("Island House", "", "HHI", "recipient", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client6);
		$client7 = new Client("Memory Matters", "", "HHI", "recipient", "123 Maine St", "Hilton Head Island", 
                                "SC", "29926", "","2077253500", "", "Mon,Wed", "no", "", "");insert_dbClients($client7);
	
}

?>
</body>
</html>