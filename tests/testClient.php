<?php
include_once(dirname(__FILE__).'/../domain/Client.php');
class testClient extends UnitTestCase {
    function testClientModule() {  

    	//fake client to test
        $client = new Client("some-id-01", "Whole Foods", "Whole Foods USA", "Bowdoin", "restaurant", 
        					"123 Maine St", "Brunswick", "ME", "04011", "000-000-000",
	                        "(207)725-3500", "", "", True, "This is a test case");
        
        echo("got here 1");
        
        //testing getter functions
        
        $this->assertTrue($client->get_name() == "Whole Foods");
        $this->assertTrue($client->get_chain_name() == "Whole Foods USA");
        $this->assertTrue($client->get_address() == "123 Maine St");
        $this->assertTrue($client->get_city() == "Brunswick");
        $this->assertTrue($client->get_state() == "ME");
        $this->assertTrue($client->get_zip() == "04011");
        $this->assertTrue($client->get_phone1() == "(207)725-3500");
        $this->assertTrue($client->get_phone2() == "");
        $this->assertTrue($client->get_notes() == "This is a test case");
        
        //$this->assertTrue(1 == 1);
                 
        //tests the 'is_type' function
        $this->assertTrue($client->is_feed_america() == True);
      
                 
        echo ("testVolunteer complete\n");
    				
    }
}

?>
