<?php
class Client {
	private $id;     		// uniquely identifies the donor or recipient
							// e.g. �Food Lion Lau� 
	private $name;			// e.g. �Food Lion #1698 Laurel Bay�
	private $chain_name;	// e.g., �Food Lion� (usually blank)
	private $area;			// �HHI�, �SUN�, or �BFT�
	private $type;			// �donor� or �recipient�
	private $address;       // street address � string
	private $city;			// city
	private $state;			// 2-letter abbrev - usually �SC�
	private $zip; 	      	// zip code � integer
	private $geocoordinates; // array pair: [latitude, longitude] for navigation
	private $phone1;		// primary phone
	private $phone2;		// secondary phone
	private $days;			// array of days for pick-up or delivery
							// e.g. [�Monday�, �Wednesday�]
	private $feed_america;	// �yes� or �no�
	private $notes; 		// notes written by the team captain or coordinator
	
	//copied from volunteer class. question about use of "explode" with arrays
	
	function __construct($id, $name, $chain_name, $area, $type, $address, $city, $state, $zip, $geocoordinates,
	                        $phone1, $phone2, $days, $feed_america, $notes){                
        $this->id       	= $id;      
        $this->name 		= $name;      
        $this->chain_name 	= $chain_name;      
        $this->area 		= $area;      
        $this->type 		= $type;      
        $this->address 		= $address;      
        $this->city 		= $city;      
        $this->state 		= $state;      
        $this->zip 			= $zip;

        
        $this->geocoordinates = $geocoordinates;
        $this->phone1 		= $phone1;
        $this->phone2 		= $phone2;
        
        $this->days 		= $days;
        $this->feed_america	= $feed_america;
        $this->notes 		= $notes;
        
        
    }
    //getter functions
    function get_id() {
        return $this->id;
    }
    function get_name() {
        return $this->name;
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
    function get_notes(){
        return $this->notes;
    }
    
    //setter functions ... can be added later as needed
        
}
?>