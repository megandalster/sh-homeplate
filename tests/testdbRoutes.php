<?php
/*
 * Created Febuary 27, 2008
 * @author Richardo
 */
include_once(dirname(__FILE__).'/../domain/Route.php'); 
include_once(dirname(__FILE__).'/../database/dbRoutes.php');
class testdbRoutes extends UnitTestCase {
      function testdbRoutesModule() {
 			//Test table creation
			// $this->assertTrue(create_dbRoutes()); 	  	
			$r1 = new Route("11-12-28-HHI", "malcom1234567890,sandi8437891234", "Jon1112345678",
    			"Food Lion - Palmetto Bay,Piggly Wiggly","","", "Note.");
			$this->assertTrue(add_route($r1));

			$r2 = new Route("Route 2", "Driver, Backseat Driver", "Captain America","","","", "Note - boxes are heavy.");
			$this->assertTrue(add_route($r2));
			// should return false for a duplicate entry
			$this->assertFalse(add_route($r2));

			//get a route
			$r = get_route("11-12-28-HHI");
			$this->assertTrue($r != null);

			//try to get a route that is not in the db
			$this->assertFalse(get_route("Route 66"));

			//remove all routes
			$this->assertTrue(remove_route("11-12-28-HHI"));
			$this->assertTrue(remove_route("Route 2"));

			//try to remove a route that is not in the db - should not work
			$this->assertFalse(remove_route("Route 66"));

			echo("testdbRoutes complete");

      }
}


?>
