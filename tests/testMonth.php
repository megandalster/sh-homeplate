<?php
include_once(dirname(__FILE__).'/../domain/Month.php');
class testMonth extends UnitTestCase {
    function testMonthModule() {     

    	//create a test Month
    	$month = new Month("12-02", "published", "",
    						"");
    	
    	//testing getter functions
    	$this->assertTrue($month->get_id() == "12-02");    // February 2012
    	$this->assertTrue($month->get_status() == "published");
    	$this->assertEqual($month->get_first_day(), 3); // February 1, 2012 is a Wednesday
    	$this->assertTrue($month->get_routes() == array());
    	$this->assertTrue($month->get_notes() == "");
    	
    	//echoing
		echo ("testMonth complete\n");
    }
}