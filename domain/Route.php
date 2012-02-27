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
							// e.g. ["malcom1234567890", Òsandi8437891234Ó]
	private $teamcaptain_id;
	private $stops;			// array of stop id's for this Route
    private $notes;			// notes written by the team captain or driver 

		/**
         * constructor for a Route
         */
    function __construct($id, $drivers, $teamcaptain_id, $stops, $notes){
    	
    	$this->id = $id;
    	
    	if ($drivers == "") 
        	$this->drivers = array();
        else 
        	$this->drivers = explode(',', $drivers);
        	
    	$this->teamcaptain_id = $teamcaptain_id;
    	
    	if ($stops = "")
    		$this->stops = array();
    	else
    		$this->stops = explode(',', $stops);
    		
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
    function get_stops() {
    	return $this->stops;
    }
    function get_day() {
    	$timestamp = mktime(0,0,0,substr($this->id,3,2),substr($this->id,6,2),substr($this->id,0,2)); 	
    	return date('l F j, Y', $timestamp);
    }
    function get_area() {
    	$areas = array("HHI"=>"Hilton Head Island", "SUN"=> "Sun City", "BFT" => "Beaufort");
    	return $areas[substr($this->get_id(),9)];
    }
    function get_notes() {
    	return $this->notes;
    }
}
?>