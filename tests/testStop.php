<?php

/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * testStop module for SH Homeplate
 * @author Nicholas Wetzel
 * @version May 8, 2012
 */

/*
 * Provides the test module for the Stop class.
 */

include_once(dirname(__FILE__).'/../domain/Stop.php');
class testStop extends UnitTestCase {
    function testStopModule() {     

    	//create a test stop/client
    	$stop = new Stop("11-12-29-HHI","Food Lion - Palmetto Bay", "donor","", "");
    	$stop2 = new Stop("11-12-29-HHI","Piggly Wiggly","donor", "meat:100","");

    	//testing getter functions
    	$this->assertTrue($stop->get_id() == "11-12-29-HHIFood Lion - Palmetto Bay");
    	$this->assertTrue($stop->get_items() == array());
    	$this->assertTrue($stop->get_total_weight() == 0);
    	$this->assertTrue($stop->get_notes() == "");
    	$this->assertEqual($stop2->get_total_weight(), 100);
    	$stop2->add_item("bakery:50");
    	$this->assertEqual($stop2->get_total_weight(), 150);
  		$stop2->remove_item("meat:100");
   		$this->assertEqual($stop2->get_total_weight(), 50);

    	//echoing
		echo ("testStop complete\n");
    }
}

?>
