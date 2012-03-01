<?php
include_once(dirname(__FILE__).'/../domain/Client.php');
include_once(dirname(__FILE__).'/../database/dbClients.php'); 
class testdbVolunteers extends UnitTestCase {
	function testdbClientsModule() {
		//Test table creation
			$this->assertTrue(create_dbClients());
	
		//Test Clients
		$client1 = new Client("some-id-01", "Whole Foods Brun", "Whole Foods USA", "Bowdoin", "grocery store", "123 Maine St", "Brunswick", 
                                "ME", "04011", "000-000-000", "(207)725-3500", "", "Mon,Wed", True, "This is a test case");
        $client2 = new Client("some-id-02", "Hannafords Brunswick", "Hannafords", "Bowdoin", "grocery store", "456 Maine St", "Brunswick", 
                                "ME", "04011", "000-000-000", "(207)725-3600", "", "Tue,Thu", False, "This is a test case #2");
        $client3 = new Client("some-id-03", "Flipside Pizza", "", "Bowdoin", "restaurant", "789 Maine St", "Brunswick", 
                                "ME", "04011", "000-000-000", "(207)725-3700", "", "Wed,Fri", True, "This is a test case #3");

		//Test inserts
		$this->assertTrue(insert_dbClients($client1));
		$this->assertTrue(insert_dbClients($client2));
		$this->assertTrue(insert_dbClients($client3));
		
        //Test Retrieve
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_id(), "some-id-01");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_name(), "Whole Foods Brun");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_chain_name(), "Whole Foods USA");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_area(), "Bowdoin");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_type(), "grocery store");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_address(), "123 Maine St");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_city(), "Brunswick");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_state(), "ME");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_zip(), "04011");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_phone1(), "(207)725-3500");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_phone2(), null);
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_days(), "Mon,Wed");
		$this->assertEqual(retrieve_dbClients($client1->get_id())->is_feed_america(), True);
		$this->assertEqual(retrieve_dbClients($client1->get_id())->get_notes(), "This is a test case");
		
		//Test Update with a change of chain name & comment
		$client2 = new Client("some-id-02", "Hannafords Brunswick", "Hannaford's Family Stores", "Bowdoin", "grocery store", "456 Maine St", "Brunswick", 
                                "ME", "04011", "000-000-000", "(207)725-3600", "", "Tue,Thu", False, "This is a test case #2 updated");
		$this->assertTrue(update_dbClients($client2));
		$this->assertEqual(retrieve_dbClients($client2->get_id())->get_chain_name(), "Hannaford's Family Stores");
/*		
		//Test Delete
		$this->assertTrue(delete_dbClients($client1->get_id()));
		$this->assertTrue(delete_dbClients($client2->get_id()));
		$this->assertTrue(delete_dbClients($client3->get_id()));
		$this->assertFalse(retrieve_dbClients($client1->get_id()));
*/		
		echo ("testdbClients complete \n");
	}
}
?>