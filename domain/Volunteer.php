<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

include_once(dirname(__FILE__).'/../domain/Pin.php');

/*
 * Volunteer class for Homeplate
 * @author Allen Tucker
 * @version February 4, 2012
 */


class Volunteer {
	private $id; 	// id (unique key) = first_name . phone1
	private $last_name; 	// last name as a string
	private $first_name; 	// first name as a string
	private $address; 		// local address - string
	private $city; 	     	// city - string
	private $state; 		// state - string
	private $zip; 			// zip code - integer
	private $phone1; 		// primary phone (may be a cell)
	private $phone2; 		// alternate phone (may be a cell)
    private $email; 		// email address as a string
	private $type;   		// array of "driver", "helper", �teamcaptain�, �coordinator�, "associate", "boardmember"
    private $status;   		// "applicant", "active", "on-leave", or "former"
    private $area;			// "HHI", "SUN", or "BFT"
	private $license_no;  		// drivers license no.
	private $license_state;	  	// state of issue
	private $license_expdate; 	// expiration date yy-mm-dd
	private $accidents;     // array of accidents (past 3 years)
							// each entry shows "date:nature:fatalities:injuries"
	private $availability; 	// array of day:week pairs; e.g. �Mon:1�, �Thu:4�, �NY�
	private $schedule;     	// array of scheduled shifts; e.g.,  [�Mon:1�, �Wed:2�]
	private $history;      	// array of recent routes worked; e.g., [�11-03-12�]
	private $birthday;     	// format: yy-mm-dd
	private $start_date;   	// format: yy-mm-dd (for applicants, date submitted)
	private $notes;			// misc notes about this volunteer
	private $password;     	// password for system access
	private $tripCount; 	// Volunteer trip count
	private $lastTripDates;	// array of up to 5 most recent trip dates volunteer was onboard the truck
	private $volunteerTrainingDate;	//date when trained as a volunteer
	private $driverTrainingDate;    // date when trained as a driver
	private $shirtSize;		//Shirt Size: S, M, L, XL, 2XL
	private $affiliateId;	//associated affiliate
    private $volunteerFormsDate;	//date when completed volunteer forms
    
    private $pins = [];
    
