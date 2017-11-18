<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * DeliveryArea class for Homeplate
 * @author Allen Tucker
 * @version February 4, 2012
 */

class DeliveryArea {
	private $deliveryAreaId; 	// id (unique key)
	private $deliveryAreaName; 	//  name as a string
	
        /**
         * constructor for a Affiliate
         */
    function __construct($deliveryAreaId, $deliveryAreaName){                
        
		$this->deliveryAreaId = $deliveryAreaId;
		$this->deliveryAreaName = $deliveryAreaName;       
    }
	
    //getter functions
    function get_deliveryAreaName() {
        return $this->deliveryAreaName;
    }
   
	function get_deliveryAreaId(){
		return $this->deliveryAreaId;
	}
	
	//returns true if the person has type $t
    function is_type($t){
        if (in_array($t, $this->type))
            return true;
        else
            return false;
    }
	
        
}
?>
