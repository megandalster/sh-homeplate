<?php
/*
 * Created Febuary 27, 2008
 * @author Richardo
 */
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Route.php'); 
include_once(dirname(__FILE__).'/../database/dbRoutes.php');
class dbRoutesTest extends TestCase {
      function testdbRoutes() {
			
			// Setup  	
			$r1 = new Route("11-12-28-HHI", "malcom1234567890,sandi8437891234", "Jon1112345678",
    			"Food Lion - Palmetto Bay,Piggly Wiggly","","", "Note.");
			$this->assertTrue(insert_dbRoutes($r1));

			$r2 = new Route("Route 2", "Driver, Backseat Driver", "Captain America","","","", "Note - boxes are heavy.");
			$this->assertTrue(insert_dbRoutes($r2));
			// should return false for a duplicate entry
			$this->assertFalse(insert_dbRoutes($r2));

			// Test -- test retrieve and update functions
			$r = get_route("11-12-28-HHI");
			$this->assertTrue($r != null);
			$this->assertFalse(get_route("Route 66"));

			// Teardown
			$this->assertTrue(delete_dbRoutes($r1));
			$this->assertTrue(delete_dbRoutes($r2));
      }
}


?>
