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
	
// add me
        $vol5 = new Volunteer("Tucker", "Allen", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "8433423118", "", 
    				"allen@bowdoin.edu", "driver,teamcaptain", "active", "HHI", "567890123","SC", "14-01-29", "", "Sat,Fri","",
    				"", "59-01-01","98-01-01", "", "");
		insert_dbVolunteers($vol5);

// add live volunteer data
$v=new Volunteer("Barnes","Lynn","PO Box 1514","Beaufort","SC",29901,"8438461739","","LMB198@embarqmail.com","driver","active","BFT","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Benton","Kevin","3033 Badgers Bend","Beaufort","SC",29902,"8439866447","8439866447","brickbuilder@embarqmail.com","driver","active","BFT","SC 008919397","SC",01/16/17,"","","","",01/13/93,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Brown","William","26 Ramsey Loop","Beaufort","SC",29906,"8435229025","","mrbrown@embarqmail.com","driver","active","BFT","SC 001666900","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Brown, Jr.","Joseph","1401 Harrington St.","Beaufort","SC",29902,"8435250207","8432634890","jsphbrownjr@yahoo.com","driver","active","BFT","SC007846212","SC",07/15/11,"","","","",07/15/52,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Cedeno","Ruben","15615 PULASKI Ct.","Beaufort","SC",29906,"8435223794","","","driver","active","BFT","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Cesar","Garcia","692 Summer Drive","Beaufort","SC",29906,"8433791522","","ap1@hargray.com","driver,teamcaptain","active","BFT","SC 100549129","SC",04/25/14,"","","","",04/25/55,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Culp","Bill","2208 Bay Street","Beaufort","SC",29902,"8432636934","","wbacjr@gmail.com","driver","active","BFT","NC 1644883","SC",08/29/13,"","","","",08/29/43,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Buterbaugh","Bob","36 Spanish Pointe Dr","Hilton Head Island","SC",29926,"8436818115","8434223296","robertbuterbaugh@earthlink.net","driver,boardmember","active","HHI","SC2456090","SC",10/01/20,"","","","",10/01/52,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Campbell","Laura","88 S. Port Royal Drive","Hilton Head Island","SC",29928,"8436816751","","LauraC4000@gmail.com","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Cole","Richard","Hiilton Head Hospital","Hilton Head Island","SC",29926,"8436898261","","richard.cole@tenethealth.com","driver","active","HHI","","","","","Sat","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Coleman","Bob","17 Lagoon  #39","Hilton Head Island","SC",29928,"8433412430","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Coleman","Jim","8 Sandhill Crane","Hilton Head Island","SC",29928,"8436897008","","tgault@thecypress.com","driver","active","HHI","","","","","Sat","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Cooley","Ed","73 Ocean Breeze","Hilton Head Island","SC",29928,"8437857485","","j01cooley@aol.com","driver","active","HHI","","","","","","","","","","Jackie",""); insert_dbVolunteers($v);
$v=new Volunteer("Corderman","Don","6 Deerfield Court","Hilton Head Island","SC",29926,"8433422938","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Crunkleton","Paul","7 Pine Island Court","Hilton Head Island","SC",29928,"8433636883","8432900495","paulcrunk@aol.com","driver","active","HHI","SC 100767567","SC",06/04/15,"","Sat:1","","",06/04/41,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Daily","Keven","9 Twin Pines Court","Hilton Head Island","SC",29928,"9494228245","","kevin_daily@ml.com","driver","active","HHI","SC102680538","SC",01/11/21,"","Sat","","",11/11/59,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("DiCanio","Vince","552beauty Colonial Drive","Hilton Head Island","SC",29926,"8437152199","","vinrose1@gmail.com","driver","active","HHI","sc 101908262","SC","","","","","",04/03/53,"","Rosemarie",""); insert_dbVolunteers($v);
$v=new Volunteer("Dishart","Ed","530 Colonial Drive","Hilton Head Island","SC",29926,"8433425530","","edhiltonhead@roadrunner.com","driver","active","HHI","SC 7952990","SC","","","Thu","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Dix","Bill","294 Seabrook Drive","Hilton Head Island","SC",29926,"8436896778","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Drake","Bruce","17 Santa Maria Drive","Hilton Head Island","SC",29926,"8436818774","","","driver,boardmember","active","HHI","","","","","","","","","","Norma",""); insert_dbVolunteers($v);
$v=new Volunteer("Duffy","John","113 Governors Road","Hilton Head Island","SC",29928,"8436712518","","beachduff@aol.com","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Dyer","Ernie","18 Christo Drive","Hilton Head Island","SC",29926,"8436816959","","jmgd@hargray.com","driver","active","HHI","SC  003704908","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Ehmke","Lane","33 Governors Road","Hilton Head Island","SC",29928,"8436712180","","slim46@roadrunner.com","driver","active","HHI","SC  011630751","SC","","","Fri","","",07/14/46,"","Sue",""); insert_dbVolunteers($v);
$v=new Volunteer("Eickhoff","Ken","6 Knollwood Drive","Hilton Head Island","SC",29926,"8436815965","","","driver","active","HHI","","","","","","","","","","Jytte",""); insert_dbVolunteers($v);
$v=new Volunteer("Ekedahl","Dave","28 Ocean Point North","Hilton Head Island","SC",29928,"8436816125","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Farkasovsky","Stephen","4 Lenora Drive","Hilton Head Island","SC",29926,"8436818151","8433685602","hiltonhead66@hotmail.com","driver","active","HHI","SC 011696046","SC",04/29/12,"","","","",04/29/49,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Farren","Ed","105 Wedgefield Drive","Hilton Head Island","SC",29926,"8433424426","","edfarren@roadrunner.com","driver","active","HHI","","","","","","","","","","Jerilyn",""); insert_dbVolunteers($v);
$v=new Volunteer("Farrington","John","","Hilton Head Island","SC",29928,"8433417640","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Fearman","Don","554 Colonial Drive","Hilton Head Island","SC",29926,"8436815479","","dmf7474@roadrunner.com","driver","active","HHI","SC 007957136","SC","","","Thu","","",10/11/39,"","Michele",""); insert_dbVolunteers($v);
$v=new Volunteer("Fielitz","Bob","15 Heather Lane","Hilton Head Island","SC",29926,"8433426363","","fielitzre@hargray.com","driver","active","HHI","SC 011240617","SC","","","Thu","","",03/13/41,"","Bette",""); insert_dbVolunteers($v);
$v=new Volunteer("Fink","Carl","3 Sussex Lane","Hilton Head Island","SC",29926,"8433427497","8433389591","carlfink@roadrunner.com","driver","active","HHI","SC 011672337","SC","","","Thu","","",11/04/42,"","Lorraine",""); insert_dbVolunteers($v);
$v=new Volunteer("Finkenstadt","Ernie","688 Colonial Drive","Hilton Head Island","SC",29926,"8436815844","","erniefink@roadrunner.com","driver","active","HHI","","","","","Wed","","","","","Doris",""); insert_dbVolunteers($v);
$v=new Volunteer("Fortin","Joe","3 Trails End","Hilton Head Island","SC",29926,"8436812318","","fortinhhi@yahoo.com","driver,boardmember","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Fortney","Don","8 Fox Lane","Hilton Head Island","SC",29928,"8432981887","","dzfortney@yahoo.com","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Fullerton","Clutch","20 Sunset Drive","Hilton Head Island","SC",29926,"8436812145","","nancyfullerton@hargray.com","driver","active","HHI","","","","","","","","","","Nancy",""); insert_dbVolunteers($v);
$v=new Volunteer("Geisler","John","10 Sherman Place","Hilton Head Island","SC",29928,"8436819577","","JGeislerHH@aol.com","driver,boardmember","active","HHI","SC  011172009","SC","","","","","","","","Joan",""); insert_dbVolunteers($v);
$v=new Volunteer("Ghirardelli","Bob","66 Lawton Road","Hilton Head Island","SC",29928,"8433636404","","bobghirardelli@aol.com","driver","active","HHI","","","","","","","","","","Ginny",""); insert_dbVolunteers($v);
$v=new Volunteer("Green","Bob","6 Anna Court","Hilton Head Island","SC",29926,"8436815269","","boblyn4@aol.com","driver","active","HHI","SC  007839388","SC","","","Mon","","","","","Lynn",""); insert_dbVolunteers($v);
$v=new Volunteer("Haff","Doug","26 Bateau","Hilton Head Island","SC",29928,"8434224348","","dhaff2@yahoo.com","driver","active","HHI","SC 100083977","SC",12/17/16,"","Wed","","",12/17/54,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Hahn","Rick","8 Club Course Drive","Hilton Head Island","SC",29928,"8436714077","8433389085","rick8@roadrunner.com","driver","active","HHI","SC 011692616","SC",12/13/17,"","","","",12/13/45,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Hartman","Trace","26 Sand Fiddler Road","Hilton Head Island","SC",29928,"8436711694","8434223515","thhhi@aol.com","driver","active","HHI","SC 011196808","SC","","","Fri","","",01/24/43,"","Karen",""); insert_dbVolunteers($v);
$v=new Volunteer("Hayward","Rick","13 Heather Lane","Hilton Head Island","SC",29926,"8433427301","","haywardhhi@hargray.com","driver","active","HHI","","","","","Thu","","","","","Marta",""); insert_dbVolunteers($v);
$v=new Volunteer("Higdon","Inactive","6 Fishermans Bend Court","Hilton Head Island","SC",29926,"8436811811","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Hirsch","Jim","662 Colonial Drive","Hilton Head Island","SC",29926,"8436896998","","cj662@aol.com","driver,boardmember","active","HHI","SC 100195091","SC",02/04/13,"","","","","","","Pat",""); insert_dbVolunteers($v);
$v=new Volunteer("Honts","Floyd","21 Durban Place","Hilton Head Island","SC",29926,"8436817141","","fhonts@hargray.com","driver","active","HHI","SC  100321249","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Hoppenrath","Bill","26 South Forest Beach Drive","Hilton Head Island","SC",29928,"8437853029","3092562672","bhoopy15@gmail.com","driver","active","HHI","SC 102111353","SC",09/07/19,"","Wed","","",09/07/58,"","Kathy",""); insert_dbVolunteers($v);
$v=new Volunteer("Hutten","Steve","PO Box 6193","Hilton Head Island","SC",29938,"8436831415","","grumpyhhi@yahoo.com","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Hynes","Bob","39 Catesworth Place%","Hilton Head Island","SC",29926,"8436815956","","bhynes@jhargray.com","driver","active","HHI","SC 100224304","SC",11/01/13,"","Sat","","","","","Barbara",""); insert_dbVolunteers($v);
$v=new Volunteer("Israel","Tom","99 Birdsong Way #D107","Hilton Head Island","SC",29926,"8436818082","","tisrael@hargray.com","driver","active","HHI","SC 003819696","SC",03/31/11,"","","","","","","Joan",""); insert_dbVolunteers($v);
$v=new Volunteer("John","Glyn","9 St. Andrews Place","Hilton Head Island","SC",29928,"8433636841","","glynjohn@hargray.com","driver","active","HHI","scdl 011134475","SC",12/29/13,"","Fri,Sat","","",12/29/44,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Johnston","Bill","6 Cypress Marsh Drive","Hilton Head Island","SC",29926,"8433423053","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Jones","Gerri","11 Eagle Claw Lane","Hilton Head Island","SC",29926,"8436815572","","hhijones@roadrunner.com","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Jones","Stu","77 Birdsong Way  Apt. C202","Hilton Head Island","SC",29926,"8436813541","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Keck","Jerry","7 Royal James","Hilton Head Island","SC",29926,"8438427065","","jerrykeck@roadrunner.com","driver","active","HHI","","","","","Tue,Sat","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Keefner","Rich","137 Headlands Drive","Hilton Head Island","SC",29926,"8436895344","8433388891","rckeefner@gmail.com","driver","active","HHI","SC011173633","SC",05/14/13,"","Mon","","","","","Gretchen",""); insert_dbVolunteers($v);
$v=new Volunteer("Kefner","Rick","137 Headlands Dr.","Hilton Head Island","SC",29926,"8436895344","","","driver","active","HHI","","","","","","","","","","Gretchan",""); insert_dbVolunteers($v);
$v=new Volunteer("Kelly","Jim","596 Colonial Drive","Hilton Head Island","SC",29926,"8436822355","","kelly.jim@roadrunner.com","driver,boardmember","active","HHI","SC","SC","","","","","","","","Nell",""); insert_dbVolunteers($v);
$v=new Volunteer("Kennard","Bill","35 Outpost Lane","Hilton Head Island","SC",29928,"8433426910","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Leo","Dennis","30 Tupelo Road","Hilton Head Island","SC",29928,"8436712049","8433014910","hhileo@roadrunner.com","driver,boardmember","active","HHI","SC  011435254","SC",05/25/16,"","","","",05/25/37,"","Kay",""); insert_dbVolunteers($v);
$v=new Volunteer("Levesque","Jim","13 Telford Lane","Hilton Head Island","SC",29926,"8433427899","","jsleve@roadrunner.com","driver,boardmember","active","HHI","","","","","Thu","","","","","Sue",""); insert_dbVolunteers($v);
$v=new Volunteer("Many","Richard","15 Wood  Duck Road","Hilton Head Island","SC",29928,"8433635444","","mareandrich@yahoo.com","driver","active","HHI","","","","","Fri","","","","","Maryann",""); insert_dbVolunteers($v);
$v=new Volunteer("Matheson","Fred","63 Saw Timber Drive","Hilton Head Island","SC",29926,"8438374874","","fgmath@hargray.com","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Mazzei","Joe","23 China Cockle Way","Hilton Head Island","SC",29926,"8436892422","","joemazzei@aol.com","driver","active","HHI","SC 100595636","SC","","","Mon","","","","","Claire",""); insert_dbVolunteers($v);
$v=new Volunteer("McGrew","Gene","5 Fox Den Court","Hilton Head Island","SC",29926,"8436818971","","gwmcgrew@aol.com","driver","active","HHI","","","","","","","","","","Patti",""); insert_dbVolunteers($v);
$v=new Volunteer("McMahon","Kevin","55 Kingston Dunes","Hilton Head Island","SC",29928,"8438425623","","kevinmcm@hargray.com","driver","active","HHI","SC  007555353","SC","","","Sat:1","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Meahen","Alan","2 Old Fort Lane","Hilton Head Island","SC",29926,"8436896779","","meahen@hargray.com","driver","active","HHI","","","","","","","","","","Pam",""); insert_dbVolunteers($v);
$v=new Volunteer("Melnick","Mike","13 Genoa Court","Hilton Head Island","SC",29928,"8436715006","","mikejem32@yahoo.com","driver","active","HHI","SC100800865","SC",07/30/15,"","Fri","","",07/30/44,"","Elleri",""); insert_dbVolunteers($v);
$v=new Volunteer("Michell","Hank","14 Planters Wood Drive","Hilton Head Island","SC",29928,"8433632122","","hmichell@michells.com","driver","active","HHI","SC  1001494110","SC","","","Sat:2","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Nicolazzi","Judy","21 Governors Lane","Hilton Head Island","SC",29928,"8436716205","","judibobn@aol.com","driver","active","HHI","","","","","Mon","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Nissi","Paul","3 Fairfax Lane","Hilton Head Island","SC",29928,"8438422134","","pnissi@aol.com","driver","active","HHI","SC 003142412","SC",07/15/11,"","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Nowacek","John","7 Fiddler Lane","Hilton Head Island","SC",29926,"8436895053","","jenowacek@earthlink.net","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Orlosky","Ron","1 Marshview Drive","Hilton Head Island","SC",29928,"8436716756","8438057924","orlo2468@roadrunner.com","driver","active","HHI","SC 101559638","SC",01/22/17,"","Fri","","",01/22/51,"","single",""); insert_dbVolunteers($v);
$v=new Volunteer("Parker","Les","18 Red Oak Road","Hilton Head Island","SC",29928,"8436716256","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Parsons","Tim","27 Lenora Drive","Hilton Head Island","SC",29926,"8436824162","","trpars12@gmail.com","driver","active","HHI","SC011705152","SC",09/27/12,"","","","","","","Rosie",""); insert_dbVolunteers($v);
$v=new Volunteer("Peters","Ron","10 S. Brayford Court","Hilton Head Island","SC",29928,"8438156472","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Peterson","Bob","4 Viscount Court","Hilton Head Island","SC",29928,"8436813518","","carolemph@roadrunner.com","driver","active","HHI","SC  100296627","SC","","","Wed","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Piccinni","Lesia","8 Saw Timber Drive","Hilton Head Island","SC",29926,"8438361295","","","driver","active","HHI","SC  100418636","SC","","","Sat:4","","","","","Russ",""); insert_dbVolunteers($v);
$v=new Volunteer("Pierpoli","Inactive","3 Fallen Arrow","Hilton Head Island","SC",29926,"8433639209","","pgprx1@hargray.com","driver","active","HHI","SC  007994340","SC","","","Sat:4","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Polk","Bill","126 Wedgefield Drive","Hilton Head Island","SC",29926,"8433427791","","putnamw@hargray.com","driver","active","HHI","SC 01291815","SC",05/07/14,"","Thu","","","","","Nancy",""); insert_dbVolunteers($v);
$v=new Volunteer("Prol","Dave","31 Seabrook Landing Dr.","Hilton Head Island","SC",29928,"8436896950","","picaperci@islc.net","driver","active","HHI","","","","","","","","","","Arlene",""); insert_dbVolunteers($v);
$v=new Volunteer("Quirk","John","11 Stable Gate Road","Hilton Head Island","SC",29926,"8438362892","","johnquirkhhi@gmail.com","driver","active","HHI","","","","","","","","","","Kathy",""); insert_dbVolunteers($v);
$v=new Volunteer("Radomski","Jean","38 Sedge Fern Drive","Hilton Head Island","SC",29926,"8436893809","","jradhhi@aol.com","driver,boardmember","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Rathke","Bob","16 Newhall Rd","Hilton Head Island","SC",29928,"8436715023","5852651291","rrathke@rathke.com","driver","active","HHI","","","","","","","","","","Nancy",""); insert_dbVolunteers($v);
$v=new Volunteer("Ray","Rick","33 S. Beach Lagoon road","Hilton Head Island","SC",29928,"8433636334","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Reilly","George","152 S. Port Royal Drive","Hilton Head Island","SC",29926,"8436838510","","geo1038@aol.com","driver,boardmember","active","HHI","SC  007279136","SC","","","Tue","","",10/24/38,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("RewGifford","Linda","PO Box 5511","Hilton Head Island","SC",29938,"8437579889","","linda@hiltonhead.com","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Richardson","John","71 Deerfield Road","Hilton Head Island","SC",29926,"8433422905","","jonwalta@aol.com","driver","active","HHI","","","","","","","","","","Alta",""); insert_dbVolunteers($v);
$v=new Volunteer("Rieck","Clarke","8 Anglers Pond Court","Hilton Head Island","SC",29926,"8436811831","","hhcrieck@aol.com","driver","active","HHI","SC  007796044","SC","","","Mon","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Riggs","Chuck","25 Belted Kingfisher","Hilton Head Island","SC",29928,"8436716663","","criggs@optonline.net","driver,boardmember","active","HHI","SC  100941110","SC","","","Mon","","",07/22/40,"","Shelia",""); insert_dbVolunteers($v);
$v=new Volunteer("Robinson","Herschel","608 Colonial Drive","Hilton Head Island","SC",29926,"8436824664","","hbrbgr@aol.com","driver","active","HHI","","","","","Thu","","","","","Brenda",""); insert_dbVolunteers($v);
$v=new Volunteer("Roll","Bob","16 Lenora Drive","Hilton Head Island","SC",29926,"8433429192","","rollhhi@yahoo.com","driver","active","HHI","","","","","","","","","","Betsy",""); insert_dbVolunteers($v);
$v=new Volunteer("Russell","Docl","288 Long Cove Drive","Hilton Head Island","SC",29928,"8437859239","","","driver","active","HHI","","","","","","","",05/06/12,"","Marie",""); insert_dbVolunteers($v);
$v=new Volunteer("Schaleuly","David","32 Fairway Winds Place","Hilton Head Island","SC",29928,"8436823554","8434418082","d.shaleuly@gmail.com","driver","active","HHI","SC 100553411","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Schauf","David","6 Snowy Egret","Hilton Head Island","SC",29925,"8436712960","","","driver","active","HHI","","","","","Tue","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Schoffner","Jim","263 Greenwood Drive","Hilton Head Island","SC",29926,"8436716803","","","driver","active","HHI","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Schroeter","Gerry","35 South Beach Lane","Hilton Head Island","SC",29928,"8436719339","","gschroe421@aol.com","driver","active","HHI","SC 007947332","SC",07/02/12,"","Mon","","","","","Betsy",""); insert_dbVolunteers($v);
$v=new Volunteer("Shaleuly","David","32 Fiarway Winds Place","Hilton Head Island","SC",29928,"8434418082","","d.shaleuly@gmail.com","driver","active","HHI","","","","","","","","","","Sandra",""); insert_dbVolunteers($v);
$v=new Volunteer("Algar","Bruce","33 Landing Lane","Bluffton","SC",29909,"8437057101","","scboatman@hotmail.com","driver","active","SUN","","","","","","","","","","Judy",""); insert_dbVolunteers($v);
$v=new Volunteer("Anderson","Bob","11 Raymond Rd.","Bluffton","SC",29909,"8437055510","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Barnedette","Matali","20 Nightingale Lane","Bluffton","SC",29909,"6313754116","","bernski27@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Barnes","Greg","45 Spring Beauty Drive","Bluffton","SC",29909,"8437053068","","gbarnes.701@gmail.com","driver","active","SUN","","","","","","","","","","Terri",""); insert_dbVolunteers($v);
$v=new Volunteer("Barrett","Tricia","7 Raymond Road","Bluffton","SC",29909,"8437054948","","triciadon@hargray.com","driver","active","SUN","SC  007449038","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Bartlett","Fred","120 Spring Boaty Drive","Bluffton","SC",29909,"8437055864","","bartfamily@hotmail.com","driver","active","SUN","","","","","","","","","","Karen",""); insert_dbVolunteers($v);
$v=new Volunteer("Bergenthal","Jim","27 Walden Lane","Bluffton","SC",29909,"8437057444","","poppy6x@sc.rr.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Binazeski","Peter","9 Ceres Court","Bluffton","SC",29909,"8437056752","","petebinazeski@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Blackstone","Jim","24 Cypress Hollow","Bluffton","SC",29909,"8437053376","","blkstn@hargray.com","driver","active","SUN","SC 100356610","SC","","","","","","","","Marilyn",""); insert_dbVolunteers($v);
$v=new Volunteer("Brandon","Steve","290 Landing Lane","Bluffton","SC",29909,"8437055868","8473540322","sbrandon4@sc.rr.com","driver","active","SUN","SC 102130838","SC",02/15/14,"","","","",02/15/42,"","Margie",""); insert_dbVolunteers($v);
$v=new Volunteer("Brennan","Pat","76 Biltmore Drive","Bluffton","SC",29909,"8437056836","","pbrennan@hargray.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Clark","Dudley","3 Perry Circle","Bluffton","SC",29909,"8437052595","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Clark","Mike","1086 Rivergrass Lane","Bluffton","SC",29909,"8437077226","","mlmclarke1@gmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Clarke","Bill","1086 Rivergrass Lane","Bluffton","SC",29909,"","","ardenec@gmail.com","driver","active","SUN","","","","","","","","","","Ardene",""); insert_dbVolunteers($v);
$v=new Volunteer("Coyne","Michael","23 Sunbeam Drive","Bluffton","SC",29909,"8437055904","4043680745","mikecoyne26@yahoo.com","driver","active","SUN","SC 011193631","SC",08/16/13,"","","","",08/16/36,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Cullie","Dick","3 Raven Glass Lane","Bluffton","SC",29909,"8432984251","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("DAmbrosi","Ron","85 Hampton Circle","Bluffton","SC",29909,"8437057713","","brantlake@aol.com","driver","active","SUN","NY  ID175606029","SC","","","","","","","","Joan",""); insert_dbVolunteers($v);
$v=new Volunteer("DAnza","Frank","15 Raindrop Lane","Bluffton","SC",29909,"8437054975","7166338133","fdeagro@mac.com","driver","active","SUN","SC 102442128","SC","","","","","","","","Paula",""); insert_dbVolunteers($v);
$v=new Volunteer("Daparma","Claudia","29 Dragonway Dr.","Bluffton","SC",29909,"8437059876","","claudy12350@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Depalma","Claudia","98 Dragon Fly","Bluffton","SC",29909,"5166039876","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Dick","Jack","24 Reedy Place","Bluffton","SC",29909,"8437053923","","jackiedee@hargray.com","driver","active","SUN","SC 101907662","SC",04/25/18,"","","","",04/25/48,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Doherty","Dan","","Bluffton","SC",29909,"8437057813","","dod1950@hotmail.com","driver","active","SUN","","","","","","","","","","Valery",""); insert_dbVolunteers($v);
$v=new Volunteer("Dolan","Paul","150 Argent Way","Bluffton","SC",29909,"8437056224","","blufftondolan@aol.com","driver","active","SUN","","","","","","","","","","Mimi",""); insert_dbVolunteers($v);
$v=new Volunteer("Douglass","Don","11 Spring Beauty Drive","Bluffton","SC",29909,"8437057813","","ded1952@hotmail.com","driver","active","SUN","SC 003352592","SC",10/18/17,"","","","",10/19/52,"","Valery",""); insert_dbVolunteers($v);
$v=new Volunteer("Dziomba","Lawrence","122 Spring Beauty Dr.","Bluffton","SC",29909,"8437077128","","dizzylarry1@yahoo.com","driver","active","SUN","SC 102286457","SC",09/11/19,"","","","",09/11/51,"","single",""); insert_dbVolunteers($v);
$v=new Volunteer("Egoroft","Carol","606 Argent Way","Bluffton","SC",29909,"8437055552","","carolorherb@gmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Erbacher","Herman","43 Redtail Drive","Bluffton","SC",29909,"8437052470","","","driver","active","SUN","","","","","","","","","","Arlene",""); insert_dbVolunteers($v);
$v=new Volunteer("Faldermeyer","Jack","27 Red Tail Drive","Bluffton","SC",29909,"8437057852","","","driver","active","SUN","","","","","Mon","","","","","separated",""); insert_dbVolunteers($v);
$v=new Volunteer("Garrigan","Ed","39 Blackstone Rive Rd.","Bluffton","SC",29909,"8437576123","8044363369","egarrigan6@aol.com","driver","active","SUN","SC 101649616","SC",05/11/12,"","","","",05/11/38,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Geisler","Don","17 Triscot Lane","Bluffton","SC",29909,"8437053337","","don3245@hargray.com","driver","active","SUN","","","","","","","","","","Margie",""); insert_dbVolunteers($v);
$v=new Volunteer("Griswold","Paul","145 Co. Thomas Heyward","Bluffton","SC",29909,"8437056317","","pgriswold@aol.com","driver,boardmember","active","SUN","","","","","Mon","","","","","Margaret",""); insert_dbVolunteers($v);
$v=new Volunteer("Groncki","Joe","87 Thomas Bee Drive","Bluffton","SC",29909,"8437056609","","bgroncki32@msn.com","driver","active","SUN","SC 101877294","SC",12/11/13,"","","","",12/11/40,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Groncki","Joe","87 Thomnas Bee Drive","Bluffton","SC",29909,"8437056609","","bgroncki32@msn.com","driver","active","SUN","","","","","","","","","","Bunny",""); insert_dbVolunteers($v);
$v=new Volunteer("Gruel","Edward","103 Nightingale Lane","Bluffton","SC",29909,"8437057679","6318182055","ed.gruel@yahoo.com","driver","active","SUN","SC 101958804","SC",04/20/18,"","","","",04/20/47,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Higgins","Thomas","21 Tourquay Lane","Bluffton","SC",29909,"8435480707","","thiggins3@sc.rr.com","driver","active","SUN","","","","","","","","","","Ann",""); insert_dbVolunteers($v);
$v=new Volunteer("Hilborn","Bill","74 Padgett Dr.","Bluffton","SC",29909,"8437057484","","wbhilborn@gmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Jordan","Matt","65 Concession Oak Drive","Bluffton","SC",29909,"8437053573","","kaixo0823@yahoo.com","driver","active","SUN","","","","","","","","","","Joann",""); insert_dbVolunteers($v);
$v=new Volunteer("Kendall","Tom","28 Mooring Line Place","Bluffton","SC",29910,"8436822228","","tkendall@hargray.com","driver","active","SUN","SC 100014948","SC","","","Thu","","",09/21/47,"","Fab",""); insert_dbVolunteers($v);
$v=new Volunteer("Korz","Stephen","28 Willard Brook Dr.","Bluffton","SC",29909,"8437053496","","stevenkorz@sc.rr.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Krause","Ed","70 Kings Creek Dr.","Bluffton","SC",29909,"8437055049","","edk543@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("LaPierre","Kevin","57 Crescent Creek Dri","Bluffton","SC",29909,"8437077615","","klbear1@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Lapp","Lorin","44 Penny Creek","Bluffton","SC",29909,"8437051959","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Lopp","Loran","44 Penny Creek Drive","Bluffton","SC",29909,"8437051959","","rllopp@gmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Magner","Jim","127 Stratford Village Way","Bluffton","SC",29909,"8437052593","","strinteg1@yahoo.com","driver","active","SUN","SC 100122124","SC",01/24/14,"","","","","","","Catherine",""); insert_dbVolunteers($v);
$v=new Volunteer("McCaw","James","18 Plymouth Lane","Bluffton","SC",29909,"8437057054","","jmccaw2@sc.rr.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Metzger","Roger","4 Cypress Hollow","Bluffton","SC",29909,"8437052305","8435402304","roger64vette@hotmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Miller","Bill","2 Rain Drop Lane","Bluffton","SC",29909,"8437055674","","bkmill2571@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Nagy","Jim","92 Hampton Circle","Bluffton","SC",29909,"8437056142","","jcnagyschh@gmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Natale","Joe","30 Fenwick Drive","Bluffton","SC",29909,"8437053393","","joenatale7@gmail.com","driver","active","SUN","SC  011154370","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Onda","Alexander","24Huquenin Lane","Bluffton","SC",29909,"8437052341","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Oros","Joe","215 Stratford Village Way","Bluffton","SC",29909,"8437052128","","jomaroros@islc.net","driver","active","SUN","SC 011565259","SC","","","","","",07/02/34,"","Mary",""); insert_dbVolunteers($v);
$v=new Volunteer("Ortiz","Julio","7 Cypress Hollow","Bluffton","SC",29909,"8437053801","","juliozitro@gmail.com","driver","active","SUN","SC 101247951","SC","","","","","","","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("Palcic","Bill","20 Devant Dr. E.","Bluffton","SC",29909,"8437059950","","wpalcic@sc.rr.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("pallisco","Gina","9 Summerplace Dr.","Bluffton","SC",29909,"8437050642","","fpc132@hargray.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Patchen","judy","55 Purry Circle","Bluffton","SC",29909,"8437053006","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Peluso","Jon","25 Tourquay Lane","Bluffton","SC",29909,"8437053909","8432904334","jon25T@aol.com","driver,teamcaptain,boardmember","active","SUN","SC 100618480","SC",03/21/14,"","","","","","","Gracene",""); insert_dbVolunteers($v);
$v=new Volunteer("Penbroke","Sonja","9 Southern Red Road","Bluffton","SC",29909,"8437053662","","sjrpsc@hargray.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Petersen","Bob","3 Bella Vista Court","Bluffton","SC",29909,"8437059499","","rpetersen3@sc.rr.com","driver,boardmember","active","SUN","SC 011414137","SC",01/06/15,"","","","",01/06/31,"","Dolores",""); insert_dbVolunteers($v);
$v=new Volunteer("Ragland","Bill","149 Stratford Village Way","Bluffton","SC",29909,"8433682894","","brag64@aol.com","driver","active","SUN","SC 100168137","SC",05/31/13,"","","","","","","Elaine",""); insert_dbVolunteers($v);
$v=new Volunteer("Raney","Ed","8 Wisteria Lane","Bluffton","SC",29909,"8437077085","","raney42@gmail.com","driver","active","SUN","SC 007957848","SC",06/07/17,"","Mon","","",06/07/42,"","Judy",""); insert_dbVolunteers($v);
$v=new Volunteer("Reinhard","Karen","17 Bailey Lane","Bluffton","SC",29909,"8437052126","","kvr19460@hotmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Resetarits","Dan","199 Argent Way","Bluffton","SC",29909,"8437052960","","dad.rez2@hargray.com","driver","active","SUN","","","","","","","","","","Marilyn",""); insert_dbVolunteers($v);
$v=new Volunteer("Resetarits","Dan","199 Argent  Way","Bluffton","SC",29909,"8437052960","","dad.rez2@hargray.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Rhyan","John","","Bluffton","SC",29909,"","","jr4803@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Richert","John","31 Hampton Circle","Bluffton","SC",29909,"8437059284","","jmrsuncity@hargray.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Roeser","Joe","100 Coburn Dr. W.","Bluffton","SC",29909,"8437059223","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Rupp","George","25 Ansley Place","Bluffton","SC",29909,"8437052016","","","driver","active","SUN","","","","","","","","","","June",""); insert_dbVolunteers($v);
$v=new Volunteer("Ryan","Tom","124 Hampton Circle","Bluffton","SC",29909,"8437053674","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Sanders","Jeff","85 Biltmore","Bluffton","SC",29909,"","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Schooner","Joe","","Bluffton","SC",29909,"8437056549","","jkschooner@localnet.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Schwiebert","David","29 Tallow Dr.","Bluffton","SC",29909,"8437057815","","sandave@sc.rr.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Scully","Bud","167 Pinacle Shores Drive","Bluffton","SC",29909,"9196242405","","Bud_scully@yahoo.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Seibert","Wes","16 Raymond Road","Bluffton","SC",29909,"8437055540","","mwseibert@juno.com","driver","active","SUN","SC011503338","SC","","","","","",09/18/34,"","Marzie",""); insert_dbVolunteers($v);
$v=new Volunteer("Sellers","Tom","35 Crescent Creek Drive","Bluffton","SC",29909,"8437057174","","tsellers@sc.rr.com","driver","active","SUN","SC 101770555","SC","","","","","",06/30/41,"","Evelyn",""); insert_dbVolunteers($v);
$v=new Volunteer("Shaw","Bill","48 Vespers Way","Bluffton","SC",29909,"8437059818","","bdshaw@hargray.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Sherwood","Bill","10 Strobhar Street","Bluffton","SC",29909,"8437055527","","carolnbill@hargray.com","driver","active","SUN","","","","","Mon","","","","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("Sisk","Ray","19 Landing Lane","Bluffton","SC",29909,"8437053426","","sisk62@sc.rr.com","driver","active","SUN","SC 100520737","SC","","","","","","","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("Sneed","John","","Bluffton","SC",29909,"8437059424","","sneeds@yahoo.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Steger","Gary","5 Starling Cirlce","Bluffton","SC",29909,"8437057917","","steger@hargray.com","driver","active","SUN","SC 100906629","SC",02/07/15,"","","","",02/07/43,"","",""); insert_dbVolunteers($v);
$v=new Volunteer("Stevenson","Ted","215 Landing Lane","Bluffton","SC",29909,"8437055290","","dntstevenson@yahoo.com","driver","active","SUN","","","","","","","","","","Diane",""); insert_dbVolunteers($v);
$v=new Volunteer("stombler","Milton","7 Murray Hill Drive","Bluffton","SC",29909,"8437059193","","stombler@gmail.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("sweibert","David","","Bluffton","SC",29909,"8437057815","","sandave@sc.rr.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Thomas","Earl","122 Pennycreek Drive","Bluffton","SC",29909,"8437057419","","earlt50@aol.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Tullie","Dick","3 Ravenglass Lane","Bluffton","SC",29909,"8437050233","","dicktullie@gmail.com","driver","active","SUN","","","","","Mon","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Valentin","Ziggy","11 Tourquay Lane","Bluffton","SC",29909,"8437052174","8432902163","sigiv@sc.rr.com","driver","active","SUN","SC 011673773","SC",06/19/12,"","","","",06/19/35,"","widowed: Ingred",""); insert_dbVolunteers($v);
$v=new Volunteer("Vogt","Karl","242 Seventy Point Dr.","Bluffton","SC",29909,"8436456534","","highten324@hargray.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Walsh","Jim","52 Raven Glass Lane","Bluffton","SC",29909,"8437056181","","kowalsa@yahoo.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Wheeler","John","127 Col. Thomas Heyward Rd.","Bluffton","SC",29909,"8437057747","","","driver","active","SUN","","","","","","","","","","Ellen",""); insert_dbVolunteers($v);
$v=new Volunteer("White","Richard","","Bluffton","SC",29909,"","","rich16white@juno.com","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Willner","Les","119 Robert E. Lee Lane","Bluffton","SC",29909,"8437055147","","eggman@hargray.com","driver,boardmember","active","SUN","SC  007746580","SC",11/19/11,"","","","","","","Millie",""); insert_dbVolunteers($v);
$v=new Volunteer("Wright","Willi","23 Hamilton Drive","Bluffton","SC",29909,"8437056009","","","driver","active","SUN","","","","","","","","","","",""); insert_dbVolunteers($v);
				
// add some live client data
$c=new Client("BiLo  Boundary Street #158","BiLo","BFT","donor","2127 Boundry St.","Beaufort","SC",29902,"","5242771","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Bill Bradsaw"); insert_dbClients($c);
$c=new Client("BiLo  Shell Point # 525","BiLo","BFT","donor","860 Parris Isl.Gateway","Beaufort","SC",29902,"","5242300","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Bimbo Bakery","","BFT","donor","45 Laurel Bay Rd.","Beaufort","SC",29906,"","3210429","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("CocaCola Bottling Co.","","BFT","donor","1840 Ribaut Rd","Port Royal","SC",29935,"","8435256293","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion  Ladies Island #945","Food Lion","BFT","donor","313 Laural Bay Rd.","Beaufort","SC",29906,"","8469994","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion  Laurel Bay #1698","Food Lion","BFT","donor","10 Sams Point Rd.","Ladys Island","SC",29907,"","5214525","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Longs Processing Facility","","BFT","donor","8925 Browning Gate Rd.","Varnville","SC",29944,"","8036254450","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Pepsi Bottling Company","","BFT","donor","287 Braod River Rd.","Beaufort","SC",29902,"","5211424","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Piggly Wiggly  Boundry","","BFT","donor","123 Boundry St.","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Pizza Hut  Inactive","","BFT","donor","2433 Boundry St.","Beaufort","SC",29902,"","5247948","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Publix #623 Beaufort","Publix","BFT","donor","163 Sea Island Pkwy","Ladys Island","SC",29907,"","9865061","","Mon,Tue,Wed,Thu,Fri,Sat","yes","foodtype",""); insert_dbClients($c);
$c=new Client("WalMart Supercenter","WalMart","BFT","donor","350 Robert Smalls Pkwy","Beaufort","SC",29906,"","8435219589","","Mon,Tue,Wed,Thu,Fri,Sat","yes","foodtypeboxes",""); insert_dbClients($c);
$c=new Client("Walgreens  Beaufort","","BFT","donor","170 Boundary St.","Beaufort","SC",29902,"","5221396","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Toby"); insert_dbClients($c);
$c=new Client("1st African","","BFT","recipient","1414 S. Ribaut Road","Port Royal","SC",29935,"","5256119","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("AME Church","","BFT","recipient","","Ladys Island","SC",29907,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Beaufort Marine Institute","","BFT","recipient","60 Honey Bee Island","Seabrook","SC",29940,"","8462128","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","James N. rivers, III"); insert_dbClients($c);
$c=new Client("Bethel Deliverance Temple","","BFT","recipient","239 County Shed Rd.; PO Box 4968","Beaufort","SC",29903,"","8435210720","263925","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Anthony Cummings"); insert_dbClients($c);
$c=new Client("Boys & Girls Club  Beaufort","","BFT","recipient","17 B Marshell Lane","Beaufort","SC",29902,"","9865437","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Sam Burke"); insert_dbClients($c);
$c=new Client("Burton Wells  Senior Center","","BFT","recipient","1 Middleton Recreation","Beaufort","SC",29906,"","4705831","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Helen"); insert_dbClients($c);
$c=new Client("Canal Appartments","","BFT","recipient","1700 Salem Rd.; PO Box 1225","Beaufort","SC",29901,"","5216508","5242207 X 22","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Susan Trogdon"); insert_dbClients($c);
$c=new Client("Cannan Baptist","","BFT","recipient","Hwy. 17","Ladys Island","SC",29907,"","5920548","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Claysoul rice"); insert_dbClients($c);
$c=new Client("CAPA","","BFT","recipient","PO 531","Beaufort","SC",29901,"","5247727","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Church of God & Unity","","BFT","recipient","PO Box","Ladys Island","SC",29907,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("CODA","","BFT","recipient","PO Box 1775","Beaufort","SC",29901,"","7701074","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Community Bible Church","","BFT","recipient","638 Parris Island","Beaufort","SC",29906,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Community residence","","BFT","recipient","1508 Old Shell Rd.","Port Royal","SC",29935,"","5257684","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Linda Jackson"); insert_dbClients($c);
$c=new Client("Coosa Elementary","","BFT","recipient","Brickyard Point","Port Royal","SC",29935,"","5927526","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mr. Mixon"); insert_dbClients($c);
$c=new Client("Disability/Special Needs DSN","","BFT","recipient","PO Box 129","Port Royal","SC",29935,"","4706300","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mitzi Wagner"); insert_dbClients($c);
$c=new Client("Ebenezer Baptist Church","","BFT","recipient","Martin Luther King Blvd.; PO Box 476","Saint Helena Island","SC",29920,"","8435751821","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Venessa Lynard"); insert_dbClients($c);
$c=new Client("First African Baptist  Beaufort","","BFT","recipient","1414 S. Ribaut Rd.","Port Royal","SC",29935,"","5256119","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Fountain of Life  Ministry","","BFT","recipient","21 Marshellen Dr.","Port Royal","SC",29935,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Franciscan Center","","BFT","recipient","234 Green Winged Tead Dr. S.","Ladys Island","SC",29907,"","8383924","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("G revival Ministries, Inc.","","BFT","recipient","52 Glaze Drive","Beaufort","SC",29906,"","6941717","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Curt F. Gibbs"); insert_dbClients($c);
$c=new Client("H.A. Beaufort","","BFT","recipient","","Beaufort","SC",29901,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Hampton County Charities","","BFT","recipient","123 Given St.","Hampton","SC",29924,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Help Of Beaufort  Inactive","","BFT","recipient","1910 Baggett St","Beaufort","SC",29901,"","843 5241223","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Housing Auth. Yemassee","","BFT","recipient","1009 Prince St.; PO Box 1104","Beaufort","SC",29901,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Housing Authority  Beaufort","","BFT","recipient","1009 Prince St.; PO Box 1104","Beaufort","SC",29901,"","5257059","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Angela or Ed"); insert_dbClients($c);
$c=new Client("Huspah Baptist Church","","BFT","recipient","18 Huspah Baptist Church Rd.","Seabrook","SC",29940,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("J. Davis School","","BFT","recipient","364 Kears Neck Road","Seabrook","SC",29940,"","4663600","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Leroy Brown Senior Center","","BFT","recipient","123 Hello","Saint Helena Island","SC",29920,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Life Ministry","","BFT","recipient","645 Beaufort St.","Beaufort","SC",29901,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Mossy Oaks","","BFT","recipient","25 Johnny Morrall Circle","Beaufort","SC",29902,"","5242922","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Johnnie Jackson"); insert_dbClients($c);
$c=new Client("New Hope","","BFT","recipient","Parris Island Gateway","Ladys Island","SC",29907,"","5246520","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Betty"); insert_dbClients($c);
$c=new Client("Oak Hill Terrace","","BFT","recipient","2605 N. Royal Oaks; C/O Joyce Bunton","Beaufort","SC",29902,"","8120638","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","buntonj2@hargray."); insert_dbClients($c);
$c=new Client("Parks & Leisure SeniorsBft","","BFT","recipient","1514 Richmond Avenue","Port Royal","SC",29935,"","4706321","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Washington"); insert_dbClients($c);
$c=new Client("Port Royal Adult Com.inactive","","BFT","recipient","1508 Old Shell Rd.","Port Royal","SC",29935,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Praise Assembly","","BFT","recipient","800 Parris Isl. Gatweway; PO Box 596","Port Royal","SC",29935,"","8432529155","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mike McCaskey"); insert_dbClients($c);
$c=new Client("Revival Team Outreach","","BFT","recipient","123 Happy","Ladys Island","SC",29907,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Revival Team Outreadh Ministry","","BFT","recipient","1744 Trask Parkway; PO Box 631","Lobeco","SC",29931,"","8435750251","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Pastor Daniels"); insert_dbClients($c);
$c=new Client("Rose Hill Baptist  Inactive","","BFT","recipient","308 Shanklin Rd.","Ladys Island","SC",29907,"","3794886","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Sacred Heart House","","BFT","recipient","123 Hello","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Scotts Hill Center","","BFT","recipient","Scott Hills Road","Ladys Island","SC",29907,"","8388300","2526525","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Scotts Hill Church of Christ","","BFT","recipient","259 Storyteller Rd.","Saint Helena Island","SC",29920,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Gethsemane Baptist","","BFT","recipient","350 Stuart Point Rd.","Seabrook","SC",29940,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Goodwill Church","","BFT","recipient","","Saint Helena Island","SC",29920,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Jordan Church","","BFT","recipient","123 Beaufort","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Senior Center","","BFT","recipient","1408 Parris Avenue","Port Royal","SC",29935,"","5241787","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Helen Elliot"); insert_dbClients($c);
$c=new Client("Sheldon Community Enrichment","","BFT","recipient","PO Box 323","Sheldon","SC",29941,"","8462250","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ms. Mackie"); insert_dbClients($c);
$c=new Client("Sinai Baptist Church","","BFT","recipient","PO Box 123","Beaufort","SC",29902,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Team Revival","","BFT","recipient","1744 Trask Parkway","Seabrook","SC",29940,"","8464024","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("The Church of God in Unity","","BFT","recipient","38 Millege Road","Beaufort","SC",29902,"","5245600","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Truck/Warehouse/Soda","","BFT","recipient","Pepsi Warehouse","Beaufort","SC",29906,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Women of Faith & Power","","BFT","recipient","71 Holly Hall Road","Beaufort","SC",29902,"","3223202","6832026","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mary Simmons"); insert_dbClients($c);
$c=new Client("Atlanta Bread  Hilton Head","","HHI","donor","120 Festival Park","Hilton Head Island","SC",29926,"","2270375","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Lynn"); insert_dbClients($c);
$c=new Client("BiLo   Hilton Head North","BiLo","HHI","donor","95 Mathews Drive","Hilton Head Island","SC",29926,"","6815327","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Julia Peacock"); insert_dbClients($c);
$c=new Client("BiLo  Hilton Head South #275","BiLo","HHI","donor","70 Pope Avenue","Hilton Head Island","SC",29926,"","8428691","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Ernie Burris"); insert_dbClients($c);
$c=new Client("Charleys Crab","","HHI","donor","2 Hudson Road","Hilton Head Island","SC",29926,"","3429066","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Eric Seaglund, Exec."); insert_dbClients($c);
$c=new Client("Christines Catering","","HHI","donor","Atrium Bldg. Unit 2","Hilton Head Island","SC",29928,"","7854646","","","no","pounds",""); insert_dbClients($c);
$c=new Client("Collins & James","","HHI","donor","8 Arccher Road","Hilton Head Island","SC",29926,"","6966388","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Jim & Alex Hazelto"); insert_dbClients($c);
$c=new Client("Crazy Crab","","HHI","donor","7 Office Park Road","Hilton Head Island","SC",29926,"","6815021","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Debbie Tolerton, M"); insert_dbClients($c);
$c=new Client("Donor Programs","","HHI","donor","PO Box 23621","Hilton Head Island","SC",29926,"","6893689","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Gerri"); insert_dbClients($c);
$c=new Client("Food Lion  Palmetto Bay #714","Food Lion","HHI","donor","6 Bow Circle","Hilton Head Island","SC",29928,"","8429734","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Fresh Market","","HHI","donor","boy","Hilton Head Island","SC",29928,"","8428332","","Mon,Tue,Wed,Thu,Fri","no","pounds","Jim riegel"); insert_dbClients($c);
$c=new Client("Harris Teeter  HHI  South #123","","HHI","donor","33 Greenwood Drive","Hilton Head Island","SC",29928,"","7856185","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Harris Teeter  HHI North #152","","HHI","donor","301 Main Street","Hilton Head Island","SC",29926,"","6896255","","Mon,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Heritage Farms","","HHI","donor","Sean Pines","Hilton Head Island","SC",29928,"","3635444","","","yes","pounds",""); insert_dbClients($c);
$c=new Client("Heritage Foundation","","HHI","donor","Sea Pines","Hilton Head Island","SC",29928,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Island  Bagels","","HHI","donor","S. Island Square","Hilton Head Island","SC",29928,"","6863353","","Mon,Wed","no","pounds",""); insert_dbClients($c);
$c=new Client("Longhorn Steak House","","HHI","donor","831 South Island Sq.","Hilton Head Island","SC",29928,"","6864056","","Tue,Wed,Fri","no","pounds","Mat"); insert_dbClients($c);
$c=new Client("Marriott Beach & Golf resort","","HHI","donor","1 Hotel Circle","Hilton Head Island","SC",29928,"","6868400","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Michael Stocker, C"); insert_dbClients($c);
$c=new Client("Michelle Kitter","","HHI","donor","17C Hunter Road","Hilton Head Island","SC",29926,"","7854246","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Old Oyster Factory","","HHI","donor","101 Marshyland Road","Hilton Head Island","SC",29926,"","6816040","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Pepperidge Farm","","HHI","donor","Hunter Road","Hilton Head Island","SC",29926,"","3043000","","Mon,Wed,Fri","no","pounds","Taser"); insert_dbClients($c);
$c=new Client("Piggly Wiggly  Shelter Cove","","HHI","donor","32 Shelter Cove Lane","Hilton Head Island","SC",29928,"","8424090","","Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Pizza Hut  Port royal","","HHI","donor","458 Wm Hilton Pkwy.","Hilton Head Island","SC",29926,"","6818100","","Mon","no","pounds","Alfredo"); insert_dbClients($c);
$c=new Client("Post Office Drive","","HHI","donor","South End","Hilton Head Island","SC",29928,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Annual Drive"); insert_dbClients($c);
$c=new Client("Publix  Hilton Head North #473","Publix","HHI","donor","45 Pembroke Drive  Suite 104","Hilton Head Island","SC",29926,"","6899977","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Publix  Hilton Head South #700","Publix","HHI","donor","11 Palmetto Bay Road","Hilton Head Island","SC",29928,"","8422632","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Ronnies Bakery","","HHI","donor","1A Regecy Road","Hilton Head Island","SC",29928,"","8424707","","","no","pounds",""); insert_dbClients($c);
$c=new Client("Sams Club","Sams Club","HHI","donor","98 Mathews Dr. Box 1A","Hilton Head Island","SC",29926,"","6817117","","Mon,Wed,Fri","yes","pounds",""); insert_dbClients($c);
$c=new Client("Seabrook of HH","","HHI","donor","300 Woodhaven Drive","Hilton Head Island","SC",29928,"","8423747","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Once a week picku"); insert_dbClients($c);
$c=new Client("Second Helpings Office","","HHI","donor","","","","","","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Signes Heavenly Café","","HHI","donor","93 Arrow Road","Hilton Head Island","SC",29928,"","7859118","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","On Call"); insert_dbClients($c);
$c=new Client("Starbucks  HHI North","","HHI","donor","461 Pineland Station","Hilton Head Island","SC",29926,"","6896823","","Mon,Wed,Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("Starbucks  HHI South","","HHI","donor","11 Palmetto Bay Road","Hilton Head Island","SC",29928,"","3415477","","Mon,Wed,Thu,Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Sweet Carolina Cupcakes","","HHI","donor","1 N. Forest Beach Dr.  #203; Coligny Plaza","Hilton Head Island","SC",29928,"","3422611","","Mon","no","pounds","Holly Slayton"); insert_dbClients($c);
$c=new Client("The Smokehouse","","HHI","donor","102 Pope Avenue","Hilton Head Island","SC",29928,"","8424227","","","no","pounds","Jerry, Owner"); insert_dbClients($c);
$c=new Client("Walgreens  Hilton Head","","HHI","donor","Festival Center","Hilton Head Island","SC",29926,"","3427481","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Bob Lopez"); insert_dbClients($c);
$c=new Client("Weston resort","","HHI","donor","2 Grass Lawn Avenue","Hilton Head Island","SC",29926,"","6814000","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Wild Wings  Hilton Head","","HHI","donor","72 Pope Avenue","Hilton Head Island","SC",29928,"","7859464","","Mon","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Helpings Office","","HHI","Internal","PO Box 23621","Hilton Head Island","SC",29925,"","6815572","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Gerri"); insert_dbClients($c);
$c=new Client("All Saints Episcopal","","HHI","recipient","3001 Meeting St.","Hilton Head Island","SC",29926,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Beaufort County Parksrec  HHI","","HHI","recipient","61 Ulmer Rd. PO Box 205","Bluffton","SC",29910,"","7571503","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Bluffton Self Help – HHI","","HHI","recipient","PO Box 2420","Bluffton","SC",29910,"","7578000","","Mon,Tue,Thu","yes","pounds","Jenny Hanny"); insert_dbClients($c);
$c=new Client("Boys & Girls Club  HHI","","HHI","recipient","PO Box 22267","Hilton Head Island","SC",29925,"","6893646","","Mon,Tue,Wed,Fri","no","pounds","Irving Campbell"); insert_dbClients($c);
$c=new Client("Childrens Center  HHI","","HHI","recipient","PO Box 22564","Hilton Head Island","SC",29925,"","6812739","","Tue,Thu,Fri","no","pounds","Lorraine"); insert_dbClients($c);
$c=new Client("Church of the Cross – HHI","","HHI","recipient","PO Box 288","Bluffton","SC",29910,"","8166015","","Mon","no","pounds","Larry Brown"); insert_dbClients($c);
$c=new Client("Deep Well","","HHI","recipient","PO Box 5543","Hilton Head Island","SC",29938,"","7852849","","Mon","no","pounds","Betsy Doughtie"); insert_dbClients($c);
$c=new Client("First African Baptist  Hilton Hea","","HHI","recipient","Beach City Rd.","Hilton Head Island","SC",29926,"","6716620","","Mon,Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("Hardeville Thrift Shop","","HHI","recipient","19869 Waythe Hardee Blvd","Hardeeville","SC",29927,"","843 7843157","2986109","Wed,Sat","no","pounds","Viola Ulmer"); insert_dbClients($c);
$c=new Client("Holy Family Catholic Church","","HHI","recipient","24 Pope Avenue","Hilton Head Island","SC",29928,"","2472612","","Fri,Sat","no","pounds","Carl Zeis"); insert_dbClients($c);
$c=new Client("Housing Auth.  Sandalwood","","HHI","recipient","148 Island Drive #5","Hilton Head Island","SC",29938,"","9733497970","6450935","Mon,Tue,Fri","no","pounds","Nannette Pierson"); insert_dbClients($c);
$c=new Client("Island House","","HHI","recipient","141 Goethe Road","Bluffton","SC",29910,"","757888","","Mon,Wed","no","pounds","Elaine Smith"); insert_dbClients($c);
$c=new Client("Memory Matters","","HHI","recipient","50 Pope Avenue","Hilton Head Island","SC",29928,"","7852119","7854099","Mon,Wed","no","pounds",""); insert_dbClients($c);
$c=new Client("Miscellaneous Deliveries","","HHI","recipient","","","","","","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Mt.Calvary Baptist","","HHI","recipient","PO Box 23194","Hilton Head Island","SC",29925,"","6813678","","Mon,Wed","no","pounds","Sandra Bass"); insert_dbClients($c);
$c=new Client("Office","","HHI","recipient","PO Box 23621","Hilton Head Island","SC",29926,"","8436893689","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Gerri"); insert_dbClients($c);
$c=new Client("Programs for Ex.People (PEP)","","HHI","recipient","10 Oak Park Drive Box 2","Washington","SC",20026,"","6818413","","Mon","no","pounds","Peg Carroll"); insert_dbClients($c);
$c=new Client("Senior Citizens","","HHI","recipient","200 Main St.","Hilton Head Island","SC",29926,"","","","Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("St. Andrew By The Sea","","HHI","recipient","20 Pope Avenue","Hilton Head Island","SC",29928,"","7854711","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds","Kathy Emery"); insert_dbClients($c);
$c=new Client("Tabernacle Baptist – HHI","","HHI","recipient","","","SC","","","","","Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Childrens Center  Bluffton","","HHI","recipient","PO Box 1089","Bluffton","SC",29910,"","7575549","","Tue,Thu","no","pounds",""); insert_dbClients($c);
$c=new Client("Archway Cookies","","SUN","donor","#2 Lotus Court","Bluffton","SC",29910,"","7579233","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Andy Vodvarka"); insert_dbClients($c);
$c=new Client("Atlanta Bread  Sun City","","SUN","donor","11 Towne Drive","Bluffton","SC",29910,"","8152479","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("BiLo Bluffton","BiLo","SUN","donor","Fording Island Rd.","Bluffton","SC",29910,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Big Lots","","SUN","donor","32 Malphrus","Bluffton","SC",29910,"","8438371400","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds","Lost Data  July"); insert_dbClients($c);
$c=new Client("Farish Meat Processing","Farish Meat Pr","SUN","donor","Moss Creek","Okatie","SC",29909,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion  Kitties Xing # 2691","Food Lion","SUN","donor","1008 Fording Island Rd.","Bluffton","SC",29910,"","8156100","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Food Lion – Sun City","Food Lion","SUN","donor","210 Okatie Village Drive ","Okatie","SC",29909,"","7059301","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Henrys Farms","","SUN","donor","46 Tom Fritt Road","Okatie","SC",29909,"","8382762","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Mr. Colton"); insert_dbClients($c);
$c=new Client("Honey Baked Ham","","SUN","donor","1060 Fording Island Rd.","Bluffton","SC",29910,"","8157388","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Krogers  Sun City","","SUN","donor","125 Towne Drive","Bluffton","SC",29910,"","8156070","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Montanas","","SUN","donor","16 Kitties Landing","Bluffton","SC",29910,"","8152327","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Piggly Wiggly  Hardeeville","","SUN","donor","7 Main St.","Hardeeville","SC",29927,"","7843201","","Mon,Wed,Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Piggly Wiggly  Sun City","","SUN","donor","50 Burnt Church Rd. 100F","Bluffton","SC",29910,"","7576621","","Mon,Wed,Fri","yes","pounds",""); insert_dbClients($c);
$c=new Client("Publix  Buckwalter #1205","Publix","SUN","donor","Belfair Village","Bluffton","SC",29910,"","7063049","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Publix  Hardeeville #1354","Publix","SUN","donor","xxooo","Hardeeville","SC",29927,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","foodtype",""); insert_dbClients($c);
$c=new Client("Publix #845","Publix","SUN","donor","80 Baylor Drive","Bluffton","SC",29910,"","8437063049","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("R.T. Market","","SUN","donor","Palmetto Bluff","Bluffton","SC",29910,"","7063448","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Target","","SUN","donor","1050 Fording Island Road","Bluffton","SC",29910,"","8153321","","Mon,Tue,Wed,Thu,Fri,Sat","yes","pounds",""); insert_dbClients($c);
$c=new Client("Truck","","SUN","donor","Sun City Parking Lot","Okatie","SC",29909,"","6815572","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("WalMart Supercenter","WalMart","SUN","donor","4400 Highway 278;1 Nickel Plate Road","Okatie","SC",29909,"","8432083000","","Mon,Wed,Thu,Sat","yes","foodtypeboxes","Harvest"); insert_dbClients($c);
$c=new Client("Wild Wings  Sun City","","SUN","donor","1188 Fording Isaland Rd.","Bluffton","SC",29910,"","8379453","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("First  BaptistSun City","","SUN","recipient","12 Church St.","Beaufort","SC",29902,"","5246886","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Habitat for Humanity  Beaufort","","SUN","recipient","616 Parris Island Gateway; PO Box 1622","Beaufort","SC",29901,"","5223500","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Helping Hands Of Burton","","SUN","recipient","22 Pine Grove Road","Beaufort","SC",29906,"","5256586","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("Second Mt. Carmel Baptist","","SUN","recipient","1 Middleton  R Dr.","Beaufort","SC",29906,"","8134706203","","Tue","no","pounds","Agnes Washington"); insert_dbClients($c);
$c=new Client("St. Jude Church","","SUN","recipient","12 Bing St.; PO Box 336","Yemassee","SC",29945,"","8432750481","","Fri","no","pounds","Debra Blue"); insert_dbClients($c);
$c=new Client("2nd Jordan Baptist Church","","SUN","recipient","2 Jordan Drive","Okatie","SC",29909,"","8433799927","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Deacon Jerome Kel"); insert_dbClients($c);
$c=new Client("Access Network","","SUN","recipient","5710 N. Okatie Hwy. Suite B.","Ridgeland","SC",29936,"","843 3795600","","Tue","no","pounds",""); insert_dbClients($c);
$c=new Client("ACE  Sun City","","SUN","recipient","80 Lowcountry Dr.","Ridgeland","SC",29936,"","8439878107","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Eddie Odgen"); insert_dbClients($c);
$c=new Client("Agape/Arthur McGirk","","SUN","recipient","5855 S. Okatie Hgwy.","Hardeeville","SC",29927,"","8437846008","","Mon","no","pounds","Arthur McGire"); insert_dbClients($c);
$c=new Client("Bluffton Recreation Center","","SUN","recipient","PO Box","Bluffton","SC",29910,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Bluffton Self Help – SUN","","SUN","recipient","PO Box 2420","Bluffton","SC",29910,"","7578000","","Wed,Fri","yes","pounds","Jenny Hanny"); insert_dbClients($c);
$c=new Client("Booker T. Washington Center","","SUN","recipient","224 Big Estate  Rd.","Yemassee","SC",29945,"","3640676","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Lucille Williams"); insert_dbClients($c);
$c=new Client("Boys & Girls Club  Bluffton","","SUN","recipient","PO Box 1908","Bluffton","SC",29910,"","7062300","","Tue,Fri","no","pounds","Molly O. Smith"); insert_dbClients($c);
$c=new Client("Boys & Girls Club  Okatie","","SUN","recipient","5855 S. Okatie Highway","Hardeeville","SC",29927,"","7846008","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","robert Huff 71711"); insert_dbClients($c);
$c=new Client("Boys & Girls Club – Ridgeland","","SUN","recipient","PO Box 1482","Ridgeland","SC",29936,"","8124357","","Mon,Thu","no","pounds","Keith Alston"); insert_dbClients($c);
$c=new Client("Christ Central Mission","","SUN","recipient","PO Bhox 311","Ridgeland","SC",29936,"","7269311","3059091","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","John Doyle"); insert_dbClients($c);
$c=new Client("Church of the Cross – SUN","","SUN","recipient","PO Box 288","Bluffton","SC",29910,"","8166015","","Mon,Thu","no","pounds","Larry Brown"); insert_dbClients($c);
$c=new Client("Community First","","SUN","recipient","1215 May River Road","Bluffton","SC",29910,"","2980026","7069580","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Paula Harvey, Exec."); insert_dbClients($c);
$c=new Client("Habitat for Humanity  Beaufort","","SUN","recipient","21 Brendan Lane","Ridgeland","SC",29936,"","7579995","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Hardeeville Thrift","","SUN","recipient","","Okatie","SC",29909,"","2986109","","Wed,Sat","no","pounds","Viola Ulmer"); insert_dbClients($c);
$c=new Client("New Abundant Life","","SUN","recipient","19 Church St.","Bluffton","SC",29910,"","","","Fri","yes","pounds",""); insert_dbClients($c);
$c=new Client("Parks & Rec.  Bluffton","","SUN","recipient","PO Box 205","Bluffton","SC",29910,"","7571503","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Barbara Campbell"); insert_dbClients($c);
$c=new Client("Room At The Inn","","SUN","recipient","155 Old Miller Rd.","Bluffton","SC",29910,"","7053773","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("Senior Citizens  Bluffton","","SUN","recipient","PO Box 158; c/o Vivian Morton","Bluffton","SC",29910,"","7571508","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds","Vivian Morton"); insert_dbClients($c);
$c=new Client("St. Anthonys – Hardeeville","","SUN","recipient","","Hardeeville","SC","","","","","Wed","no","pounds",""); insert_dbClients($c);
$c=new Client("St. Gregory","","SUN","recipient","333 Fording Island Rd","Bluffton","SC",29910,"","8153100","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
$c=new Client("St. Stephens Ridgeland","","SUN","recipient","PO Box 2075 Hgwy.13","Ridgeland","SC",29936,"","","","Mon,Thu,Fri","no","pounds",""); insert_dbClients($c);
$c=new Client("Tabernacle Baptist Church","","SUN","recipient","PO Box 4035; C/O Gertrude Bro","Beaufort","SC",29903,"","5223465","","Tue","no","pounds","Gertrude Brown"); insert_dbClients($c);
$c=new Client("Walgreens  Sun City","","SUN","recipient","","Okatie","SC",29909,"","","","Mon,Tue,Wed,Thu,Fri,Sat","no","pounds",""); insert_dbClients($c);
		
}

?>
</body>
</html>