<?php
include_once(dirname(__FILE__).'/../domain/Week.php');
class testWeek extends UnitTestCase {
    function testWeekModule() {    
    	
    	//fake route to test
    	$week = new Week("11-12-28-HHI","");
    	
    	//testing getter functions
    	$this->assertTrue($week->get_id() == "11-12-28-HHI");
    	
        echo ("testWeek complete\n");
    }
}

?>
