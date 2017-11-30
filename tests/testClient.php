<?php
include_once(dirname(__FILE__).'/../domain/Client.php');
class testClient extends UnitTestCase {
    function testClientModule() {  

    	//fake client to test
        $client = new Client("Whole Foods Brunswick", "Whole Foods USA", "Bowdoin", "restaurant", 
        					"123 Maine St", "Brunswick", "ME", "04011", "",
	                        "2077253500", "", "Mon,Wed", True, "pounds", "This is a test case");
        
         //testing getter functions
        
        $this->assertTrue($client->get_id() == "Whole Foods Brunswick");
        $this->assertTrue($client->get_chain_name() == "Whole Foods USA");
        $this->assertTrue($client->get_address() == "123 Maine St");
        $this->assertTrue($client->get_city() == "Brunswick");
        $this->assertTrue($client->get_state() == "ME");
        $this->assertTrue($client->get_zip() == "04011");
        $this->assertTrue($client->get_phone1() == "2077253500");
        $this->assertTrue($client->get_phone2() == "");
        $this->assertTrue(sizeof($client->get_days()) == 2);
        $this->assertTrue($client->get_weight_type() == "pounds");
        $this->assertTrue($client->get_notes() == "This is a test case");
        
        //$this->assertTrue(1 == 1);
                 
        //tests the 'is_type' function
        $this->assertTrue($client->is_lcfb() == True);
      
                 
        echo ("testClient complete\n");
    				
    }
}

?>
