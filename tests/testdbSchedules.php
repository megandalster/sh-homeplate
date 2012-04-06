<?php
include_once(dirname(__FILE__).'/../domain/ScheduleEntry.php');
include_once(dirname(__FILE__).'/../database/dbSchedules.php'); 
class testdbSchedules extends UnitTestCase {
	function testdbSchedulesModule() {
		
		//Test ScheduleEntries
		$drivers = array("Richardo1112345678", "Jon1112345678");
		$scheduleentry1 = new ScheduleEntry("HHI", "Mon:1", $drivers, "this is a test case schedule entry, created automatically. you shouldn't see it.");
        $scheduleentry2 = new ScheduleEntry("SUN", "Mon:2", $drivers, "this is a test case schedule entry, created automatically. you shouldn't see it.");


		//Test inserts
		$this->assertTrue(insert_dbSchedules($scheduleentry1));
		$this->assertTrue(insert_dbSchedules($scheduleentry2));
		
        //Test Retrieve
		$this->assertEqual(retrieve_dbSchedules($scheduleentry1->get_id())->get_id(), "Mon:1");
		$this->assertEqual(retrieve_dbSchedules($scheduleentry1->get_id())->get_area(), "HHI");
		$this->assertEqual(retrieve_dbSchedules($scheduleentry1->get_id())->get_days(), array("malcom1234567890","sandi8437891234"));
		$this->assertEqual(retrieve_dbSchedules($scheduleentry1->get_id())->get_notes(), "this is a test case schedule entry, created automatically. you shouldn't see it.");
		
		//Test Update with a change of area
		$scheduleentry2 = new ScheduleEntry("HHI", "Mon:2", $drivers, "this is a test case schedule entry, created automatically. you shouldn't see it.");
		$this->assertTrue(update_dbSchedules($scheduleentry2));
		$this->assertEqual(retrieve_dbSchedules($scheduleentry2->get_id())->get_area(), "HHI");
		
		//Test Delete
		$this->assertTrue(delete_dbSchedules($scheduleentry1->get_id()));
		$this->assertTrue(delete_dbSchedules($scheduleentry2->get_id()));
		$this->assertFalse(retrieve_dbSchedules($scheduleentry1->get_id()));
		
		echo ("testdbSchedules complete \n");
	}
}
?>