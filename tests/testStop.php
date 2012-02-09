<?php
include_once(dirname(__FILE__).'/../domain/Stop.php');
class testStop extends UnitTestCase {
    function testStopModule() {     

    	//create a test stop/client
    	$stop = new Stop("North Route . Food Lion", 
    					"Bread : 50 lbs.", "Only Bread");
    	
    	//testing getter functions
    	$this->assertTrue($stop->get_id() == "North Route . Food Lion");
    	$this->assertTrue($stop->get_items() == "Bread : 50 lbs.");
    	$this->assertTrue($stop->get_notes() == "Only Bread");
    	
		echo ("testStop complete\n");
    }
}

?>
