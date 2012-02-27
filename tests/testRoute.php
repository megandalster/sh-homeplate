<?php
include_once(dirname(__FILE__).'/../domain/Route.php');
class testRoute extends UnitTestCase {
    function testRouteModule() {    
    	//fake route to test
    	$route = new Route("11-12-28-HHI", "malcom1234567890,sandi8437891234", "Jon1112345678",
    	"Food Lion - Palmetto Bay,Piggly Wiggly", "Note.");
    	
    	//testing getter functions
    	$this->assertTrue($route->get_id() == "11-12-28-HHI");
    	$this->assertTrue($route->get_teamcaptain_id() == "Jon1112345678");
    	$this->assertEqual($route->get_day(), "Wednesday December 28, 2011");
	   	$this->assertEqual($route->get_area(), "Hilton Head Island");
    	$this->assertTrue($route->get_notes() == "Note.");
   	
        echo ("testRoute complete\n");
    }
}

?>
