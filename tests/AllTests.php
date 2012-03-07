<?php
/*
 * Run all the RMH Homeroom unit tests
 */
// require_once(dirname(__FILE__).'/simpletest/autorun.php');
class AllTests extends GroupTest {
 	  function AllTests() {
        $this->addTestFile(dirname(__FILE__).'/testClient.php');
        $this->addTestFile(dirname(__FILE__).'/testMonth.php');	
        $this->addTestFile(dirname(__FILE__).'/testRoute.php');
        $this->addTestFile(dirname(__FILE__).'/testScheduleEntry.php');
        $this->addTestFile(dirname(__FILE__).'/testStop.php');
        $this->addTestFile(dirname(__FILE__).'/testVolunteer.php');
        $this->addTestFile(dirname(__FILE__).'/testWeek.php');
        
        $this->addTestFile(dirname(__FILE__).'/testdbClients.php');
        $this->addTestFile(dirname(__FILE__).'/testdbMonths.php');
        $this->addTestFile(dirname(__FILE__).'/testdbRoutes.php');
        $this->addTestFile(dirname(__FILE__).'/testdbStops.php');
        $this->addTestFile(dirname(__FILE__).'/testdbVolunteers.php');
        $this->addTestFile(dirname(__FILE__).'/testdbWeeks.php');
        
        echo ("All tests complete");
 	  }
 }
?>
