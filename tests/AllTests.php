<?php
/*
 * Run all the RMH Homeroom unit tests
 */
// require_once(dirname(__FILE__).'/simpletest/autorun.php');
class AllTests extends GroupTest {
 	  function AllTests() {
        $this->addTestFile(dirname(__FILE__).'/testVolunteer.php');
        $this->addTestFile(dirname(__FILE__).'/testClient.php');
        $this->addTestFile(dirname(__FILE__).'/testRoute.php');
        $this->addTestFile(dirname(__FILE__).'/testStop.php');
        $this->addTestFile(dirname(__FILE__).'/testWeeklyReport.php');
        $this->addTestFile(dirname(__FILE__).'/testScheduleEntry.php');
        $this->addTestFile(dirname(__FILE__).'/testMonth.php');
        echo ("All tests complete in AllTests.php");
 	  }
 }
?>
