<?php
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/ScheduleEntry.php');
class ScheduleEntryTest extends TestCase {
    function testScheduleEntry() {  

        $scheduleEntry = new ScheduleEntry("HHI", "Mon:1", "", "This is a test Schedule Entry");
        $this->assertTrue($scheduleEntry->get_area() == "HHI");
        $this->assertTrue($scheduleEntry->get_id() == "Mon:1");
        $this->assertTrue($scheduleEntry->get_drivers() == array());
        $this->assertTrue($scheduleEntry->get_notes() == "This is a test Schedule Entry");
    }
}

?>
