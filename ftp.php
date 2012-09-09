<?php
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
	$mondaylastweek = strtotime('last monday', $todayUTC);
	$weekfromtodayUTC = $todayUTC+604800;
	$mondaynextweek = strtotime('last monday', strtotime('tomorrow',$weekfromtodayUTC));
	// we are mid-week and we want to update any new files that are there
	for ($day = $mondaylastweek; $day < $mondaynextweek+604800; $day += 86400) {
		if ($day > $todayUTC) 
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
		@unlink($filename);
	// create a new file for $day
	    $filename = dirname(__FILE__).'/../homeplateftp1/ftpout/'.$yymmdd."-".$area.".csv";
		$handle = fopen($filename, "w");
	// get the data to put out there
		$theRoute = get_route($yymmdd."-".$area);
		if (!$theRoute)
			return "ftpout Error: no route available for ".$fulldate." in ".$area_name;
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
	// line 1
	    	fputcsv($handle, array(
	    		$yymmdd."-".$area, $area_name, $fulldate, 
	    		$theDayCaptain->get_first_name() . " " . $theDayCaptain->get_last_name(),
	    		$theDayCaptain->get_nice_phone1()
	    		),";");
	// line 2		
			fputcsv($handle, $drivers, ";");
	// line 3
			fputcsv($handle, $pickup_stops, ";");
	// line 4
			fputcsv($handle, $dropoff_stops,";");
		}
	// close the file
		fclose($handle);
	}
}

function ftpin($day) {
	$areas = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
	$deviceIds = array("8c5328005a8d7784", // allens tablet
				"6b2b51166c2b321f","387a6442e578d02f","bd3eb9c3c3bd44ba",  //jons 6 tablets
				"c930db8fe6dccd30","3d28c762d6862027","2fc8453a13e544a8");
	$yymmdd = date('y-m-d',$day);
	$twoweeksagoyymmdd = date('y-m-d',$day-1209600);
	$day_of_week = date ("D", $day);
	foreach ($areas as $area=>$area_name) {
		foreach ($deviceIds as $deviceId) {
	// get rid of old data
	//		$filename = dirname(__FILE__).'/../homeplateftp1/ftpout/'.$twoweeksagoyymmdd."-".$area.".csv";
	//		@unlink($filename);
	// look for a file for $day and $deviceId
			$filename = dirname(__FILE__).'/../homeplateftp1/ftpin/'.$yymmdd."-".$area."-".$deviceId.".csv";	
			if (file_exists($filename)) {
				$handle = fopen($filename, "r+");
	// line 1			
				$line1 = fgetcsv($handle, 0, ";"); 
				$id = substr($line1[0],0,12);
				$tabletid = substr($line1[0],13);
				$notes = $tabletid.";".$line1[5]."-".$line1[6];
				$r = get_route($id);
				if ($r && strpos($r->get_notes(),$tabletid) > 0) { // WEIGHTS ALREADY RECORDED FOR THIS TABLET, SKIP IT
					fclose($handle);
					continue;
				}
				$teamcaptain = $line1[3];
				
	// line 2			
				$drivers = array();
				$availables = getall_drivers_available($area, $day_of_week);
			//	echo "availables "; var_dump($availables);
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
					foreach($availables as $av) 
						if ($av->get_first_name() == $d_first && $av->get_last_name() == $d_last) {
							$driver_id = $av->get_id();
							break;
						}
					$drivers[] = $driver_id;	
				}
			//	echo "line 2 = ".$drivers[0].$drivers[1];
				
	// line 3
				$line3 = fgetcsv($handle, 0, ";");
				$pickup_stops = array();
				foreach ($line3 as $pickup_stop) {
					$pickup_stop = trim(str_replace('\'','',htmlentities(str_replace('\&','and',str_replace('\#',' ',$pickup_stop)))));
					$pickup_stops[] = $id.$pickup_stop;
				}
			//	echo "line 3 = ".$pickup_stops[0].$pickup_stops[1];
				
	// line 4
				$line4 = fgetcsv($handle, 0, ";");
				$dropoff_stops = array();
				foreach ($line4 as $dropoff_stop) {
					$dropoff_stop = trim(str_replace('\'','',htmlentities(str_replace('\&','and',str_replace('\#',' ',$dropoff_stop)))));
					$dropoff_stops[] = $id.$dropoff_stop;
				}
			//	echo "line 4 = ".$dropoff_stops[0].$dropoff_stops[1];
				
	// save and merge the data, allowing that more than one tablet has uploaded data 
	// on the same day and area.
	// do not allow more than one to pickup or dropoff at the same stop.
				if ($r->get_status()=="completed") { // merging additional tablet's data
					$r->merge_drivers($drivers);
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
				if (has_nonzero_pickup_weight($r) || has_nonzero_dropoff_weight($r))
					update_completed_dbRoutes($r);
				// @unlink($filename);  // delete the file after saving its weights
				// rewrite the file and close it
				fclose($handle);
			}
		}
	}
}


?>
