<?php

class Pin {
	private $id;
	private $volunteer_id;
	private $pin_id;
	private $pinned_date;
    
    private $pin_name;

    function __construct($id, $volunteer_id, $pin_id, $pinned_date, $pin_name){
        $this->id = $id;
        $this->volunteer_id = $volunteer_id;
        $this->pin_id = $pin_id;
        $this->pinned_date = $pinned_date;
        $this->pin_name = $pin_name;
    }
    
    //getter functions
    function get_id() {
        return $this->id;
    }
    function get_volunteer_id() {
        return $this->volunteer_id;
    }
    function get_pin_id() {
        return $this->pin_id;
    }
    function get_pinned_date() {
        return $this->pinned_date;
    }
    function get_pin_name() {
        return $this->pin_name;
    }
}
?>
