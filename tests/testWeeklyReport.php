<?php
include_once(dirname(__FILE__).'/../domain/WeeklyReport.php');
class testWeeklyReport extends UnitTestCase {
    function testWeeklyReportModule() {    
    	
    	//fake route to test
    	$weekly = new WeeklyReport("11-12-28-HHI","");
    	
    	//testing getter functions
    	$this->assertTrue($weekly->get_id() == "11-12-28-HHI");
    	
        echo ("testRoute complete\n");
    }
}

?>
