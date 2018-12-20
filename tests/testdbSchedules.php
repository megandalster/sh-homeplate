<?php
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/ScheduleEntry.php');
include_once(dirname(__FILE__).'/../database/dbSchedules.php'); 
class dbSchedulesTest extends TestCase {
	function testdbSchedules() {
		
		// Setup
		$drivers = "Richardo1112345678,Jon1112345678";
		$scheduleentry1 = new ScheduleEntry("HHI", "Mon:1", $drivers, "this is a test case.");
        $scheduleentry2 = new ScheduleEntry("BFT", "Mon:2", $drivers, "this is a test case.");
		$this->assertTrue(insert_dbSchedules($scheduleentry1));
		$this->assertTrue(insert_dbSchedules($scheduleentry2));
		
        // Test update and retrieve
		$this->assertEquals(retrieve_dbSchedules("HHI",$scheduleentry1->get_id())->get_id(), "Mon:1");
		$this->assertEquals(retrieve_dbSchedules("HHI",$scheduleentry1->get_id())->get_area(), "HHI");
		$this->assertEquals(retrieve_dbSchedules("HHI",$scheduleentry1->get_id())->get_day(), "Mon");
		$this->assertEquals(retrieve_dbSchedules("HHI",$scheduleentry1->get_id())->get_notes(), "this is a test case.");
		$scheduleentry2 = new ScheduleEntry("BFT", "Mon:2", $drivers, "this is a test case.");
		$this->assertTrue(update_dbSchedules($scheduleentry2));
		$this->assertEquals(retrieve_dbSchedules("BFT",$scheduleentry2->get_id())->get_area(), "BFT");
		
		// Teardown
		$this->assertTrue(delete_dbSchedules("HHI",$scheduleentry1->get_id()));
		$this->assertTrue(delete_dbSchedules("BFT",$scheduleentry2->get_id()));
		$this->assertFalse(retrieve_dbSchedules("HHI",$scheduleentry1->get_id()));
	}
}
?>