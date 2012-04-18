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
	private $area;		// "HHI", "SUN", or "BFT"
	private $id;		// "ddd:w" identifies a day on the master schedule
					// e.g., "Mon:1" means Monday on week 1 of the month
	private $drivers;       // array of driver id's scheduled for this run,
					// e.g. ["malcom1234567890", “sandi8437891234”]
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
        
}
?>