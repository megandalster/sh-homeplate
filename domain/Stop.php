<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * Stop class for Homeplate
 * @author Nicholas Wetzel
 * @version February 15, 2012
 */
class Stop {
	private $id;		// String $route_id . $Client_id
	private $items;		// array of foodtype=>weight pairs for this stop
	private $notes;		// notes written by the driver 

	//Constructor for an indvidual Stop/Client
	function __construct($id, $items, $notes){
		$this->id = $id;
		 if ($items == "") 
        	$this->items = array(); 
        else 
        	$this->items = explode(',',$items);
         if ($notes == "") 
        	$this->notes = array(); 
        else 
        	$this->notes = explode(',',$notes);
	}
	
	//Getter functions for the Stop/Client
	function get_id(){
		return $this->id;
	}
	
	function get_items(){
		return $this->items;
	}
	
	function get_notes(){
		return $this->notes;
	}
}
?>
