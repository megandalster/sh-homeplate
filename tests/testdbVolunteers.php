<?php
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Volunteer.php');
include_once(dirname(__FILE__).'/../database/dbVolunteers.php'); 
class dbVolunteersTest extends TestCase {
	function testdbVolunteers() {
		// Setup
		$vol1 = new Volunteer("Jones", "Hartley", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"Hartley.Jones@gmail.com", "driver", "active", "HHI", "123456789","SC", "14-01-29", "", "Wed:3,Fri:4","",
		    "", "59-01-01","98-01-01", "", "","","","","","","");
        $vol2 = new Volunteer("Jones", "Richardo", "2 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"rjones342@gmail.com", "driver", "active", "HHI", "234567890","SC", "14-01-29", "", "Wed:1,Fri:5","",
            "", "59-01-01","98-01-01", "", "","","","","","","");
        $vol3 = new Volunteer("Jones", "Nick", "3 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"njones41@gmail.com", "driver", "active", "HHI", "345678901","SC", "14-01-29", "", "Wed:2,Fri:1","",
            "", "59-01-01","98-01-01", "", "","","","","","","");
        
		$this->assertTrue(insert_dbVolunteers($vol1));
		$this->assertTrue(insert_dbVolunteers($vol2));
		$this->assertTrue(insert_dbVolunteers($vol3));
		
		// Test retrieve and update
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_id (), "Hartley1112345678");
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_first_name (), "Hartley");
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_last_name (), "Jones");
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_address(), "1 Scarborough Head Rd");
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_city (), "Hilton Head");
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_state (), "SC");
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_zip(), "29928");
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_phone1 (), 1112345678);
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_phone2 (), null);
		$this->assertEquals(retrieve_dbVolunteers($vol1->get_id())->get_email(), "Hartley.Jones@gmail.com");
		
		//Test Update with a change of address
		$vol2 = new Volunteer("Jones", "Richardo", "444 Park","Hilton Head", "SC", "29928", "1112345678", "", 
    				"rjones342@gmail.com", "driver", "active", "HHI", "234567890","SC", "14-01-29", "", "Wed:1,Fri:5","",
		    "", "59-01-01","98-01-01", "", "","","","","","","");
		$this->assertTrue(update_dbVolunteers($vol2));
		$this->assertEquals(retrieve_dbVolunteers($vol2->get_id())->get_address(), "444 Park");
		
		// Teardown
		$this->assertTrue(delete_dbVolunteers($vol1->get_id()));
		$this->assertTrue(delete_dbVolunteers($vol2->get_id()));
		$this->assertTrue(delete_dbVolunteers($vol3->get_id()));
		$this->assertFalse(retrieve_dbVolunteers($vol2->get_id()));
	}
}
?>