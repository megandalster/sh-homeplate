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
	$mondaythisweek = strtotime('last monday', strtotime('tomorrow',$todayUTC));
	$weekfromtodayUTC = $todayUTC+604800;
	$mondaynextweek = strtotime('last monday', strtotime('tomorrow',$weekfromtodayUTC));
	// we are mid-week and we want to update any new files that are there
	for ($day = $mondaythisweek; $day < $mondaynextweek+604800; $day += 86400) {
	  	if ($day > $todayUTC) 
	  		ftpout($day, $areas); // update ftpout for future days
	  	if ($day <= $todayUTC) 
	  		ftpin($day);  // grab data from any past days, including today
	}
}

function ftpout($day, $areas) {
	$yymmdd = date('y-m-d',$day);
	$fulldate = date('l F j, Y',$day);
	$twoweeksagoyymmdd = date ('y-m-d',$day-1209600);
	foreach ($areas as $area=>$area_name) {
	// remove the file for a week ago from $day: date('y-m-d',$day-604800), if it's there
	    $filename = dirname(__FILE__).'/../homeplateftp/ftpout/'.$twoweeksagoyymmdd."-".$area.".csv";
		@unlink($filename);
	// create a new file for $day
	    $filename = dirname(__FILE__).'/../homeplateftp/ftpout/'.$yymmdd."-".$area.".csv";
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
			foreach ($theRoute->get_pickup_stops() as $pickup_stopid) {
				$pickup_stop = retrieve_dbClients(substr($pickup_stopid,12));
				if (!$pickup_stop || $pickup_stop->get_weight_type()=="pounds"){
					$pickup_stops[] = substr($pickup_stopid,12).",0";
				}
				else 
					$pickup_stops[] = $pickup_stop->get_id().",0,Meat:0,Frozen:0,Bakery:0,Grocery:0,Dairy:0,Produce:0";
		 	}
		 	$pickup_stops[] = "Other,0";
			foreach ($theRoute->get_dropoff_stops() as $dropoff_stopid) {
				$dropoff_stops[] = substr($dropoff_stopid,12).",0";
			}
			$dropoff_stops[] = "Other,0";
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
	$deviceIds = array("8c5328005a8d7784");
	$yymmdd = date('y-m-d',$day);
	foreach ($areas as $area=>$area_name) {
		foreach ($deviceIds as $deviceId) {
	// look for a file for $day and $deviceId
			$filename = dirname(__FILE__).'/../homeplateftp/ftpin/'.$yymmdd."-".$area."-".$deviceId.".csv";	
			if (file_exists($filename)) {
				$handle = fopen($filename, "r");
	// line 1			
				$line1 = fgetcsv($handle, 0, ";");
			//	echo "line 1 = ".$line1[0].$line1[1];
				$id = substr($line1[0],0,12);
				$notes = "";  //  substr($line1[0],13)."_".$line1[5]."_".$line1[6];
				$teamcaptain = $line1[3];
	// line 2			
				$drivers = array();
				$availables = getall_drivers_available($area, $day);
				$ds = fgetcsv($handle, 0, ";");
				foreach ($ds as $d) {
					if (strpos($d," ")>=0) $i=strpos($d," "); else $i=-1;
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
				$ps = fgetcsv($handle, 0, ";");
				$pickup_stops = array();
				foreach ($ps as $pickup_stop)
					$pickup_stops[] = $id.$pickup_stop;
			//	echo "line 3 = ".$pickup_stops[0].$pickup_stops[1];
				
	// line 4
				$ds = fgetcsv($handle, 0, ";");
				$dropoff_stops = array();
				foreach ($ds as $dropoff_stop) 
					$dropoff_stops[] = $id.$dropoff_stop;
			//	echo "line 4 = ".$dropoff_stops[0].$dropoff_stops[1];
				
	// save the stuff
				$r = get_route($id);
				$r->set_drivers($drivers);
				$r->set_pickup_stops($pickup_stops);
				$r->set_dropoff_stops($dropoff_stops);
				$r->set_notes($notes);
				$r->set_status("completed");
				update_completed_dbRoutes($r);
				// close the file
				fclose($handle);
			}
		}
	}
}


?>
