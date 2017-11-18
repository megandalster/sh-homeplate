<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once 'database/dbVolunteers.php';
include_once 'database/dbClients.php';
include_once 'database/dbRoutes.php';
include_once 'database/dbStops.php';
include_once 'domain/Route.php';
include_once 'domain/Stop.php';

function update_ftp() {
	$areas = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
	autogenerate_routes();  // be sure we have next 3 weeks worth of routes in database
	$todayUTC = time();
	$mondaylastweek = strtotime('last monday', strtotime('last monday', $todayUTC));
	$weekfromtodayUTC = $todayUTC+604800;
	$mondaynextweek = strtotime('last monday', strtotime('tomorrow',$weekfromtodayUTC));
	// we are mid-week and we want to update any new files that are there
	for ($day = $mondaylastweek; $day < $mondaynextweek+604800; $day += 86400) {

	
		//if ($day > $todayUTC) 
	  		ftpout($day, $areas); // update ftpout for future days
	  	if ($day <= $todayUTC) 
	  		ftpin($day);  // grab data from any past days, including today!
	}
}

function ftpout($day, $areas) {
	$yymmdd = date('y-m-d',$day);
	$fulldate = date('l F j, Y',$day);
	$twoweeksagoyymmdd = date('y-m-d',$day-1209600);
	foreach ($areas as $area=>$area_name) {
	// remove the file for 2 weeks ago from $day: date('y-m-d',$day-604800), if it's there
	    $filename = dirname(__FILE__).'/../homeplateftp1/ftpout/'.$twoweeksagoyymmdd."-".$area.".csv";
		
		//Dev path
		//$filename = realpath('../../../ftpout/'.$twoweeksagoyymmdd."-".$area.".csv");
		@unlink($filename);
	// create a new file for $day
		$filename = dirname(__FILE__).'/../homeplateftp1/ftpout/'.$yymmdd."-".$area.".csv";
//local dev path
		//$filename = realpath('../../../ftpout/'.$yymmdd."-".$area.".csv");
		$handle = fopen($filename, "w");
		

		
	//	echo "creating ftp file for tablet: ". $filename . "<br />";
		
		/*
		$dirName = realpath('../../../homeplateftp1/ftpout/');
		if (file_exists($dirName)) {
			echo("File path " . $dirName . " found.<br />");
		}
		else{
			echo("File path " . $dirName . " NOT found.<br />");
		}
		
		//echo "File Name:";
		//echo $filename . "<br />";
		*/

	// get the data to put out there
	
		$theRoute = get_route($yymmdd."-".$area);
		
		
		if (!$theRoute){
			//echo "ftpout Error: no route available for ".$fulldate." in ".$area_name . "<br />";
			return "ftpout Error: no route available for ".$fulldate." in ".$area_name;
			}
		else {	
		
			$theDayCaptain = retrieve_dbVolunteers($theRoute->get_teamcaptain_id());
			$drivers = array();
			$pickup_stops = array(); 
			$dropoff_stops = array();
			foreach ($theRoute->get_drivers() as $driver_id) {
				$driver = retrieve_dbVolunteers($driver_id);
				if ($driver)
					$drivers[] = $driver->get_first_name()." ".$driver->get_last_name();
				else $drivers[] = $driver_id;
			}
			$drivers[] = "Other";
			
				
			foreach ($theRoute->get_pickup_stops() as $pickup_stopid) {
				$pickup_stop = retrieve_dbClients(substr($pickup_stopid,12));
				if (!$pickup_stop || $pickup_stop->get_weight_type()=="pounds"){
					$pickup_stops[] = substr($pickup_stopid,12).",0";
				}
				else 
					$pickup_stops[] = $pickup_stop->get_id().",0,Meat:0,Frozen:0,Bakery:0,Grocery:0,Dairy:0,Produce:0";
		 	}
		 	// $pickup_stops[] = "Other,0";
			foreach ($theRoute->get_dropoff_stops() as $dropoff_stopid) {
				$dropoff_stops[] = substr($dropoff_stopid,12).",0";
			}
			// $dropoff_stops[] = "Other,0";
		
			$allVolunteers = getall_dbVolunteers();
			$volunteerNames = array();
			
			foreach($allVolunteers as $aVolunteer){
				$volunteerNames[] = $aVolunteer->get_first_name() . " " . $aVolunteer->get_last_name();
			}
			
			//donors and recipients
			$allClients = getall_dbClients();
			$donors = array();
			$recipients = array();
			foreach($allClients as $aClient){
				if($aClient->get_type() == "donor"){
					$donors[] = $aClient->get_id();
				}
				else{
					$recipients[] = $aClient->get_id();
				}
			}
			
			//echo "writing file " . $filename . "<br />";
			
	// line 1
	    	if ($theDayCaptain) fputcsv($handle, array(
	    		$yymmdd."-".$area, $area_name, $fulldate, 
	    		$theDayCaptain->get_first_name() . " " . $theDayCaptain->get_last_name(),
	    		$theDayCaptain->get_nice_phone1()
	    		),";");
            else fputcsv($handle, array(
	    		$yymmdd."-".$area, $area_name, $fulldate, 
	    		"no day captain","555-555-5555"
	    		),";");
	// line 2		
			fputcsv($handle, $drivers, ";");
	// line 3
			fputcsv($handle, $pickup_stops, ";");
	// line 4
			fputcsv($handle, $dropoff_stops,";");
			
	//line 5 full vol list
		fputcsv($handle, $volunteerNames,";");
		
	//line 6 full Donor list
		fputcsv($handle, $donors,";");
	
	//line 7 full Recipient list
		fputcsv($handle, $recipients,";");
		
		}
			//echo "closing file handle<br />";
			fclose($handle);
			//$fileContentx = file_get_contents($file);
		
		//	echo "<pre>" . $fileContentx . "</pre>";
		}
	// close the file
		

	//echo "ftp out done<br />";
	
	
}



