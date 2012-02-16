<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * Month class for Homeplate
 * @author Nicholas Wetzel
 * @version February 15, 2012
 */
class Month {
	private $id;			// "yy-mm" unique identifier for a month
	private $status; 		// "unpublished", "published" or "archived"
	private $first_day;     // Day of the week (1-7, Monday = 1) for yy-mm-01
	private $routes;        // array of Route ids, one for each day of the month
	private $notes;			// notes written by the team captain or coordinator
	

	//Constructor for each Month
	function __construct($id, $status, $first_day, $routes, $notes){
		$this->id = $id;
		$this->status = $status;
		$this->first_day = $first_day;
		 if ($routes == "") 
        	$this->routes = array(); 
        else 
        	$this->routes = explode(',',$routes);
         if ($notes == "") 
        	$this->notes = array(); 
        else 
        	$this->notes = explode(',',$notes);
	}
	
	//Getter functions for the Month
	function get_id(){
		return $this->id;
	}
	function get_status(){
		return $this->status;
	}
	function get_first_day(){
		return $this->first_day;
	}
	function get_routes(){
		return $this->routes;
	}
	function get_notes(){
		return $this->notes;
	}
}