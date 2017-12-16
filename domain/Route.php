<?php 
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/). 
*/

/*
 * Route class for Homeplate
 * @author Richardo Hopkins
 * @version February 8, 2012
 */
class Route {
	private $id;			// String: "yy-mm-dd-area" serves as a unique id 
							// for the route.  e.g. 11-12-29-HHI
 	private $drivers;       // array of driver id's scheduled for this route,
							// e.g. ["malcom1234567890", �sandi8437891234�]
							// for a completed route, an array of the driver
							// names who rode on the truck that day
	private $teamcaptain_id;
	private $pickup_stops;	// array of stop id's for donors
    private $dropoff_stops;	// array of stop id's for recipients
    private $status;		// "created", "published", or "completed"
    private $notes;			// for a completed route, deviceId;start time;end time
        					// e.g. 8c5328005a8d7784;08:30;13:30

		/**
         * constructor for a Route
         */
    function __construct($id, $drivers, $teamcaptain_id, $pickup_stops, $dropoff_stops, $status, $notes){
    // echo "new Route parameters = ".$id.$drivers.$teamcaptain_id.$pickup_stops.$dropoff_stops.$status.$notes;
    	$this->id = $id;  	
    	
    	if ($drivers == "") 
        	$this->drivers = array();
        else $this->drivers = explode(',', $drivers);
    	
        $this->teamcaptain_id = $teamcaptain_id;   	
    	
    	if ($pickup_stops == "")
    		$this->pickup_stops = array();
    	else $this->pickup_stops = explode(',', $pickup_stops);
    	
    	if ($dropoff_stops == "") 
    		$this->dropoff_stops = array();
    	else $this->dropoff_stops = explode(',', $dropoff_stops);
    	
    	if ($status == "") 
    		$this->status = "created";
    	else $this->status = $status;	
    	$this->notes = $notes;
    }
    
    // getter functions
    function get_id() {
    	return $this->id;
    }
    function get_drivers() {
    	return $this->drivers;
    }
    function get_teamcaptain_id() {
    	return $this->teamcaptain_id;
    }
    function get_pickup_stops() {
    	return $this->pickup_stops;
    }
    function get_num_pickups() {
    	return sizeof($this->pickup_stops);
    }
    function get_dropoff_stops() {
    	return $this->dropoff_stops;
    }
    function get_num_dropoffs() {
    	return sizeof($this->dropoff_stops);
    }
    function get_status() {
    	return $this->status;
    }
    function get_day() {
    	$timestamp = mktime(0,0,0,substr($this->id,3,2),substr($this->id,6,2),substr($this->id,0,2)); 	
    	return date('l F j, Y', $timestamp);
    }
    function get_area() {
    	$areas = array("HHI"=>"Hilton Head Island", "SUN"=> "Bluffton", "BFT" => "Beaufort");
    	return $areas[substr($this->get_id(),9)];
    }
    function get_notes() {
    	return $this->notes;
    }
    // setter functions
    function change_status($new_status) {
    	$this->status = $new_status;
    }
    function remove_driver($theDriver){
    	$size = count($this->drivers);
    	$remaining = array();
    	for ($i=0; $i<$size; $i++){
    		if ($this->drivers[$i] != $theDriver){
    			$remaining[] = $this->drivers[$i];
    		}
    	}
    	$this->drivers = $remaining;
    }
    function add_driver($theDriver){
    	$this->drivers[]= $theDriver;
    }
    function remove_pick_up($pick_up){
    	$size = count($this->pickup_stops);
    	for ($i=0; $i<$size; $i++){
    		if ($this->pickup_stops[$i] == $pick_up){
    			unset($this->pickup_stops[$i]);
    			break;
    		}
    	}
    }
    function add_pick_up($pick_up){
    	$this->pickup_stops[]= $pick_up;
    }
    function remove_drop_off($drop_off){
    	$size = count($this->dropoff_stops);
    	for ($i=0; $i<$size; $i++){
    		if ($this->dropoff_stops[$i] == $drop_off){
    			unset($this->dropoff_stops[$i]);
    			break;
    		}
    	}
    }
    function add_drop_off($drop_off){
    	$this->dropoff_stops[] = $drop_off;
    }
    function set_status($status){
     	$this->status = $status;
    }
    function set_notes($notes) {
    	$this->notes = $notes;
    }
    function set_drivers($drivers) {
    	$this->drivers = $drivers;
    }
	function set_pickup_stops($pickup_stops) {
    	$this->pickup_stops = $pickup_stops;
    }
    function set_dropoff_stops($dropoff_stops) {
    	$this->dropoff_stops = $dropoff_stops;
    }
	function merge_notes($notes) {
		$array_notes = explode(",",$this->notes);
		$array_notes = array_unique(array_merge($array_notes,array($notes)));
    	$this->notes = implode(",", $array_notes);
    }
    function merge_drivers($drivers) {
    	$this->drivers = array_unique(array_merge($this->drivers,$drivers));
    }
	function merge_pickup_stops($original_pickups, $pickup_stops) {
    	$this->pickup_stops = $this->special_merge($original_pickups,$pickup_stops);
    }
    function merge_dropoff_stops($original_dropoffs, $dropoff_stops) {
    	$this->dropoff_stops = $this->special_merge($original_dropoffs,$dropoff_stops);
    }
    // special merge function for pickup and dropoff stops
    // if stop2 has data, 
    //    if stop1 has no data, or there is no comparable stop1, then
    //        then replace stop1's data by stop2's
    // otherwise, leave stop1 alone
    function special_merge($array1, $array2) {
    	$array3 = $array1;
    	$originallimit = sizeof($array3);
    	foreach($array2 as $stop2) {
    		$stop2array = explode (",",$stop2);
    		$found = false;
    		if ($stop2array[1] != 0) {   // stop2 has data, so deal with it
    			// find a matching stop, if there is one
    			for ($i=0; $i<$originallimit; $i++) { 
    				$stop1array = explode (",",$array3[$i]);
    				// matching stop
    				if ($stop1array[0] == $stop2array[0]) {
    				  $found = true;
    				  if ($stop1array[1] == 0) { // no data there yet
    				  	$array3[$i] = $stop2;
    					$found = true;
    					break;
    				  }
    				  else {			// stop1 has data, keep it
    				  	$found = true;  
						if($stop1array[1] > 0 || $stop2array[1] > 0){
							$thisWeight = 0;
							if($stop1array[1] > 0){
								$thisWeight  = $thisWeight  + $stop1array[1] ;
							}
							if($stop2array[1] > 0){
								$thisWeight  = $thisWeight  + $stop2array[1] ;
							}
							$stop1array[1] = $thisWeight;
							$array3[$i] = implode(",", $stop1array);
						}
						
						
						
    					break;
    				  }	
    				}
    			} 	
    			if (!$found) { // we didnt find a match, so append stop2 to the list
    			    $array3[] = $stop2;
    			}
    		}  
    		else { // stop2 has no data, so skip it	
    		}
    	}
    	return $array3;
    }
}
?>