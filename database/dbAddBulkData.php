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
	echo("Adding bulk data...<br />");
	include_once('dbinfo.php');
	include_once('dbClients.php');
	include_once('dbVolunteers.php');
	
	// connect
	$connected=connect();
 	if (!$connected) echo mysql_error();
 	echo("connected...<br />");
    echo("database selected...<br />");
    
	add_the_data();
	
	echo("Adding bulk data complete.");
	echo(" To prevent data loss, run this program only if you want to add bulk data to the tables.</p>");

function add_the_data() {
	
// add new volunteer data: $v = new Volunteer ($last_name, $first_name, $address, $city, $state, $zip, $phone1, $phone2, $email, $type,
//                         $status, $area, $license_no, $license_state, $license_expdate, $accidents, $availability, 
//                         $schedule, $history, $birthday, $start_date, $notes, $password); insert_dbVolunteers($v);
$v=new Volunteer("Anderson","Bruce","167  Dataw Drive","St  Helena Isl","SC","29920","8438385989",8432713284,"cbanders@centurylink.net","driver","active","BFT","SC 100024063","SC","17-03-17","","Mon:5","","","45-03-17","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("Anderson","Swinton","395 Distant Island Dr","Ladys Isl","SC",29907,"8435244099",8644151123,"beverlyswn@aol.com","driver","active","BFT","","","","","Thu:1","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Beidelman","Larry","1733 Longfield Drive","St  Helena Isl","SC","29920","8438388088",2489097868,"Lbeets@comcast.net","driver","active","BFT","SC 102251080","SC","14-01-15","","Tue:4","","","43-01-15","","Ronnie",""); insert_dbVolunteers($v);
$v=new Volunteer("Berg","Ed","216 Locust Fence Road","St  Helena Isl","SC","29920","8438381417",5169028277,"exbgolf@aol.com","driver","active","BFT","SC 100702786  ","SC","15-11-17","","Sat:1","","","42-11-17","","Pam",""); insert_dbVolunteers($v);
$v=new Volunteer("Blamble","Ken","459 BB Sams Drive","St  Helena Isl","SC","29920","8438384458",7327356983,"kenblam@msn.com","helper","active","BFT","","","","","","","","","","Charlotte",""); insert_dbVolunteers($v);
$v=new Volunteer("Bowers","Don","31 Cotton Dike Court","St  Helena Isl","SC","29920","8438386023","","dbowers@islc.net","driver","active","BFT",11307060,"SC","19-05-01","","Thu:4,Thu:5","","","","","Nancy",""); insert_dbVolunteers($v);
$v=new Volunteer("Briscia","Bernie","337 Dear Lake Drive","St  Helena Isl","SC","29920","8438383473","","bernbrese@aol.com","helper","active","BFT","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Brown","Nelson","175 distant island dr.","Ladys Isl","SC",29907,"8433229934",8435402491,"","helper","active","BFT","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Carter","John","805 Island Circle West","St  Helena Isl","SC","29920","8438389444",8438126177,"johncarter5@earthlink.net","driver","active","BFT","SC  100672916","SC","14-06-15","","Thu:3","","","44-06-15","","Diana",""); insert_dbVolunteers($v);
$v=new Volunteer("Clelland","Roy    ","265 Locust Fence Road","St  Helena Isl","SC","29920","8438385817",9432712994,"clelland@islc.net","helper","active","BFT","","","","","Mon:5","","","","","Joan",""); insert_dbVolunteers($v);
$v=new Volunteer("Close","Edward","38 Ceecee Rd,","St  Helena Isl","SC","29920","","","","driver","active","BFT","SC 90355805","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Dale","George","1712 Longfield Drive","St  Helena Isl","SC","29920","8438382586",5717488250,"gapdale@gmail.com","helper","active","BFT","","","","","Mon:1","","","","","Peggy",""); insert_dbVolunteers($v);
$v=new Volunteer("Dalziel","Robert","804 Island Circle West","St  Helena Isl","SC","29920","8438383651",5702428217,"robdalziel@mindspring.com","helper","active","BFT","","","","","","","","","","Mary Lou",""); insert_dbVolunteers($v);
$v=new Volunteer("Ferguson","Blain","PO Box 749","St  Helena Isl","SC","29920","8438383686",8432635675,"","driver","active","BFT","","","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Foley","Jim","240 Locust Fence","St  Helena Isl","SC","29920","8438389620","","foleycj@islc.net","helper","active","BFT","","","","","Thu:4","","","","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("George","Norm","1507 Gleason’s Landing Ct.","St  Helena Isl","SC","29920","8438380123","","ntgeorge@embarqmail.com","driver","active","BFT","","","","","Mon:3","","","","","Julie",""); insert_dbVolunteers($v);
$v=new Volunteer("Gesell","Perry H.","1722 Longfield Drive","St  Helena Isl","SC","29920","8438382995","","gesell.islandhome@gmail.com","helper","active","BFT","","","","","Mon:5","","","","","Patty",""); insert_dbVolunteers($v);
$v=new Volunteer("Hager","Frank","566 Island Circle East","St  Helena Isl","SC","29920","8438380010",8438124819,"franklinda@islc.net","driver","active","BFT","SC011258127","SC","14-03-19","","Thu:3","","","39-03-19","","Linda",""); insert_dbVolunteers($v);
$v=new Volunteer("Hammel","Charlie","1720 Long Field Drive","St  Helena Isl","SC","29920","8438381157","","cmhammel@embarqmail.com","driver","active","BFT","SC 101669261","SC","17-11-02","","Mon:4","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Hirsch","Ken","1055 Curisha Point South","St  Helena Isl","SC","29920","8438382783","","khirschmensana@att.net","helper","active","BFT","","","","","","","","","","Sherry",""); insert_dbVolunteers($v);
$v=new Volunteer("Jacobs","Paul","1116 Palmetto Point","St  Helena Isl","SC","29920","8438385188","","pdj8363@aol.com","driver","active","BFT","","","","","Sat:1","","","","","Joy",""); insert_dbVolunteers($v);
$v=new Volunteer("Jones","Joe"," 310 Westbrook Road","St  Helena Isl","SC","29920","8438387830","","jjjones1@embarqmail.com","driver","active","BFT","","","","","Mon:3","","","","","Jean",""); insert_dbVolunteers($v);
$v=new Volunteer("Kemp","Jim","39 South Boone Road","St  Helena Isl","SC","29920","8438384535","","jakemp@earthlink.net","helper","active","BFT","","","","","Thu:2","","","","","Ellen",""); insert_dbVolunteers($v);
$v=new Volunteer("Laughlin","Larry","115 Locust Fence Road","St  Helena Isl","SC","29920","8438389194","","lldl30@earthlink.net","helper","active","BFT","","","","","Thu:4,Thu:5","","","","","Diane",""); insert_dbVolunteers($v);
$v=new Volunteer("Lee","Walter","365 Westbrook Road","St  Helena Isl","SC","29920","8438385966","","walterann@earthlink.net","helper","active","BFT","","","","","Sat:1","","","31-03-01","","Ann ",""); insert_dbVolunteers($v);
$v=new Volunteer("Looney","Art","1710 Longfield Drive","St  Helena Isl","SC","29920","8438383563",8434763199,"artcarol1710@gmail.com","driver","active","BFT","SC 011511312","SC","16-03-31","","Mon:1","","","39-03-31","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("Lurtz","Terry M.","219 Dataw Drive","St  Helena Isl","SC","29920","8438387262","","tmlutz@embarqmail.com","driver","active","BFT","","","","","Mon:3","","","","","Beth",""); insert_dbVolunteers($v);
$v=new Volunteer("Luzzi","Dave","1219 Big Dataw Point Drive","St  Helena Isl","SC","29920","8438385040","","dluzzi@islc.net","driver","active","BFT","SC102017842","SC","18-07-25","","Tue:4,Sat:1","","","46-07-25","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("McKeown","Michael","623 South Reeve Road","St  Helena Isl","SC","29920","8438387678",5862607907,"mckeownmchl@gmail.com","driver","active","BFT","SC100128620","SC","18-08-22","","Mon:2","","","46-08-22","","Donna",""); insert_dbVolunteers($v);
$v=new Volunteer("Meeker","Philip","382 Dataw Drive","St  Helena Isl","SC","29920","8438381452","","onlymeek1@embarqmail.com","driver","active","BFT","SC 007748443","SC","11-08-17","","Mon:4","","","40-08-17","","June",""); insert_dbVolunteers($v);
$v=new Volunteer("Morris","G. David","50 S Boone Road","St  Helena Isl","SC","29920","8438384597",5858020466,"GDMORRIS1@embarqmail.com","driver","active","BFT","SC100782236","SC","15-06-02","","Thu:2","","","44-06-02","","Pat",""); insert_dbVolunteers($v);
$v=new Volunteer("Morrissey","Jim","658 South Reeve Road","St  Helena Isl","SC","29920","8438383876","","jmorrissey@islc.net","driver","active","BFT","NY445146321","NY","12-07-26","","Thu:2","","","49-07-26","","Carol",""); insert_dbVolunteers($v);
$v=new Volunteer("Mueller","Gerry","14 Pee Dee Point","St  Helena Isl","SC","29920","8438385020","","gkmanddam@aol.com","driver","active","BFT","","","","","Mon:4","","","","","Denise",""); insert_dbVolunteers($v);
$v=new Volunteer("O_Brien","Ken","46 S Boone Road","St  Helena Isl","SC","29920","8438383601",7036277569,"kwobrien27@gmail.com","driver","active","BFT","SC 101590580","SC","17-06-27","","Thu:2","","","45-06-27","","Mary Therese",""); insert_dbVolunteers($v);
$v=new Volunteer("Ogle","Tom","50 Long Point Dr","Coosaw Isl","SC",29907,"8435214672","","togle46@embarqmail.com","helper","active","BFT","","","","","Thu:1","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Pierce","Dan","418 B.B. Sams Drive","St  Helena Isl","SC","29920","8438382950","","danielpierce@embarqmail.com","helper","active","BFT","","","","","Thu:5","","","","","Ruth",""); insert_dbVolunteers($v);
$v=new Volunteer("Pogachnick","Bob","405 B.B. Sams Court","St  Helena Isl","SC","29920","8438384887","","twopogos@islc.net","driver","active","BFT","","","","","Mon:3","","","","","Micki",""); insert_dbVolunteers($v);
$v=new Volunteer("Ross","Jim","6 Eagle Trace Ct.","St  Helena Isl","SC","29920","8435211713","","","driver","active","BFT","SC 011472519","SC","","","","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Schafer","John","671 Island Circle East","St  Helena Isl","SC","29920","8438387517",2019600648,"johnschafer3@yahoo.com","driver","active","BFT","SC102163119","SC","19-10-04","","Thu:3","","","48-10-04","","Cindy",""); insert_dbVolunteers($v);
$v=new Volunteer("Schmitt","Steve","1463 Gleason’s Landing Drive","St  Helena Isl","SC","29920","8438387361","","aceduffer0313@gmail.com","helper","active","BFT","","","","","Mon:1","","","","","Anne",""); insert_dbVolunteers($v);
$v=new Volunteer("Schrader","Drew","1349 Rowland Drive","St  Helena Isl","SC","29920","8438387251",4045200170,"djschrader@comcast.net","helper","active","BFT","","","","","Mon:5","","","","","Joan",""); insert_dbVolunteers($v);
$v=new Volunteer("Sullivan","Michael","490 BB Sams Drive","St  Helena Isl","SC","29920","8438387463",3019889354,"mjsullivan@embarqmail.com","driver","active","BFT","SC 102273886","SC","19-03-29","","Thu:2","","","54-03-29","","Denise",""); insert_dbVolunteers($v);
$v=new Volunteer("Thomas","Ernie","730 North Reeve Road","St  Helena Isl","SC","29920","8438381282","","ethomas@islc.net","helper","active","BFT","","","","","Sat:1","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Tomsik","Bill","3 Doe Point","St  Helena Isl","SC","29920","8438387299","","bltomsik@embarqmail.com","driver","active","BFT","","","","","Mon:2","","","","","Linda",""); insert_dbVolunteers($v);
$v=new Volunteer("Weeks","Steve","2002 Wilson dr.","Beaufort","SC",29902,"8435229772","","geodyne@aol.com","driver","active","BFT","SC101362779","SC","12-06-12","","Thu:1","","","41-06-02","","",""); insert_dbVolunteers($v);
$v=new Volunteer("White","Conard","707 N. Reeve Road","St  Helena Isl","SC","29920","8438385612",8438126627,"cnwhite@islc.net","driver","active","BFT","il w30019241098","SC","15-04-05","","Thu:2","","","41-04-05","","Nancy",""); insert_dbVolunteers($v);
$v=new Volunteer("Jenkins","Norman","","","SC","","8435249742","","Pastorn@charter.net","driver","active","BFT","","","","","Tue:2","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Irby","James","","","SC","","8432710602","","","driver","active","BFT","","","","","Tue:1","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Grim","David","","","SC","","8435751481","","grimdav@gmail.com","driver","active","BFT","","","","","Tue:4","","","","","",""); insert_dbVolunteers($v);
$v=new Volunteer("Jackson","Johnny","","","SC","","8432633115",8433790062,"","driver","active","BFT","","","","","Fri:2,Fri:3,Fri:4,Fri:5","","","","","",""); insert_dbVolunteers($v);
	
// add new client data: $c = new Client ($id, $chain_name, $area, $type, $address, $city, $state, $zip, $geocoordinates,
//	                        $phone1, $phone2, $days, $feed_america, $weight_type, $notes); insert_dbClients($c);

}

?>
</body>
</html>