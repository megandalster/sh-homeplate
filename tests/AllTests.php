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
        
        echo ("All tests complete");
 	  }
 }
?>
