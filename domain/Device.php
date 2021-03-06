<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen
 * Tucker.  This program is part of Homeplate, which is free software.  It comes
 * with absolutely no warranty.  You can redistribute and/or modify it under the
 * terms of the GNU Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/).
 *
 * Device class for Homeplate
 * @author Allen Tucker
 * @version February 8, 2018
 */
class Device {
    private $id;		// 16-digit device id, such as "532c5d0e6f5aca9d"
    private $status;	// active, inactive, or out of service (broken or retired)
    private $base;		//  where is the device being used (HH, Bluffton, Beaufort, or Office)
    private $owner;		// who is responsible for it
    private $date_activated;	// date placed in service with Homeplate
    private $last_used;	// date last used
    private $notes;		// any other notes about the device -- model, screen size, etc.

    function __construct($id, $status, $base, $owner, $date_activated, $last_used, $notes){
        $this->id = $id;
        $this->status = $status;
        $this->base = $base;
        $this->owner = $owner;
        $this->date_activated = $date_activated;
        $this->last_used = $last_used;
        $this->notes = $notes;
    }
   
    // getter functions
    function get_id() {
        return $this->id;
    }
    function get_status() {
        return $this->status;
    }
    function get_base() {
        return $this->base;
    }
    function get_owner() {
        return $this->owner;
    }
    function get_date_activated() {
        return $this->date_activated;
    }
    function get_last_used() {
    	return $this->last_used;
    }
    function get_notes() {
        return $this->notes;
    }
    function set_base($b) {
    	$this->base = $b;
    }
    function set_last_used($last_used) {
    	$this->last_used = $last_used;
    }
    function set_owner($o) {
        $this->owner = $o;
    }
    
}
?>