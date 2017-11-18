<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * Client class for Homeplate
 * @author Hartley Brody
 * @version February 16, 2012
 */
class Client {
	private $id;     		// uniquely identifies the donor or recipient
							// e.g. ÒFood Lion #1698 Laurel BayÓ
	private $chain_name;	// e.g., ÒFood LionÓ (usually blank)
	private $area;			// "HHI", "SUN", or "BFT"
	private $type;			// "donor" or "recipient"
	private $address;       // street address Ð string
	private $city;			// city
	private $state;			// 2-letter abbrev - usually ÒSCÓ
	private $zip; 	      	// zip code Ð integer
	private $geocoordinates; // array pair: [latitude, longitude] for navigation
	private $phone1;		// primary phone
	private $phone2;		// secondary phone
	private $days;			// array of days for pick-up or delivery
							// e.g. [ÒMonÓ, ÒWedÓ]
	private $feed_america;	// ÒyesÓ or ÒnoÓ
	private $weight_type;	// variable for how items are recorded: 
							// ("pounds", "foodtype" or "foodtypeboxes")
	private $notes; 		// notes written by the team captain or coordinator
	
	private $email;			//contact email for client
	private $ContactName;
	private $deliveryAreaId; //
	
	//copied from volunteer class. question about use of "explode" with arrays
	
	function __construct($id, $chain_name, $area, $type, $address, $city, $state, $zip, $geocoordinates,
	                        $phone1, $phone2, $days, $feed_america, $weight_type, $notes, $email, $ContactName, $deliveryAreaId){                
        $this->id       	= $id;      
        $this->chain_name 	= $chain_name;      
        $this->area 		= $area;      
        $this->type 		= $type;      
        $this->address 		= $address;      
        $this->city 		= $city;      
        $this->state 		= $state;      
        $this->zip 			= $zip;
		$this->email		= $email;
		$this->ContactName = $ContactName;
		$this->deliveryAreaId = $deliveryAreaId;
		
		/*
        if ($weight_type=="")
        	$this->weight_type = "pounds";
        else
			$this->weight_type	= $weight_type;
		*/
		if( $this->type 	== "donor"){
			$this->weight_type = "foodtype";
		}
		else{
			 if ($weight_type=="")
        	$this->weight_type = "pounds";
        else
			$this->weight_type	= $weight_type;
		}
		
        $this->phone1 		= $phone1;
        $this->phone2 		= $phone2;

        if ($geocoordinates == "")
		   $this->geocoordinates = array();
		else
		   $this->geocoordinates = explode(',',$geocoordinates);
        
        if ($days == "")
		   $this->days = array();
		else
		   $this->days = explode(',',$days);
		   
        $this->feed_america	= $feed_america;
        $this->notes 		= $notes;
        
        
    }
    //getter functions
    function get_id() {
        return $this->id;
    }
    function get_chain_name() {
        return $this->chain_name;
    }
    function get_area() {
        return $this->area;
    }
    function get_type() {
        return $this->type;
    }
    function get_address() {
        return $this->address;
    }
    function get_city() {
        return $this->city;
    }
    function get_state() {
        return $this->state;
    }
    function get_zip() {
        return $this->zip;
    }
    function get_geo() {
        return $this->geocoordinates;
    }
    function get_phone1() {
        return $this->phone1;
    }
    function get_phone2() {
        return $this->phone2;
    }
    function get_days(){
        return $this->days;
    }
    function is_feed_america(){
        return $this->feed_america;
    }
    function get_weight_type(){
		if($this->get_type() == "donor"){
			return "foodtype";
		}
		else{
			return "pounds";
		}
		
    	//return $this->weight_type;
    }
    function get_notes(){
        return $this->notes;
    }
	
	function get_email(){
		return $this->email;
	}
	
	function get_ContactName(){
		return $this->ContactName;
	}
	
	function get_deliveryAreaId(){
		return $this->deliveryAreaId;
	}
	
	function get_nice_phone1 () {
    	if (strlen($this->phone1)==10)
    		return substr($this->phone1,0,3)."-".substr($this->phone1,3,3)."-".substr($this->phone1,6);
    	else if (strlen($this->phone1)==7)
    		return substr($this->phone1,0,3)."-".substr($this->phone1,3);
    	else return $this->phone1;
    }
    //setter functions ... can be added later as needed
        
}
?>