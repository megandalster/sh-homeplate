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
$c=new Client("Bi-Lo - Boundary Street #158","Bi-Lo","BFT","donor","2127 Boundry St.","Beaufort","SC",29902,"","524-2771","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Bill Bradsaw"); insert_dbClients($c);
$c=new Client("Bi-Lo - Shell Point # 525","Bi-Lo","BFT","donor","860 Parris Isl.Gateway","Beaufort","SC",29902,"","524-2300","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Bimbo Bakery","","BFT","donor","45 Laurel Bay Rd.","Beaufort","SC",29906,"","321-0429","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Coca-Cola Bottling Co.","","BFT","donor","1840 Ribaut Rd","Port Royal","SC",29935,"","843-525-6293","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion - Ladies Island #945","Food Lion","BFT","donor","313 Laural Bay Rd.","Beaufort","SC",29906,"","846-9994","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion - Laurel Bay #1698","Food Lion","BFT","donor","10 Sams Point Rd.","Ladys Island","SC",29907,"","521-4525","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Longs Processing Facility","","BFT","donor","8925 Browning Gate Rd.","Varnville","SC",29944,"","803-625-4450","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Pepsi Bottling Company","","BFT","donor","287 Braod River Rd.","Beaufort","SC",29902,"","521-1424","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Piggly Wiggly - Boundry","","BFT","donor","123 Boundry St.","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Pizza Hut - Inactive","","BFT","donor","2433 Boundry St.","Beaufort","SC",29902,"","524-7948","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Publix #623 Beaufort","Publix","BFT","donor","163 Sea Island Pkwy","Ladys Island","SC",29907,"","986-5061","","Mon,Tue,Wed,Thu,Fri,Sat","yes","foodtype",""); insert_dbClients($c);
$c=new Client("Wal-Mart Supercenter","Wal-Mart","BFT","donor","350 Robert Smalls Pkwy","Beaufort","SC",29906,"","843-521-9589","","Mon,Tue,Wed,Thu,Fri,Sat","yes","foodtypeboxes",""); insert_dbClients($c);
$c=new Client("Walgreens - Beaufort","","BFT","donor","170 Boundary St.","Beaufort","SC",29902,"","522-1396","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Toby"); insert_dbClients($c);
$c=new Client("1st African","","BFT","recipient","1414 S. Ribaut Road","Port Royal","SC",29935,"","525-6119","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("AME Church","","BFT","recipient","","Ladys Island","SC",29907,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Beaufort Marine Institute","","BFT","recipient","60 Honey Bee Island","Seabrook","SC",29940,"","846-2128","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","James N. rivers, III"); insert_dbClients($c);
$c=new Client("Bethel Deliverance Temple","","BFT","recipient","239 County Shed Rd.; PO Box 4968","Beaufort","SC",29903,"","843-521-0720","263-925","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Anthony Cummings"); insert_dbClients($c);
$c=new Client("Boys & Girls Club - Beaufort","","BFT","recipient","17 B Marshell Lane","Beaufort","SC",29902,"","986-5437","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Sam Burke"); insert_dbClients($c);
$c=new Client("Burton Wells  Senior Center","","BFT","recipient","1 Middleton Recreation","Beaufort","SC",29906,"","470-5831","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Helen"); insert_dbClients($c);
$c=new Client("Canal Appartments","","BFT","recipient","1700 Salem Rd.; PO Box 1225","Beaufort","SC",29901,"","521-6508","524-2207 X 22","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Susan Trogdon"); insert_dbClients($c);
$c=new Client("Cannan Baptist","","BFT","recipient","Hwy. 17","Ladys Island","SC",29907,"","592-0548","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Claysoul rice"); insert_dbClients($c);
$c=new Client("CAPA","","BFT","recipient","PO 531","Beaufort","SC",29901,"","524-7727","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Church of God & Unity","","BFT","recipient","PO Box","Ladys Island","SC",29907,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("CODA","","BFT","recipient","PO Box 1775","Beaufort","SC",29901,"","770-1074","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Community Bible Church","","BFT","recipient","638 Parris Island","Beaufort","SC",29906,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Community residence","","BFT","recipient","1508 Old Shell Rd.","Port Royal","SC",29935,"","525-7684","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Linda Jackson"); insert_dbClients($c);
$c=new Client("Coosa Elementary","","BFT","recipient","Brickyard Point","Port Royal","SC",29935,"","592-7526","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mr. Mixon"); insert_dbClients($c);
$c=new Client("Disability/Special Needs DSN","","BFT","recipient","PO Box 129","Port Royal","SC",29935,"","470-6300","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mitzi Wagner"); insert_dbClients($c);
$c=new Client("Ebenezer Baptist Church","","BFT","recipient","Martin Luther King Blvd.; PO Box 476","Saint Helena Island","SC",29920,"","843-575-1821","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Venessa Lynard"); insert_dbClients($c);
$c=new Client("First African Baptist - Beaufort","","BFT","recipient","1414 S. Ribaut Rd.","Port Royal","SC",29935,"","525-6119","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Fountain of Life  Ministry","","BFT","recipient","21 Marshellen Dr.","Port Royal","SC",29935,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Franciscan Center","","BFT","recipient","234 Green Winged Tead Dr. S.","Ladys Island","SC",29907,"","838-3924","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("G revival Ministries, Inc.","","BFT","recipient","52 Glaze Drive","Beaufort","SC",29906,"","694-1717","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Curt F. Gibbs"); insert_dbClients($c);
$c=new Client("H.A. Beaufort","","BFT","recipient","","Beaufort","SC",29901,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Hampton County Charities","","BFT","recipient","123 Given St.","Hampton","SC",29924,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Help Of Beaufort - Inactive","","BFT","recipient","1910 Baggett St","Beaufort","SC",29901,"","843 524-1223","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Housing Auth.- Yemassee","","BFT","recipient","1009 Prince St.; PO Box 1104","Beaufort","SC",29901,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Housing Authority - Beaufort","","BFT","recipient","1009 Prince St.; PO Box 1104","Beaufort","SC",29901,"","525-7059","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Angela or Ed"); insert_dbClients($c);
$c=new Client("Huspah Baptist Church","","BFT","recipient","18 Huspah Baptist Church Rd.","Seabrook","SC",29940,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("J. Davis School","","BFT","recipient","364 Kears Neck Road","Seabrook","SC",29940,"","466-3600","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Leroy Brown Senior Center","","BFT","recipient","123 Hello","Saint Helena Island","SC",29920,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Life Ministry","","BFT","recipient","645 Beaufort St.","Beaufort","SC",29901,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Mossy Oaks","","BFT","recipient","25 Johnny Morrall Circle","Beaufort","SC",29902,"","524-2922","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Johnnie Jackson"); insert_dbClients($c);
$c=new Client("New Hope","","BFT","recipient","Parris Island Gateway","Ladys Island","SC",29907,"","524-6520","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Betty"); insert_dbClients($c);
$c=new Client("Oak Hill Terrace","","BFT","recipient","2605 N. Royal Oaks; C/O Joyce Bunton","Beaufort","SC",29902,"","812-0638","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","buntonj2@hargray."); insert_dbClients($c);
$c=new Client("Parks & Leisure Seniors-Bft","","BFT","recipient","1514 Richmond Avenue","Port Royal","SC",29935,"","470-6321","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Washington"); insert_dbClients($c);
$c=new Client("Port Royal Adult Com.-inactive","","BFT","recipient","1508 Old Shell Rd.","Port Royal","SC",29935,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Praise Assembly","","BFT","recipient","800 Parris Isl. Gatweway; PO Box 596","Port Royal","SC",29935,"","843-252-9155","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mike McCaskey"); insert_dbClients($c);
$c=new Client("Revival Team Outreach","","BFT","recipient","123 Happy","Ladys Island","SC",29907,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Revival Team Outreadh Ministry","","BFT","recipient","1744 Trask Parkway; PO Box 631","Lobeco","SC",29931,"","843-575-0251","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Pastor Daniels"); insert_dbClients($c);
$c=new Client("Rose Hill Baptist - Inactive","","BFT","recipient","308 Shanklin Rd.","Ladys Island","SC",29907,"","379-4886","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Sacred Heart House","","BFT","recipient","123 Hello","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Scotts Hill Center","","BFT","recipient","Scott Hills Road","Ladys Island","SC",29907,"","838-8300","252-6525","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Scotts Hill Church of Christ","","BFT","recipient","259 Storyteller Rd.","Saint Helena Island","SC",29920,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Gethsemane Baptist","","BFT","recipient","350 Stuart Point Rd.","Seabrook","SC",29940,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Goodwill Church","","BFT","recipient","","Saint Helena Island","SC",29920,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Jordan Church","","BFT","recipient","123 Beaufort","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Senior Center","","BFT","recipient","1408 Parris Avenue","Port Royal","SC",29935,"","524-1787","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Helen Elliot"); insert_dbClients($c);
$c=new Client("Sheldon Community Enrichment","","BFT","recipient","PO Box 323","Sheldon","SC",29941,"","846-2250","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Mackie"); insert_dbClients($c);
$c=new Client("Sinai Baptist Church","","BFT","recipient","PO Box 123","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Team Revival","","BFT","recipient","1744 Trask Parkway","Seabrook","SC",29940,"","846-4024","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("The Church of God in Unity","","BFT","recipient","38 Millege Road","Beaufort","SC",29902,"","524-5600","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Truck/Warehouse/Soda","","BFT","recipient","Pepsi Warehouse","Beaufort","SC",29906,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Women of Faith & Power","","BFT","recipient","71 Holly Hall Road","Beaufort","SC",29902,"","322-3202","683-2026","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mary Simmons"); insert_dbClients($c);
$c=new Client("Atlanta Bread - Hilton Head","","HHI","donor","120 Festival Park","Hilton Head Island","SC",29926,"","227-0375","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Lynn"); insert_dbClients($c);
$c=new Client("Bi-Lo  - Hilton Head North","Bi-Lo","HHI","donor","95 Mathews Drive","Hilton Head Island","SC",29926,"","681-5327","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Julia Peacock"); insert_dbClients($c);
$c=new Client("Bi-Lo - Hilton Head South #275","Bi-Lo","HHI","donor","70 Pope Avenue","Hilton Head Island","SC",29926,"","842-8691","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ernie Burris"); insert_dbClients($c);
$c=new Client("Charleys Crab","","HHI","donor","2 Hudson Road","Hilton Head Island","SC",29926,"","342-9066","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Eric Seaglund, Exec."); insert_dbClients($c);
$c=new Client("Christines Catering","","HHI","donor","Atrium Bldg. Unit 2","Hilton Head Island","SC",29928,"","785-4646","","","no","pounds",""); insert_dbClients($c);
$c=new Client("Collins & James","","HHI","donor","8 Arccher Road","Hilton Head Island","SC",29926,"","696-6388","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Jim & Alex Hazelto"); insert_dbClients($c);
$c=new Client("Crazy Crab","","HHI","donor","7 Office Park Road","Hilton Head Island","SC",29926,"","681-5021","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Debbie Tolerton, M"); insert_dbClients($c);
$c=new Client("Donor Programs","","HHI","donor","PO Box 23621","Hilton Head Island","SC",29926,"","689-3689","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Gerri"); insert_dbClients($c);
$c=new Client("Food Lion - Palmetto Bay #714","Food Lion","HHI","donor","6 Bow Circle","Hilton Head Island","SC",29928,"","842-9734","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Fresh Market","","HHI","donor","boy","Hilton Head Island","SC",29928,"","842-8332","","Mon,Tue,Wed,Thu,Fri","no","pounds","Jim riegel"); insert_dbClients($c);
$c=new Client("Harris Teeter - HHI  South #123","","HHI","donor","33 Greenwood Drive","Hilton Head Island","SC",29928,"","785-6185","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Harris Teeter - HHI North #152","","HHI","donor","301 Main Street","Hilton Head Island","SC",29926,"","689-6255","","Mon,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Heritage Farms","","HHI","donor","Sean Pines","Hilton Head Island","SC",29928,"","363-5444","","","yes","pounds",""); insert_dbClients($c);
$c=new Client("Heritage Foundation","","HHI","donor","Sea Pines","Hilton Head Island","SC",29928,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Island  Bagels","","HHI","donor","S. Island Square","Hilton Head Island","SC",29928,"","686-3353","","Mon,Wed","no","pounds",""); insert_dbClients($c);
$c=new Client("Longhorn Steak House","","HHI","donor","831 South Island Sq.","Hilton Head Island","SC",29928,"","686-4056","","Tue,Wed,Fri","no","pounds","Mat"); insert_dbClients($c);
$c=new Client("Marriott Beach & Golf resort","","HHI","donor","1 Hotel Circle","Hilton Head Island","SC",29928,"","686-8400","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Michael Stocker, C"); insert_dbClients($c);
$c=new Client("Michelle Kitter","","HHI","donor","17C Hunter Road","Hilton Head Island","SC",29926,"","785-4246","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Old Oyster Factory","","HHI","donor","101 Marshyland Road","Hilton Head Island","SC",29926,"","681-6040","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Pepperidge Farm","","HHI","donor","Hunter Road","Hilton Head Island","SC",29926,"","304-3000","","Mon,Wed,Fri","no","pounds","Taser"); insert_dbClients($c);
$c=new Client("Piggly Wiggly - Shelter Cove","","HHI","donor","32 Shelter Cove Lane","Hilton Head Island","SC",29928,"","842-4090","","Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Pizza Hut - Port royal","","HHI","donor","458 Wm Hilton Pkwy.","Hilton Head Island","SC",29926,"","681-8100","","Mon","no","pounds","Alfredo"); insert_dbClients($c);
$c=new Client("Post Office Drive","","HHI","donor","South End","Hilton Head Island","SC",29928,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Annual Drive"); insert_dbClients($c);
$c=new Client("Publix - Hilton Head North #473","Publix","HHI","donor","45 Pembroke Drive - Suite 104","Hilton Head Island","SC",29926,"","689-9977","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Publix - Hilton Head South #700","Publix","HHI","donor","11 Palmetto Bay Road","Hilton Head Island","SC",29928,"","842-2632","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Ronnies Bakery","","HHI","donor","1A Regecy Road","Hilton Head Island","SC",29928,"","842-4707","","","no","pounds",""); insert_dbClients($c);
$c=new Client("Sams Club","Sams Club","HHI","donor","98 Mathews Dr. Box 1-A","Hilton Head Island","SC",29926,"","681-7117","","Mon,Wed,Fri","yes","pounds",""); insert_dbClients($c);
$c=new Client("Seabrook of HH","","HHI","donor","300 Woodhaven Drive","Hilton Head Island","SC",29928,"","842-3747","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Once a week picku"); insert_dbClients($c);
$c=new Client("Second Helpings Office","","HHI","donor","","","","","","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Signes Heavenly Café","","HHI","donor","93 Arrow Road","Hilton Head Island","SC",29928,"","785-9118","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","On Call"); insert_dbClients($c);
$c=new Client("Starbucks - HHI North","","HHI","donor","461 Pineland Station","Hilton Head Island","SC",29926,"","689-6823","","Mon,Wed,Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("Starbucks - HHI South","","HHI","donor","11 Palmetto Bay Road","Hilton Head Island","SC",29928,"","341-5477","","Mon,Wed,Thu,Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Sweet Carolina Cupcakes","","HHI","donor","1 N. Forest Beach Dr.  #203; Coligny Plaza","Hilton Head Island","SC",29928,"","342-2611","","Mon","no","pounds","Holly Slayton"); insert_dbClients($c);
$c=new Client("The Smokehouse","","HHI","donor","102 Pope Avenue","Hilton Head Island","SC",29928,"","842-4227","","","no","pounds","Jerry, Owner"); insert_dbClients($c);
$c=new Client("Walgreens - Hilton Head","","HHI","donor","Festival Center","Hilton Head Island","SC",29926,"","342-7481","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Bob Lopez"); insert_dbClients($c);
$c=new Client("Weston resort","","HHI","donor","2 Grass Lawn Avenue","Hilton Head Island","SC",29926,"","681-4000","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Wild Wings - Hilton Head","","HHI","donor","72 Pope Avenue","Hilton Head Island","SC",29928,"","785-9464","","Mon","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Helpings Office","","HHI","Internal","PO Box 23621","Hilton Head Island","SC",29925,"","681-5572","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Gerri"); insert_dbClients($c);
$c=new Client("All Saints Episcopal","","HHI","recipient","3001 Meeting St.","Hilton Head Island","SC",29926,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Beaufort County Parks-rec - HHI","","HHI","recipient","61 Ulmer Rd. PO Box 205","Bluffton","SC",29910,"","757-1503","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Bluffton Self Help – HHI","","HHI","recipient","PO Box 2420","Bluffton","SC",29910,"","757-8000","","Mon,Tue,Thu","yes","pounds","Jenny Hanny"); insert_dbClients($c);
$c=new Client("Boys & Girls Club - HHI","","HHI","recipient","PO Box 22267","Hilton Head Island","SC",29925,"","689-3646","","Mon,Tue,Wed,Fri","no","pounds","Irving Campbell"); insert_dbClients($c);
$c=new Client("Childrens Center - HHI","","HHI","recipient","PO Box 22564","Hilton Head Island","SC",29925,"","681-2739","","Tue,Thu,Fri","no","pounds","Lorraine"); insert_dbClients($c);
$c=new Client("Church of the Cross – HHI","","HHI","recipient","PO Box 288","Bluffton","SC",29910,"","816-6015","","Mon","no","pounds","Larry Brown"); insert_dbClients($c);
$c=new Client("Deep Well","","HHI","recipient","PO Box 5543","Hilton Head Island","SC",29938,"","785-2849","","Mon","no","pounds","Betsy Doughtie"); insert_dbClients($c);
$c=new Client("First African Baptist - Hilton Hea","","HHI","recipient","Beach City Rd.","Hilton Head Island","SC",29926,"","671-6620","","Mon,Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("Hardeville Thrift Shop","","HHI","recipient","19869 Waythe Hardee Blvd","Hardeeville","SC",29927,"","843 784-3157","298-6109","Wed,Sat","no","pounds","Viola Ulmer"); insert_dbClients($c);
$c=new Client("Holy Family Catholic Church","","HHI","recipient","24 Pope Avenue","Hilton Head Island","SC",29928,"","247-2612","","Fri,Sat","no","pounds","Carl Zeis"); insert_dbClients($c);
$c=new Client("Housing Auth. - Sandalwood","","HHI","recipient","148 Island Drive #5","Hilton Head Island","SC",29938,"","973-349-7970","645-0935","Mon,Tue,Fri","no","pounds","Nannette Pierson"); insert_dbClients($c);
$c=new Client("Island House","","HHI","recipient","141 Goethe Road","Bluffton","SC",29910,"","757-888","","Mon,Wed","no","pounds","Elaine Smith"); insert_dbClients($c);
$c=new Client("Memory Matters","","HHI","recipient","50 Pope Avenue","Hilton Head Island","SC",29928,"","785-2119","785-4099","Mon,Wed","no","pounds",""); insert_dbClients($c);
$c=new Client("Miscellaneous Deliveries","","HHI","recipient","","","","","","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Mt.Calvary Baptist","","HHI","recipient","PO Box 23194","Hilton Head Island","SC",29925,"","681-3678","","Mon,Wed","no","pounds","Sandra Bass"); insert_dbClients($c);
$c=new Client("Office","","HHI","recipient","PO Box 23621","Hilton Head Island","SC",29926,"","843-689-3689","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Gerri"); insert_dbClients($c);
$c=new Client("Programs for Ex.People (PEP)","","HHI","recipient","10 Oak Park Drive Box 2","Washington","SC",20026,"","681-8413","","Mon","no","pounds","Peg Carroll"); insert_dbClients($c);
$c=new Client("Senior Citizens","","HHI","recipient","200 Main St.","Hilton Head Island","SC",29926,"","","","Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("St. Andrew By The Sea","","HHI","recipient","20 Pope Avenue","Hilton Head Island","SC",29928,"","785-4711","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds","Kathy Emery"); insert_dbClients($c);
$c=new Client("Tabernacle Baptist – HHI","","HHI","recipient","","","SC","","","","","Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Childrens Center - Bluffton","","HHI","recipient","PO Box 1089","Bluffton","SC",29910,"","757-5549","","Tue,Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("Archway Cookies","","SUN","donor","#2 Lotus Court","Bluffton","SC",29910,"","757-9233","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Andy Vodvarka"); insert_dbClients($c);
$c=new Client("Atlanta Bread - Sun City","","SUN","donor","11 Towne Drive","Bluffton","SC",29910,"","815-2479","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Bi-Lo Bluffton","Bi-Lo","SUN","donor","Fording Island Rd.","Bluffton","SC",29910,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Big Lots","","SUN","donor","32 Malphrus","Bluffton","SC",29910,"","843-837-1400","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds","Lost Data - July"); insert_dbClients($c);
$c=new Client("Farish Meat Processing","Farish Meat Pr","SUN","donor","Moss Creek","Okatie","SC",29909,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion - Kitties Xing # 2691","Food Lion","SUN","donor","1008 Fording Island Rd.","Bluffton","SC",29910,"","815-6100","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion – Sun City","Food Lion","SUN","donor","210 Okatie Village Drive ","Okatie","SC",29909,"","705-9301","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Henrys Farms","","SUN","donor","46 Tom Fritt Road","Okatie","SC",29909,"","838-2762","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mr. Colton"); insert_dbClients($c);
$c=new Client("Honey Baked Ham","","SUN","donor","1060 Fording Island Rd.","Bluffton","SC",29910,"","815-7388","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Krogers - Sun City","","SUN","donor","125 Towne Drive","Bluffton","SC",29910,"","815-6070","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Montanas","","SUN","donor","16 Kitties Landing","Bluffton","SC",29910,"","815-2327","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Piggly Wiggly - Hardeeville","","SUN","donor","7 Main St.","Hardeeville","SC",29927,"","784-3201","","Mon,Wed,Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Piggly Wiggly - Sun City","","SUN","donor","50 Burnt Church Rd. 100F","Bluffton","SC",29910,"","757-6621","","Mon,Wed,Fri","yes","pounds",""); insert_dbClients($c);
$c=new Client("Publix - Buckwalter #1205","Publix","SUN","donor","Belfair Village","Bluffton","SC",29910,"","706-3049","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Publix - Hardeeville #1354","Publix","SUN","donor","xxooo","Hardeeville","SC",29927,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Publix #845","Publix","SUN","donor","80 Baylor Drive","Bluffton","SC",29910,"","843-706-3049","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("R.T. Market","","SUN","donor","Palmetto Bluff","Bluffton","SC",29910,"","706-3448","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Target","","SUN","donor","1050 Fording Island Road","Bluffton","SC",29910,"","815-3321","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Truck","","SUN","donor","Sun City Parking Lot","Okatie","SC",29909,"","681-5572","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Wal-Mart Supercenter","Wal-Mart","SUN","donor","4400 Highway 278;1 Nickel Plate Road","Okatie","SC",29909,"","843-208-3000","","Mon,Wed,Thu,Sat","yes","foodtypeboxes","Harvest"); insert_dbClients($c);
$c=new Client("Wild Wings - Sun City","","SUN","donor","1188 Fording Isaland Rd.","Bluffton","SC",29910,"","837-9453","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("First  Baptist-Sun City","","SUN","recipient","12 Church St.","Beaufort","SC",29902,"","524-6886","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Habitat for Humanity - Beaufort-","","SUN","recipient","616 Parris Island Gateway; PO Box 1622","Beaufort","SC",29901,"","522-3500","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Helping Hands Of Burton","","SUN","recipient","22 Pine Grove Road","Beaufort","SC",29906,"","525-6586","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Mt. Carmel Baptist","","SUN","recipient","1 Middleton  R Dr.","Beaufort","SC",29906,"","813-470-6203","","Tue","no","pounds","Agnes Washington"); insert_dbClients($c);
$c=new Client("St. Jude Church","","SUN","recipient","12 Bing St.; PO Box 336","Yemassee","SC",29945,"","843-275-0481","","Fri","no","pounds","Debra Blue"); insert_dbClients($c);
$c=new Client("2nd Jordan Baptist Church","","SUN","recipient","2 Jordan Drive","Okatie","SC",29909,"","843-379-9927","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Deacon Jerome Kel"); insert_dbClients($c);
$c=new Client("Access Network","","SUN","recipient","5710 N. Okatie Hwy.- Suite B.","Ridgeland","SC",29936,"","843 379-5600","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("ACE - Sun City","","SUN","recipient","80 Lowcountry Dr.","Ridgeland","SC",29936,"","843-987-8107","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Eddie Odgen"); insert_dbClients($c);
$c=new Client("Agape/Arthur McGirk","","SUN","recipient","5855 S. Okatie Hgwy.","Hardeeville","SC",29927,"","843-784-6008","","Mon","no","pounds","Arthur McGire"); insert_dbClients($c);
$c=new Client("Bluffton Recreation Center","","SUN","recipient","PO Box","Bluffton","SC",29910,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Bluffton Self Help – SUN","","SUN","recipient","PO Box 2420","Bluffton","SC",29910,"","757-8000","","Wed,Fri","yes","pounds","Jenny Hanny"); insert_dbClients($c);
$c=new Client("Booker T. Washington Center","","SUN","recipient","224 Big Estate  Rd.","Yemassee","SC",29945,"","364-0676","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Lucille Williams"); insert_dbClients($c);
$c=new Client("Boys & Girls Club - Bluffton","","SUN","recipient","PO Box 1908","Bluffton","SC",29910,"","706-2300","","Tue,Fri","no","pounds","Molly O. Smith"); insert_dbClients($c);
$c=new Client("Boys & Girls Club - Okatie","","SUN","recipient","5855 S. Okatie Highway","Hardeeville","SC",29927,"","784-6008","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","robert Huff 717-11"); insert_dbClients($c);
$c=new Client("Boys & Girls Club – Ridgeland","","SUN","recipient","PO Box 1482","Ridgeland","SC",29936,"","812-4357","","Mon,Thu","no","pounds","Keith Alston"); insert_dbClients($c);
$c=new Client("Christ Central Mission","","SUN","recipient","PO Bhox 311","Ridgeland","SC",29936,"","726-9311","305-9091","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","John Doyle"); insert_dbClients($c);
$c=new Client("Church of the Cross – SUN","","SUN","recipient","PO Box 288","Bluffton","SC",29910,"","816-6015","","Mon,Thu","no","pounds","Larry Brown"); insert_dbClients($c);
$c=new Client("Community First","","SUN","recipient","1215 May River Road","Bluffton","SC",29910,"","298-0026","706-9580","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Paula Harvey, Exec."); insert_dbClients($c);
$c=new Client("Habitat for Humanity - Beaufort","","SUN","recipient","21 Brendan Lane","Ridgeland","SC",29936,"","757-9995","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Hardeeville Thrift","","SUN","recipient","","Okatie","SC",29909,"","298-6109","","Wed,Sat","no","pounds","Viola Ulmer"); insert_dbClients($c);
$c=new Client("New Abundant Life","","SUN","recipient","19 Church St.","Bluffton","SC",29910,"","","","Fri","yes","pounds",""); insert_dbClients($c);
$c=new Client("Parks & Rec. - Bluffton","","SUN","recipient","PO Box 205","Bluffton","SC",29910,"","757-1503","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Barbara Campbell"); insert_dbClients($c);
$c=new Client("Room At The Inn","","SUN","recipient","155 Old Miller Rd.","Bluffton","SC",29910,"","705-3773","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Senior Citizens - Bluffton","","SUN","recipient","PO Box 158; c/o Vivian Morton","Bluffton","SC",29910,"","757-1508","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Vivian Morton"); insert_dbClients($c);
$c=new Client("St. Anthonys – Hardeeville","","SUN","recipient","","Hardeeville","SC","","","","","Wed","no","pounds",""); insert_dbClients($c);
$c=new Client("St. Gregory","","SUN","recipient","333 Fording Island Rd","Bluffton","SC",29910,"","815-3100","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("St. Stephens-- Ridgeland","","SUN","recipient","PO Box 2075 Hgwy.13","Ridgeland","SC",29936,"","","","Mon,Thu,Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Tabernacle Baptist Church","","SUN","recipient","PO Box 4035; C/O Gertrude Bro","Beaufort","SC",29903,"","522-3465","","Tue","no","pounds","Gertrude Brown"); insert_dbClients($c);
$c=new Client("Walgreens - Sun City","","SUN","recipient","","Okatie","SC",29909,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
		
}

?>
</body>
</html>