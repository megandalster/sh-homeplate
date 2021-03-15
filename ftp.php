<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once 'database/dbVolunteers.php';
include_once 'database/dbClients.php';
include_once 'database/dbRoutes.php';
include_once 'database/dbDevices.php';
include_once 'database/dbStops.php';
include_once 'domain/Route.php';
include_once 'domain/Device.php';
include_once 'domain/Stop.php';

function update_ftp() {
	$areas = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
	autogenerate_routes();  // be sure we have next 3 weeks worth of routes in database
	$todayUTC = time();
	$mondaylastweek = strtotime('last monday', strtotime('last monday', $todayUTC));
	$weekfromtodayUTC = $todayUTC+604800;
	$mondaynextweek = strtotime('last monday', strtotime('tomorrow',$weekfromtodayUTC));
	$devices = getall_dbDevices();
	// we are mid-week and we want to update any new files that are there
	for ($day = $mondaylastweek; $day < $mondaynextweek+604800; $day += 86400) {
	    // if ($day > $todayUTC) 
	  		ftpout($day, $areas); // update ftpout for future days
	  	if ($day <= $todayUTC) 
	  		ftpin($day,$devices);  // grab data from any past days, including today!
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
	    		$theDayCaptain->nice_phone($theDayCaptain->get_phone1()),
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



function ftpin($day,$devices) {
	$areas = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
	$yymmdd = date('y-m-d',$day);
	$twoweeksagoyymmdd = date('y-m-d',$day-1209600);
	$day_of_week = date ("D", $day);
	foreach ($areas as $area=>$area_name) {
		foreach ($devices as $device) {
			$deviceId = $device->get_id();
		
	// look for a file for $day and $deviceId
			
			$filename = dirname(__FILE__).'/../homeplateftp1/ftpin/'.$yymmdd."-".$area."-".$deviceId.".csv";	
			//QA path
			//$filename = realpath('../../../ftpin/'.$yymmdd."-".$area."-".$deviceId.".csv");		
			//$filename = dirname(__FILE__) . '/../../../ftpin/' . $yymmdd."-".$area."-".$deviceId.".csv";			
			//dev path
			//$filename = 'C:\\Projects\\WebApps\\HomePlate\\homeplateftp1\\ftpin\\'.$yymmdd."-".$area."-".$deviceId.".csv";
			//echo  dirname(__FILE__) . '/../../../ftpin/' . $yymmdd."-".$area."-".$deviceId.".csv"; //; $yymmdd."-".$area."-".$deviceId.".csv" . "<br />";
			
			if (file_exists($filename)) {  // a route was created for this device, so pull the data if theere is any
				$handle = fopen($filename, "r+");
				//echo "Processing ftpin file:";
						
	// line 1	--  pull date, base, tablet ID, start, and end times		
				$line1 = fgetcsv($handle, 0, ";"); 
				$id = substr($line1[0],0,12);
				$date = substr($id,0,8);
				$base = substr($id,9,3);
				$notes = $deviceId.";".$line1[5]."-".$line1[6];
				$r = get_route($id);
				if ($r) { // if there's a route with this id
				    $pos = strpos($r->get_notes(),$deviceId);
				    if ($pos === false) { // WEIGHTS NOT YET RECORDED FOR THIS TABLET AND AREA
				    }
				    else{ // WEIGHTS ALREADY RECORDED FOR THIS TABLET AND AREA, SO SKIP IT 
					   fclose($handle);
					   continue;
				    }
				}
				else{ // no route with this id -- move on
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
							if ($date>$device->get_last_used()) {
							    $device->set_last_used($date);
							    $device->set_base($areas[$base]);
							    $device->set_owner($d_first." ".$d_last);
							    update_dbDevices($device);
							}	
							else {
							    // echo "date not more recent than last used" -- this shouldn't happen
							}
						}
						else{
							// echo  "driver not found:" .  $d_first . " && " .  $d_last . ":<br />";
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
				if (nonzero_weight($pickup_stops) || nonzero_weight($dropoff_stops)) {  // check that there's data to record
					   $r->merge_drivers($drivers);	
					   $r->merge_pickup_stops(rebuild_original_stops($r, "pickup"), $pickup_stops);
					   $r->merge_dropoff_stops(rebuild_original_stops($r, "dropoff"), $dropoff_stops);
					   $r->merge_notes($notes);
					   $r->set_status("completed");
					   update_completed_dbRoutes($r);
				}
				else {
				    echo "<br>zero-weight route ". $id ." skipped for tablet ". $notes; 
				}
//				@unlink($filename);  // delete the file after saving/merging its weights
				// rewrite the file and close it
				fclose($handle);
			}
			else{
				//echo "file not found in ftpin:" . $filename . "<br />";
			}
		}
	}
}
// see if any stop has a nonzero weight
function nonzero_weight($stops) {
    for ($i = 0; $i < sizeof($stops); $i++) {
        $astop = explode(',',$stops[$i]);
        if (sizeof($astop) > 1 && $astop[1] > 0)
            return true;
    }
    return false;
}

//$mytime=$today = strtotime("today")+1209600; //mktime(9, 23, 33, 8, 31, 2013);

?>
