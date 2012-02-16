<?php
include_once(dirname(__FILE__).'/../domain/Stop.php');
class testStop extends UnitTestCase {
    function testStopModule() {     

    	//create a test stop/client
    	$stop = new Stop("North Route . Food Lion", 
    					"", "");
    	
    	//testing getter functions
    	$this->assertTrue($stop->get_id() == "North Route . Food Lion");
    	$this->assertTrue($stop->get_items() == array());
    	$this->assertTrue($stop->get_notes() == array());
    	
    	//echoing
		echo ("testStop complete\n");
    }
}

?>
