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
							// e.g. 'Food Lion #1698 Laurel Bay'
	private $chain_name;	// e.g., 'Food Lion' (usually blank)
	private $area;			// "HHI", "SUN", or "BFT"
	private $type;			// "donor" or "recipient"
	private $address;       // street address : string
	private $city;			// city
	private $state;			// 2-letter abbrev - usually 'SC'
	private $zip; 	      	// zip code : integer
	private $county; 		// county
	private $phone1;		// food contact phone
	private $address2;      // street address : string
	private $city2;			// city
	private $state2;			// 2-letter abbrev - usually 'SC'
	private $zip2; 	      	// zip code : integer
	private $county2; 		// county
	private $phone2;		// administrative contact phone
	private $daysHHI;		// array of [days] for pick-up or delivery in HHI
							// e.g. ['Mon', 'Wed']
    private $daysSUN;		// array of [days] for pick-up or delivery in SUN
	private $daysBFT;		// array of [days] for pick-up or delivery in BFT
	private $lcfb;			// 'yes' or 'no'
	private $chartrkr;		// 'yes' or 'no'
	private $weight_type;	// variable for how items are recorded: 
							// ("pounds", "foodtype" or "foodtypeboxes")
	private $notes; 		// notes written by the team captain or coordinator
	
	private $email;			// email for food contact
	private $email2;		// email for administrative contact
	private $ContactName;	// food contact
	private $ContactName2;  // administrative contact
	private $deliveryAreaId;// for recipients
	private $survey_date;	//		last date surveyed
	private $visit_date;	//		last date visited
	private $foodsafe_date;	//		last date food safety inspection
	private $pestctrl_date; //		last date pest control inspection
	private $number_served;	//		number of people served 
	// 
	
	function __construct($id, $chain_name, $area, $type, $address, $city, $state, $zip, $county, $phone1, 
                            $address2, $city2, $state2, $zip2, $county2, $phone2, 
                            $daysHHI, $daysSUN, $daysBFT, $lcfb, $chartrkr, $weight_type, $notes, $email, $email2, $ContactName, $ContactName2, $deliveryAreaId, 
                            $survey_date, $visit_date, $foodsafe_date, $pestctrl_date, $number_served){                
        $this->id       	= $id;      
        $this->chain_name 	= $chain_name;      
        $this->area 		= $area;      
        $this->type 		= $type;      
        $this->address 		= $address;      
        $this->city 		= $city;      
        $this->state 		= $state;      
        $this->zip 			= $zip;
        $this->county 		= $county;
		$this->phone1 		= $phone1;
        $this->address2 	= $address2;      
        $this->city2 		= $city2;      
        $this->state2 		= $state2;      
        $this->zip2 		= $zip2;
        $this->county2 		= $county2;
		$this->phone2 		= $phone2;
        $this->email		= $email;
        $this->email2		= $email2;
		$this->ContactName = $ContactName;
		$this->ContactName2 = $ContactName2;
		$this->deliveryAreaId = $deliveryAreaId;
		$this->survey_date	= $survey_date;
		$this->visit_date	= $visit_date;
		$this->foodsafe_date= $foodsafe_date;
		$this->pestctrl_date= $pestctrl_date;
		$this->number_served= $number_served;
		
		if( $this->type 	== "donor"){
			$this->weight_type = "foodtype";
		}
		else{
			 if ($weight_type=="")
        	$this->weight_type = "pounds";
        else
			$this->weight_type	= $weight_type;
		}
        if ($daysHHI == "")
		   $this->daysHHI = array();
		else
		   $this->daysHHI = explode(',',$daysHHI);
	    if ($daysSUN == "")
	        $this->daysSUN = array();
		else
		    $this->daysSUN = explode(',',$daysSUN);
		if ($daysBFT == "")
		    $this->daysBFT = array();
		else
		    $this->daysBFT = explode(',',$daysBFT);
        $this->lcfb	= $lcfb;
        $this->chartrkr	= $chartrkr;
        $this->notes = $notes;
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
    function get_county() {
        return $this->county;
    }
    function get_phone1() {
        return $this->phone1;
    }
	function get_address2() {
        return $this->address2;
    }
    function get_city2() {
        return $this->city2;
    }
    function get_state2() {
        return $this->state2;
    }
    function get_zip2() {
        return $this->zip2;
    }
    function get_county2() {
        return $this->county2;
    }
    function get_phone2() {
        return $this->phone2;
    }
    function get_days($area){
        switch ($area) {
            case "HHI":
                return $this->daysHHI;
                break;
            case "SUN":
                return $this->daysSUN;
                break;
            case "BFT":
                return $this->daysBFT;
                break;
            default:
                return "";
        }
    }
    function get_lcfb(){
        return $this->lcfb;
    }
    function get_chartrkr(){
    	return $this->chartrkr;
    }
    function get_weight_type(){
		if($this->get_type() == "donor"){
			return "foodtype";
		}
		else{
			return "pounds";
		}
    }
    function get_notes(){
        return $this->notes;
    }
	
	function get_email(){
		return $this->email;
	}
	function get_email2(){
		return $this->email2;
	}
	function get_ContactName(){
		return $this->ContactName;
	}

	function get_ContactName2(){
		return $this->ContactName2;
	}
	
	function get_deliveryAreaId(){
		return $this->deliveryAreaId;
	}
	function get_survey_date(){
		return $this->survey_date;
	}
	function get_visit_date(){
		return $this->visit_date;
	}
	function get_foodsafe_date(){
		return $this->foodsafe_date;
	}
	function get_pestctrl_date(){
		return $this->pestctrl_date;
	}
	function get_number_served(){
		return $this->number_served;
	}
        
}
?>