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
 * @author Nicholas Wetzel and Allen Tucker
 * @version February 15, 2012
 */
class Stop {
	private $id;		// String $route_id . $Client_id 
	private $type;		// "donor" or "recipient"
	private $items;		// array of foodtype:weight:lboxes:sboxes:trays:bags pairs for this stop
	private $total_weight; // total weight for this stop
	private $total_lboxes; // total number of large boxes for this stop
	private $total_sboxes; // total number of small boxes for this stop
	private $total_trays; // total number of trays for this stop
	private $total_bags; // total number of bags for this stop
	private $notes;		// notes written by the driver 

	//Constructor for an indvidual Stop/Client
	function __construct($route_id, $client_id, $type, $items, $notes){
		$this->id = $route_id . $client_id;
		$this->route_id = $route_id;
		$this->client_id = $client_id;
		$this->type = $type;
		if ($items == "") { 
        	$this->items = array();
        	$this->total_weight = 0;
		} 
        else {
        	$this->items = explode(',',$items);
        	
        	$this->set_all_totals();
	}
        $this->notes = $notes;
	}
	
	//Getter functions for the Stop
	function get_id(){
		return $this->id;
	}
	function get_route_id(){
		return $this->route_id;
	}
	function get_client_id(){
		return $this->client_id;
	}
	function get_type(){
		return $this->type;
	}
	function get_items(){
		return $this->items;
	}
	function get_notes(){
		return $this->notes;
	}
	function get_total_weight() {
		return $this->total_weight;
	}
	function get_total_lboxes() {
		return $this->total_lboxes;
	}
	function get_total_sboxes() {
		return $this->total_sboxes;
	}
	function get_total_trays() {
		return $this->total_trays;
	}
	function get_total_bags() {
		return $this->total_bags;
	}
	
	//setters
	function set_all_totals(){
		
		$this->total_weight = 0;
		$this->total_lboxes = 0;
		$this->total_sboxes = 0;
		$this->total_trays = 0;
		$this->total_bags = 0;
		
		foreach($this->items as $item){
        		$i = explode(':',$item);
        		
        		$this->total_weight += $i[1];
        		
        		$this->total_lboxes += $i[2];
        		
        		$this->total_sboxes += $i[3];
        		
        		$this->total_trays += $i[4];
        		
     			$this->total_bags += $i[5];
	}
	
	}
	function set_id($new_id){
		$this->id = $new_id;
	}
	function add_item ($new_item) {
        $this->items[] = $new_item;
        $this->set_all_totals();
        
    }
	function set_notes($new_notes){
		$this->notes = $new_notes;
	}
	function remove_item ($item_type) {
		for ($i=0; $i<sizeof($this->items);$i++) {
			if (strpos($this->items[$i],$item_type)==0) {
			    array_splice($this->items,$i,1);
			    break;
			}
		}
		$this->set_all_totals();
		
	}
}
?>
