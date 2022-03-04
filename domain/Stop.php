<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/). new Stop
*/

/*
 * Stop class for Homeplate
 * @author Nicholas Wetzel and Allen Tucker
 * @version May 8, 2012
 */

/*
 * This class serves as the basis for all actions associated with the a Stop.
 * A stop can be constructed and edited with the functions provided by this class.
 */

class Stop {
	private $id;		// String $route_id . $Client_id
	private $route_id;
	private $client_id; 
	private $type;		// "donor" or "recipient"
	private $items;		// array of foodtype:weight pairs for this stop
    private $rescued_weight;
    private $transported_weight;
    private $purchased_weight;
    private $food_drive_weight;
	private $total_weight; // total weight for this stop
	private $notes;		// notes written by the driver 

	// Constructor for an indvidual Stop/Client
	function __construct($route_id, $client_id, $type, $items, $notes){
		$this->id = $route_id . $client_id;
		$this->route_id = $route_id;
		$this->client_id = $client_id;
		$this->type = $type;
		if (strpos($items,":")>0) {
			$this->items = explode(',',$items);  	
        	$this->set_all_totals();
		} 
        else {
        	$this->items = array();
        	$this->total_weight = $items==""?0:$items;
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
	// Returns the weight of a certain food type based on the numeric index parameter.
	function get_item_weight($index){
		$item_weights = array();
		foreach($this->items as $item){
        	$i = explode(':',$item);
        	$item_weights[] = $i[1];
		}
		return $item_weights[$index] ?? 0;
//        return $item_weights[$index] ==""?0: $item_weights[$index] ?? 0;
	}
	function get_notes(){
		return $this->notes;
	}
    function get_rescued_weight() {
        return $this->rescued_weight==""?0:$this->rescued_weight;
    }
    function get_transported_weight() {
        return $this->transported_weight==""?0:$this->transported_weight;
    }
    function get_purchased_weight() {
        return $this->purchased_weight==""?0:$this->purchased_weight;
    }
    function get_food_drive_weight() {
        return $this->food_drive_weight==""?0:$this->food_drive_weight;
    }
	function get_total_weight() {
	    return $this->total_weight==""?0:$this->total_weight;
	}
	function get_date() {
		return substr($this->id,0,8);
	}
	
	// Setter functions for the Stop class.
	
	// Sets the total weight by accessing and summing the weights
	// of each food type in the stop's item array.
	function set_all_totals(){
		$this->total_weight = 0;
		foreach($this->items as $item){
			$i = explode(':',$item);
			$itemWeight = intval($i[1],10);
			//if(itemWeight > 0){
			//echo $itemWeight . "<br />"; 
				$this->total_weight += $itemWeight; 		
			//}
		}
	}
    
    function set_rescued_weight($weight){
        if ($weight == '') $weight = 0;
        $this->rescued_weight = $weight;
    }
    function set_transported_weight($weight){
        if ($weight == '') $weight = 0;
        $this->transported_weight = $weight;
    }
    function set_purchased_weight($weight){
        if ($weight == '') $weight = 0;
        $this->purchased_weight = $weight;
    }
    function set_food_drive_weight($weight){
        if ($weight == '') $weight = 0;
        $this->food_drive_weight = $weight;
    }
	// Sets the stop's total weight to the specified numeric value.
	function set_total_weight($weight){
        if ($weight == '') $weight = 0;
		$this->total_weight = $weight;
	}
	function set_id($new_id){
		$this->id = $new_id;
	}
	// Adds an item to the end of the stop's array of items.
	function add_item ($new_item) {
        $this->items[] = $new_item;
        $this->set_all_totals();    
    }
    // Sets the specified item to the specified index into the item array.
    function set_item($index, $new_item){
    	$this->items[$index] = $new_item;
    	$this->set_all_totals();
    }
    // items is an array of 6 foodtype:weight pairs
    function set_item_weights($items){
		$this->items = $items;
		$this->set_all_totals();
	}
	function set_notes($new_notes){
		$this->notes = $new_notes;
	}
	function remove_all_items(){
		$this->items = array();
		$this->set_all_totals();
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