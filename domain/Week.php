<?php 
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * Week class for Homeplate
 * @author Richardo Hopkins
 * @version February 15, 2012
 */
class Week {
	private $id;			// Unique identifier: yy-mm-dd-area where dd is is 
							// the day of the month for Monday of the week.
 	private $routes;		// array of Route ids for this week 

		/**
         * constructor for a WeeklyReport
         */
    function __construct($id, $routes){
    	
    	$this->id = $id;
    	
    	if ($routes == "") 
        	$this->routes = array();
        else 
        	$this->routes = explode(',', $routes);
    }
    
    // getter functions
    function get_id() {
    	return $this->id;
    }
    function get_routes() {
    	return $this->routes;
    }
}
?>