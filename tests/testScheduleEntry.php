<?php
include_once(dirname(__FILE__).'/../domain/ScheduleEntry.php');
class testScheduleEntry extends UnitTestCase {
    function testScheduleEntryModule() {  

    	echo ("starting testScheduleEntry \n");
    	
    	//fake Schedule Entry to test
        $scheduleEntry = new ScheduleEntry("HHI", "Mon:1", "", "This is a test Schedule Entry");
        
        //testing getter functions
        
        $this->assertTrue($scheduleEntry->get_area() == "HHI");
        $this->assertTrue($scheduleEntry->get_id() == "Mon:1");
        //$this->assertTrue($scheduleEntry->get_drivers() == "");
        $this->assertTrue($scheduleEntry->get_notes() == "This is a test Schedule Entry");
        
        $this->assertTrue(1 == 1);
                 
        echo ("testScheduleEntry complete\n");
    				
    }
}

?>