        /**
         * constructor for a Volunteer
         */
    function __construct($last_name, $first_name, $address, $city, $state, $zip, $phone1, $phone2, $email, $type,
                         $status, $area, $license_no, $license_state, $license_expdate, $accidents, $availability, 
                         $schedule, $history, $birthday, $start_date, $notes, $password, $tripCount, $lastTripDates, 
    					 $volunteerTrainingDate, $driverTrainingDate, $shirtSize, $affiliateId, $volunteerFormsDate){
        $this->id = $first_name . $phone1; 
        $this->last_name = $last_name;
        $this->first_name = $first_name;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->phone1 = $phone1;
        $this->phone2 = $phone2;
        $this->email = $email;
		$this->tripCount = $tripCount;
		
		if ($lastTripDates == "")
		    $this->lastTripDates = array();
		else 
		    $this->lastTripDates = explode(',',$lastTripDates);
		$this->volunteerTrainingDate = $volunteerTrainingDate;
		$this->driverTrainingDate = $driverTrainingDate;
		$this->shirtSize = $shirtSize;
		$this->affiliateId = $affiliateId;
		
        if ($type == "") 
        	$this->type = array();
        else 
        	$this->type = explode(',',$type);
        $this->status = $status;
        $this->area = $area;
        
        $this->license_no = $license_no;
        $this->license_state = $license_state;
        $this->license_expdate = $license_expdate;
        if ($accidents == "") 
        	$this->accidents = array();
        else 
        	$this->accidents = explode(',',$accidents);                
        if ($availability == "") 
        	$this->availability = array();
        else 
        	$this->availability = explode(',',$availability);
        if ($schedule == "") 
        	$this->schedule = array();
        else 
        	$this->schedule = explode(',',$schedule);
        if ($history == "") 
        	$this->history = array();
        else 
        	$this->history = explode(',',$history);
        $this->birthday = $birthday;
        $this->start_date = $start_date;
        $this->notes = $notes;   
        if ($password=="")
            $this->password = md5($this->id);
        else $this->password = $password;
        $this->volunteerFormsDate = $volunteerFormsDate;
    }
    //getter functions
    function get_id() {
        return $this->id;
    }
    function get_first_name() {
        return $this->first_name;
    }
    function get_last_name() {
        return $this->last_name;
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
    function get_phone1() {
        return $this->phone1;
    }
    function get_phone2() {
        return $this->phone2;
    }
    function get_email(){
        return $this->email;
    }
    function get_type(){
        return $this->type;
    }
    function get_status() {
        return $this->status;
    }
    function get_area() {
        return $this->area;
    }
    function get_license_no() {
        return $this->license_no;
    }
    function get_license_state() {
        return $this->license_state;
    }
    function get_license_expdate() {
        return $this->license_expdate;
    }
    function get_accidents() {
        return $this->accidents;
    }
    function get_availability() {
        return $this->availability;
    }
    function get_schedule() {
        return $this->schedule;
    }
    function get_history() {
        return $this->history;
    }
    function get_birthday(){
        return $this->birthday;
    }
    function get_start_date(){
    	return $this->start_date;
    }
	function get_notes(){
    	return $this->notes;
    }
	function get_password () {
        return $this->password;
    }
	
	function get_tripCount(){
		return $this->tripCount;
	}
	
	function get_lastTripDates(){
		return $this->lastTripDates;
	}
	
	function get_volunteerTrainingDate(){
		return $this->volunteerTrainingDate;
	}
	function get_driverTrainingDate(){
		return $this->driverTrainingDate;
	}
	function get_shirtSize(){
		return $this->shirtSize;
	}
	
	function get_affiliateId(){
		return $this->affiliateId;
	}
	
	function nice_phone ($phone) {
	    if (strlen($phone)==10)
	        return substr($phone,0,3)."-".substr($phone,3,3)."-".substr($phone,6);
	    else if (strlen($phone)==7)
	             return substr($phone,0,3)."-".substr($phone,3);
	         else return $phone;
    }
	//returns true if the person has type $t
    function is_type($t){
        if (in_array($t, $this->type))
            return true;
        else
            return false;
    }
	//returns true if the person is available on a particular day and week of the month
    function is_available($day, $week){
        if (in_array($day.":".$week, $this->availability))
            return true;
        else
            return false;
    }
    //setter functions ... can be added later as needed
    function set_license_expdate ($exp_date) {
        $this->license_expdate = $exp_date;
    }
    function set_birthday ($birthday) {
        $this->birthday = $birthday;
    }
    function set_password ($new_password) {
        $this->password = $new_password;
    }
 
	function insert_lastTripDates($newDate){ // insert -- avoid duplicate entties
	    sort($this->lastTripDates);
	    if (in_array($newDate,$this->lastTripDates))
	        return false; // avoid double-counting the same date
	    $s = sizeof($this->lastTripDates);
	    if ($s >= 5) {
	        array_splice($this->lastTripDates,0,$s-4); // remove the earliest dates
	    }
		$this->lastTripDates[] = $newDate; 
		$this->tripCount ++;
		return true;
	}
	
	function remove_lastTripDates($date) { // remove a date from last trip dates and decrement tripCount
	    sort($this->lastTripDates);
	    $i = array_search($date,$this->lastTripDates);
	    if ($i===false )
	        return false; // not there
	    array_splice($this->lastTripDates,$i,1);  // remove it
	    $this->tripCount --;
	    return true;
	}
	
	function set_volunteerTrainingDate($newDate){
		$this->volunteerTrainingDate = $newDate;
	}
	function set_driverTrainingDate($newDate){
		$this->driverTrainingDate = $newDate;
	}
	function set_tripCount($count){
		$this->tripCount = $count;
	}
    
    function get_volunteerFormsDate(){
        return $this->volunteerFormsDate;
    }
    function set_volunteerFormsDate($newDate){
        $this->volunteerFormsDate = $newDate;
    }
    
    function get_pins() {
        return $this->pins;
    }
    function set_pins($pins) {
        $this->pins = $pins;
    }
}
?>