function ftpin($day) {
	$areas = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
	$deviceIds = array("8c5328005a8d7784", // allens tablet
				"6b2b51166c2b321f","387a6442e578d02f","f17f64f993d2b19b","bd3eb9c3c3bd44ba",  //jons 7 tablets
				"c930db8fe6dccd30","3d28c762d6862027","2fc8453a13e544a8","91ba397615181245", 
				"3d741b4a9d4676ff", //dev tablet
				"43c0cbf7509044e8", //PT dev tablet
				"3A8E00285AC36131", //New Homeplate tablets
				"33DF3639D186AC7", 
				"35866C36A130677C",
				"908b4dfcee3e90f0",
				"bea0b2f0390264a4",
				"300BFECC9D846131",
				"3D73C3D17FD0043A",
				"3F385F7EA21C91C2",
				"95d13979f48f5c7f",
				"3013FD008A318D10",
				"167a036c74b1b492",
				"3D3FB47075B22A72", //?bogus tablet id?
				"335F08296E03B6B8", //?bogus tablet id?
				"d66bb4aa40e073ca",
				"ab92221e6ada959c", //new tablet id
				"486e7427693b6422",
				"5e47d9a9796482da",
				"4203b94c00da12f3",
				"306F69703B092554",
				"33DF36397D186AC7",
				"336430502B44E4FF", //new tablet id added 11/24/2014
				"1ed5e3b37488588", //new android id added 11/24/2014
				"328878A6F614A7DA", //new tablet id added 11/24/2014
				"a207427fee8357ab", //new android id added 11/24/2014
				"34FBAA6A2CFAD3C7", //new android id added 8/12/2015
				"49f2420a374c1b0f", //new android id added 8/18/2015					
				"ef553c628b2c11b2",
				"d3065bbe7dd5c4ad"
				);
				



	$yymmdd = date('y-m-d',$day);
	$twoweeksagoyymmdd = date('y-m-d',$day-1209600);
	$day_of_week = date ("D", $day);
	foreach ($areas as $area=>$area_name) {
		foreach ($deviceIds as $deviceId) {
		
		
	// look for a file for $day and $deviceId
			//TODO: Refactor for dependency injection
			//prod path
			$filename = dirname(__FILE__).'/../homeplateftp1/ftpin/'.$yymmdd."-".$area."-".$deviceId.".csv";	
			//QA path
			//$filename = realpath('../../../ftpin/'.$yymmdd."-".$area."-".$deviceId.".csv");		
			//$filename = dirname(__FILE__) . '/../../../ftpin/' . $yymmdd."-".$area."-".$deviceId.".csv";			
			//dev path
			//$filename = 'C:\\Projects\\WebApps\\HomePlate\\homeplateftp1\\ftpin\\'.$yymmdd."-".$area."-".$deviceId.".csv";
			//echo  dirname(__FILE__) . '/../../../ftpin/' . $yymmdd."-".$area."-".$deviceId.".csv"; //; $yymmdd."-".$area."-".$deviceId.".csv" . "<br />";
			
			if (file_exists($filename)) {
				$handle = fopen($filename, "r+");
				//echo "Processing ftp in file:";
				//echo $filename . "<br />";
				
	// line 1			
				$line1 = fgetcsv($handle, 0, ";"); 
				$id = substr($line1[0],0,12);
				$tabletid = substr($line1[0],13);
				$notes = $tabletid.";".$line1[5]."-".$line1[6];
				$r = get_route($id);
				
				//echo $r->get_notes() . "  " . $tabletid . " strpos = ". strpos($r->get_notes(),"adam") . "<br />";
				
				$pos = strpos($r->get_notes(),$tabletid);

				
				if ($pos === false) { // WEIGHTS ALREADY RECORDED FOR THIS TABLET, SKIP IT
					
				}
				else{
				
				//	echo " WEIGHTS ALREADY RECORDED FOR THIS TABLET, SKIP IT<br />";
					fclose($handle);
					continue;
				}
				$teamcaptain = $line1[3];
				
	// line 2			
				$drivers = array();
				$availables = getall_drivers_available($area, $day_of_week);
				//echo "availables "; //var_dump($availables);
				$line2 = fgetcsv($handle, 0, ";");
				
				foreach ($line2 as $d) {
					if (strpos($d,"*")>0)
					    continue;
					$j = strpos($d,"#");
					if ($j>0)
						$d = substr($d,0,$j);
					if (strpos($d," ")>0) 
						$i=strpos($d," "); 
					else $i=0;
					$d_first = substr($d,0,$i);
					$d_last = substr($d,$i+1);
					$driver_id = $d;
					
					//echo "setting driver stats:" . $av->get_first_name() . "==" . $d_first . " && " . $av->get_last_name() . "==" .  $d_last . ":<br />";
					if($d_first != 'Other'){
						$theVol = retrieve_dbVolunteersByName($d_first, $d_last);
						//echo "updating driver<br />";
						if ($theVol instanceof Volunteer) {
							$theVol->set_lastTripDate($yymmdd);
							$theVol->set_tripCount(($theVol->get_tripCount() + 1));
							update_dbVolunteers($theVol);
						}
						else{
							//echo  "driver not found:" .  $d_first . " && " .  $d_last . ":<br />";
						}
					}		
							
					foreach($availables as $av) {
						
						if ($av->get_first_name() == $d_first && $av->get_last_name() == $d_last) {
							$driver_id = $av->get_id();
						
							break;
						}
					}
					$drivers[] = $driver_id;	
					
					
				}
				//echo "line 2 = ".$drivers[0].$drivers[1];
				
	// line 3
				$line3 = fgetcsv($handle, 0, ";");
				$pickup_stops = array();
				foreach ($line3 as $pickup_stop) {
					$pickup_stop = trim(str_replace('\'','',htmlentities(str_replace('\&','and',str_replace('\#',' ',$pickup_stop)))));
					$pickup_stops[] = $id.$pickup_stop;
				}
				//echo "line 3 = ".$pickup_stops[0].$pickup_stops[1];
				
	// line 4
				$line4 = fgetcsv($handle, 0, ";");
				$dropoff_stops = array();
				foreach ($line4 as $dropoff_stop) {
					$dropoff_stop = trim(str_replace('\'','',htmlentities(str_replace('\&','and',str_replace('\#',' ',$dropoff_stop)))));
					$dropoff_stops[] = $id.$dropoff_stop;
				}
				//echo "line 4 = ".$dropoff_stops[0].$dropoff_stops[1];
				
	// save and merge the data, allowing that more than one tablet has uploaded data 
	// on the same day and area.
	// do not allow more than one to pickup or dropoff at the same stop.
				if ($r->get_status()=="completed") { // merging additional tablet's data
					$r->merge_drivers($drivers);
					
					//echo "merging additional tablet's data<br />";
					$r->merge_pickup_stops(rebuild_original_stops($r, "pickup"), $pickup_stops);
					$r->merge_dropoff_stops(rebuild_original_stops($r, "dropoff"), $dropoff_stops);
					$r->merge_notes($notes);
				}
				else {
					$r->set_drivers($drivers);
					$r->set_pickup_stops($pickup_stops);
					$r->set_dropoff_stops($dropoff_stops);
					$r->set_notes($notes);
					$r->set_status("completed");
				}
				// if (has_nonzero_pickup_weight($r) || has_nonzero_dropoff_weight($r))
				update_completed_dbRoutes($r);
				@unlink($filename);  // delete the file after saving its weights
				// rewrite the file and close it
				fclose($handle);
			}
			else{
				//echo "file not found in ftpin:" . $filename . "<br />";
			}
		}
	}
}

//$mytime=$today = strtotime("today")+1209600; //mktime(9, 23, 33, 8, 31, 2013);
/*
$currentDate =strtotime('12/22/2013'); //date("y-m-d",strtotime('now'));
echo $currentDate;
ftpin($currentDate);
echo "echo base";
*/
?>
