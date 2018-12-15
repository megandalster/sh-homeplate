<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * ScheduleEntry class for Homeplate
 * @author Hartley Brody
 * @version February 16, 2012
 */
class ScheduleEntry {	
	private $area;	// "HHI", "SUN" (alternate week calendars), or "BFT" (5-week monthly calendar)
	private $id;	// "ddd:w" identifies a day on the master schedule
					// e.g., "Mon:1st" means 1st Monday (5-week monthly calendar)
					// "Mon:odd" means every odd Monday (alternate week calendar)
	private $drivers;   // array of driver id's scheduled for this run,
					// e.g. ["malcom1234567890", �sandi8437891234�]
	private $notes;		// notes to/from the team captain or coordinator

	
	function __construct($area, $id, $drivers, $notes){                
        $this->id       	= $id;      
        $this->area 		= $area; 
        $this->notes 		= $notes;     
        
        if ($drivers == "")
		   $this->drivers = array();
		else
		   $this->drivers = explode(',',$drivers);        
    }
    
    //getter functions
	function get_area() {
        return $this->area;
    }
	function get_day() {
        return substr($this->id,0,3);
    }
    function get_week() {
    	return substr($this->id,4);
    }
    function get_group() {
        return substr($this->id,4);
    }
    function get_id() {
        return $this->id;
    }
    function get_drivers(){
        return $this->drivers;
    }
    function get_notes(){
        return $this->notes;
    }
    
    //setter functions ... can be added later as needed
    function set_drivers($d){
        $this->drivers = $d;
    }
        
}
?>