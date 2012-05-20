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
	echo("Fixing volunteer dates...<br />");
	include_once('dbinfo.php');
	include_once('dbVolunteers.php');
	
	// connect
	$connected=connect();
 	if (!$connected) echo mysql_error();
 	echo("connected...<br />");
    echo("database selected...<br />");

	fixthedates();
	
	echo("Fixing volunteer dates complete.");
	echo(" To prevent data loss, run this program only if you want to fix data fields.</p>");

function fixthedates() {
$v=retrieve_dbVolunteers("Lynn8438461739"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Kevin8439866447"); if ($v) {$v->set_license_expdate("17-01-16"); $v->set_birthday("93-01-13"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("William8435229025"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joseph8435250207"); if ($v) {$v->set_license_expdate("11-07-15"); $v->set_birthday("52-07-15"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ruben8435223794"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Garcia8433791522"); if ($v) {$v->set_license_expdate("14-04-25"); $v->set_birthday("55-04-25"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8432636934"); if ($v) {$v->set_license_expdate("13-08-29"); $v->set_birthday("43-08-29"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8436818115"); if ($v) {$v->set_license_expdate("20-10-01"); $v->set_birthday("52-10-01"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Laura8436816751"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Richard8436898261"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8433412430"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8436897008"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ed8437857485"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Don8433422938"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Paul8433636883"); if ($v) {$v->set_license_expdate("15-06-04"); $v->set_birthday("41-06-04"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Keven","9494228245"); if ($v) {$v->set_license_expdate("21-01-11"); $v->set_birthday("59-11-11"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Vince8437152199"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("53-04-03"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ed8433425530"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8436896778"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bruce8436818774"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8436712518"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ernie8436816959"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Lane8436712180"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("46-07-14"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ken8436815965"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dave8436816125"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Stephen8436818151"); if ($v) {$v->set_license_expdate("12-04-29"); $v->set_birthday("49-04-29"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ed8433424426"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8433417640"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Don8436815479"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("39-10-11"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8433426363"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("41-03-13"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Carl8433427497"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("42-11-04"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ernie8436815844"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8436812318"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Don8432981887"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Clutch8436812145"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8436819577"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8433636404"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8436815269"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Doug8434224348"); if ($v) {$v->set_license_expdate("16-12-17"); $v->set_birthday("54-12-17"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Rick8436714077"); if ($v) {$v->set_license_expdate("17-12-13"); $v->set_birthday("45-12-13"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Trace8436711694"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("43-01-24"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Rick8433427301"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8436896998"); if ($v) {$v->set_license_expdate("13-02-04"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Floyd8436817141"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8437853029"); if ($v) {$v->set_license_expdate("19-09-07"); $v->set_birthday("58-09-07"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Steve8436831415"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8436815956"); if ($v) {$v->set_license_expdate("13-11-01"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Tom8436818082"); if ($v) {$v->set_license_expdate("11-03-31"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Glyn8433636841"); if ($v) {$v->set_license_expdate("13-12-29"); $v->set_birthday("44-12-29"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8433423053"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Gerri8436815572"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Stu8436813541"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jerry8438427065"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Rich8436895344"); if ($v) {$v->set_license_expdate("13-05-14"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Rick8436895344"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8436822355"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8433426910"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dennis8436712049"); if ($v) {$v->set_license_expdate("16-05-25"); $v->set_birthday("37-05-25"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8433427899"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Richard8433635444"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Fred8438374874"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8436892422"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Gene8436818971"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Kevin8438425623"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Alan8436896779"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Mike8436715006"); if ($v) {$v->set_license_expdate("15-07-30"); $v->set_birthday("44-07-30"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Hank8433632122"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Judy8436716205"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Paul8438422134"); if ($v) {$v->set_license_expdate("11-07-15"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8436895053"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ron8436716756"); if ($v) {$v->set_license_expdate("17-01-22"); $v->set_birthday("51-01-22"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Les8436716256"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Tim8436824162"); if ($v) {$v->set_license_expdate("12-09-27"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ron8438156472"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8436813518"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Lesia8438361295"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8433427791"); if ($v) {$v->set_license_expdate("14-05-07"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dave8436896950"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8438362892"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jean8436893809"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8436715023"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Rick8433636334"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("George8436838510"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("38-10-24"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Linda8437579889"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8433422905"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Clarke8436811831"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Chuck8436716663"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("40-07-22"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Herschel8436824664"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8433429192"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Docl8437859239"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("12-05-06"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("David8436823554"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("David8436712960"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8436716803"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Gerry8436719339"); if ($v) {$v->set_license_expdate("12-07-02"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("David8434418082"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bruce8437057101"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8437055510"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Greg8437053068"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Tricia8437054948"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Fred8437055864"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8437057444"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Peter8437056752"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8437053376"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Steve8437055868"); if ($v) {$v->set_license_expdate("14-02-15"); $v->set_birthday("42-02-15"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Pat8437056836"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dudley8437052595"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Mike8437077226"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Michael8437055904"); if ($v) {$v->set_license_expdate("13-08-16"); $v->set_birthday("36-08-16"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dick8432984251"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ron8437057713"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Frank8437054975"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Claudia8437059876"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Claudia","5166039876"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jack8437053923"); if ($v) {$v->set_license_expdate("18-04-25"); $v->set_birthday("48-04-25"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dan8437057813"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Paul8437056224"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Don8437057813"); if ($v) {$v->set_license_expdate("17-10-18"); $v->set_birthday("52-10-19"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Lawrence8437077128"); if ($v) {$v->set_license_expdate("19-09-11"); $v->set_birthday("51-09-11"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Carol8437055552"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Herman8437052470"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jack8437057852"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ed8437576123"); if ($v) {$v->set_license_expdate("12-05-11"); $v->set_birthday("38-05-11"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Don8437053337"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Paul8437056317"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8437056609"); if ($v) {$v->set_license_expdate("13-12-11"); $v->set_birthday("40-12-11"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8437056609"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Edward8437057679"); if ($v) {$v->set_license_expdate("18-04-20"); $v->set_birthday("47-04-20"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Thomas8435480707"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8437057484"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Matt8437053573"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Tom8436822228"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("47-09-21"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Stephen8437053496"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ed8437055049"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Kevin8437077615"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Lorin8437051959"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Loran8437051959"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8437052593"); if ($v) {$v->set_license_expdate("14-01-24"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("James8437057054"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Roger8437052305"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8437055674"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8437056142"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8437053393"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Alexander8437052341"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8437052128"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("34-07-02"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Julio8437053801"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8437059950"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Gina8437050642"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("judy8437053006"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jon8437053909"); if ($v) {$v->set_license_expdate("14-03-21"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Sonja8437053662"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bob8437059499"); if ($v) {$v->set_license_expdate("15-01-06"); $v->set_birthday("31-01-06"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8433682894"); if ($v) {$v->set_license_expdate("13-05-31"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ed8437077085"); if ($v) {$v->set_license_expdate("17-06-07"); $v->set_birthday("42-06-07"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Karen8437052126"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dan8437052960"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dan8437052960"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8437059284"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8437059223"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("George8437052016"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Tom8437053674"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Joe8437056549"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("David8437057815"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bud","9196242405"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Tom8437057174"); if ($v) {$v->set_license_expdate(""); $v->set_birthday("41-06-30"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8437059818"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Bill8437055527"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ray8437053426"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8437059424"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Gary8437057917"); if ($v) {$v->set_license_expdate("15-02-07"); $v->set_birthday("43-02-07"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ted8437055290"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Milton8437059193"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("David8437057815"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Earl8437057419"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Dick8437050233"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Ziggy8437052174"); if ($v) {$v->set_license_expdate("12-06-19"); $v->set_birthday("35-06-19"); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Karl8436456534"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Jim8437056181"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("John8437057747"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Les8437055147"); if ($v) {$v->set_license_expdate("11-11-19"); $v->set_birthday(""); update_dbVolunteers($v); }
$v=retrieve_dbVolunteers("Willi8437056009"); if ($v) {$v->set_license_expdate(""); $v->set_birthday(""); update_dbVolunteers($v); }
	
}

?>
</body>
</html>