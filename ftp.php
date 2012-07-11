<?php
include_once 'database/dbVolunteers.php';
include_once 'database/dbClients.php';
include_once 'database/dbRoutes.php';
include_once 'database/dbStops.php';
include_once 'domain/Route.php';
include_once 'domain/Stop.php';

function update_ftp(){
	$todayUTC = time();
	$mondaythisweek = strtotime('last monday', strtotime('tomorrow',$todayUTC));
	$weekfromtodayUTC = $todayUTC+604800;
	$mondaynextweek = strtotime('last monday', strtotime('tomorrow',$weekfromtodayUTC));
	// do this for each of 7 days, but only if today is a Sunday
	if (date('N',$todayUTC) == 7)
	  for ($day = $mondaynextweek; $day < $mondaynextweek+604800; $day += 86400) {
	  	ftpout($day);
	  	ftpin($day);
	  }
	// otherwise, we are mid-week and we want to just update the files that are there
	else
	  for ($day = $mondaythisweek; $day < $mondaythisweek+604800; $day += 86400) {
	  	ftpout($day);
	  	ftpin($day);
	  }
}

function ftpout($day) {
	$areas = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
	$yymmdd = date('y-m-d',$day);
	$fulldate = date('l F j, Y',$day);
	$weekagoyymmdd = date ('y-m-d',$day-604800);
	echo "we are here with ftpout";
	foreach ($areas as $area=>$area_name) {
	// remove the file for a week ago from $day: date('y-m-d',$day-604800), if it's there
	    $filename = dirname(__FILE__).'/../homeplateftp/ftpout/'.$weekagoyymmdd."-".$area.".csv";
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
			$handle = fopen($filename, "r");
			if ($handle) {
	// line 1
				fgetcsv($handle, $line1, ";");
				$id = substr($line1[0],0,12);
				$notes = substr($line1[0],13).";".$line1[5].";".$line1[6];
				$teamcaptain = $line1[3];
	// line 2
				fgetcsv($handle, $drivers, ";");
	// line 3
				fgetcsv($handle, $pickup_stops, ";");
	// line 4
				fgetcsv($handle, $dropoff_stops, ";");
	// save the stuff
				$r = get_route($id);
				$r->set_notes($notes);
				$r->set_status("completed");
				$r->set_drivers($drivers);
				$r->set_pickup_stops($pickkup_stops);
				$r->set_dropoff_stops($dropoff_stops);
				update_dbRoutes($r);
				
				// close the file
				fclose($handle);
			}
		}
	}
}

?>