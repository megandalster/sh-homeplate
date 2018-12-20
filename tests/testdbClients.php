<?php
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Client.php');
include_once(dirname(__FILE__).'/../database/dbClients.php'); 
class dbClientsTest extends TestCase {
	function testdbClients() {
		
		//Setup -- test create
	    $client1 = new Client("Whole Foods Brunswick", "Whole Foods USA", "HHI", "donor",
	        "123 Maine St", "Brunswick", "ME", "04011", "", "2077253500",
	        "","","","","","","Mon,Wed","","", "yes","no", "foodtype", "This is a test case",
	        "whole@foods.com","","John","","","","","","","");
        $client2 = new Client("Hannafords Brunswick", "Hannafords", "BFT", "donor", 
            "456 Maine St", "Brunswick", "ME", "04011",  "","2077253600", 
            "","","","","","","Tue,Thu","","", "yes","no", "foodtype", "This is a test case #2",
            "hanna@fords.com","","Mary","","","","","","","");
		$this->assertTrue(insert_dbClients($client1));
		$this->assertTrue(insert_dbClients($client2));
		
        //Test -- test retrieve and update
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_id(), "Whole Foods Brunswick");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_chain_name(), "Whole Foods USA");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_area(), "HHI");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_type(), "donor");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_address(), "123 Maine St");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_city(), "Brunswick");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_state(), "ME");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_zip(), "04011");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_phone1(), "2077253500");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_phone2(), null);
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_days("HHI"), array("Mon","Wed"));
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_lcfb(), "yes");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_weight_type(), "foodtype");
		$this->assertEquals(retrieve_dbClients($client1->get_id())->get_notes(), "This is a test case");
		
		$client2 = new Client("Hannafords Brunswick", "Hannafords Family Store", "BFT", "donor",
		    "456 Maine St", "Brunswick", "ME", "04011",  "","2077253600",
		    "","","","","","","Tue,Thu", "","","yes","no", "foodtype", "This is a test case #2",
		    "hanna@fords.com","","Mary","","","","","","","");
		$this->assertTrue(update_dbClients($client2));
		$this->assertEquals(retrieve_dbClients($client2->get_id())->get_chain_name(), "Hannafords Family Store");
		
		// Teardown -- test delete
		$this->assertTrue(delete_dbClients($client1->get_id()));
		$this->assertTrue(delete_dbClients($client2->get_id()));
		$this->assertFalse(retrieve_dbClients($client1->get_id()));
	}
}
?>