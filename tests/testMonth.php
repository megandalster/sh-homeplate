<?php
include_once(dirname(__FILE__).'/../domain/Month.php');
class testMonth extends UnitTestCase {
    function testMonthModule() {     

    	//create a test Month
    	$month = new Month("12-02", "published", "1","",
    						"");
    	
    	//testing getter functions
    	$this->assertTrue($month->get_id() == "12-02");
    	$this->assertTrue($month->get_status() == "published");
    	$this->assertTrue($month->get_first_day() == "1");
    	$this->assertTrue($month->get_routes() == array());
    	$this->assertTrue($month->get_notes() == array());
    	
    	//echoing
		echo ("testMonth complete\n");
    }
}