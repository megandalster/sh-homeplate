<?php
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Client.php');
class ClientTest extends TestCase {
    function testClient() {  

        $client = new Client("Whole Foods Brunswick", "Whole Foods USA", "HHI", "donor", 
            "123 Maine St", "Brunswick", "ME", "04011", "", "2077253500", 
	        "","","","","","","Mon,Wed","","", "yes","no", "pounds", "This is a test case",
            "whole@foods.com","","John","","","","","","","");      
        $this->assertTrue($client->get_id() == "Whole Foods Brunswick");
        $this->assertTrue($client->get_chain_name() == "Whole Foods USA");
        $this->assertTrue($client->get_address() == "123 Maine St");
        $this->assertTrue($client->get_city() == "Brunswick");
        $this->assertTrue($client->get_state() == "ME");
        $this->assertTrue($client->get_zip() == "04011");
        $this->assertTrue($client->get_phone1() == "2077253500");
        $this->assertTrue($client->get_phone2() == "");
        $this->assertTrue(sizeof($client->get_days("HHI")) == 2);
        $this->assertTrue($client->get_weight_type() == "foodtype");
        $this->assertTrue($client->get_notes() == "This is a test case");
        $this->assertTrue($client->get_lcfb() == "yes");
    				
    }
}

?>
