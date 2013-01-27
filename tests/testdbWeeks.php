<?php
/*
 * Created March 6th, 2008
 * @author Richardo
 */
include_once(dirname(__FILE__).'/../domain/Week.php'); 
include_once(dirname(__FILE__).'/../database/dbWeeks.php');
class testdbWeeks extends UnitTestCase {
      function testdbWeeksModule() {
      	
 			//Test table creation
			// $this->assertTrue(create_dbWeeks()); 	  	
			$w1 = new Week("1st week of January", "Route 1, Route 2, Route 66");
			$this->assertTrue(add_week($w1));

			$w2 = new Week("Spring Break", "Route 4, Route 7");
			$this->assertTrue(add_week($w2));
			// should return false for a duplicate entry
			$this->assertFalse(add_week($w2));

			//get a week
			$w = get_week("Spring Break");
			$this->assertTrue($w != null);

			//try to get a week that is not in the db
			$this->assertFalse(get_week("2nd week of April"));

			//remove all weeks
			$this->assertTrue(remove_week("1st week of January"));
			$this->assertTrue(remove_week("Spring Break"));

			//try to remove a week that is not in the db - should not work
			$this->assertFalse(remove_week("53rd week of the year"));

			echo("testdbWeeks complete");

      }
}


?>
