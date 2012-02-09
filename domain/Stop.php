<?php
class Stop {
	private $id;		// String $route_id . $Client_id
	private $items;		// array of foodtype=>weight pairs for this stop
	private $notes;		// notes written by the driver 

	//Constructor for an indvidual Stop/Client
	function __construct($id, $items, $notes){
		$this->id = $id;
		$this->items = $items;
		$this->notes = $notes;
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
