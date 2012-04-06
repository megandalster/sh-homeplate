<?php
include_once(dirname(__FILE__).'/../domain/Volunteer.php');
include_once(dirname(__FILE__).'/../database/dbVolunteers.php'); 
class testdbVolunteers extends UnitTestCase {
	function testdbVolunteersModule() {
		//Test table creation
		//	$this->assertTrue(create_dbVolunteers());
	
		//Test volunteers
		$vol1 = new Volunteer("Brody", "Hartley", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"Hartley.Brody@gmail.com", "driver", "active", "HHI", "123456789","SC", "14-01-29", "", "", "Wed:3,Fri:4","",
    				"", "59-01-01","98-01-01", "", "");
        $vol2 = new Volunteer("Hopkins", "Richardo", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"milkywayw@gmail.com", "driver", "active", "HHI", "234567890","SC", "14-01-29", "", "", "Wed:1,Fri:5","",
    				"", "59-01-01","98-01-01", "", "");
        $vol3 = new Volunteer("Wetzel", "Nick", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"nwetzel41@gmail.com", "driver", "active", "HHI", "345678901","SC", "14-01-29", "", "", "Wed:2,Fri:1","",
    				"", "59-01-01","98-01-01", "", "");
        $vol4 = new Volunteer("Peluso", "Jon", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"jon25T@gmail.com", "driver,teamcaptain", "active", "HHI", "456789012","SC", "14-01-29", "", "", "Wed:4,Fri:2","",
    				"", "59-01-01","98-01-01", "", "");
        $vol5 = new Volunteer("Tucker", "Allen", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "1112345678", "", 
    				"allen@bowdoin.edu", "driver", "active", "HHI", "567890123","SC", "14-01-29", "", "", "Wed:5,Fri:3","",
    				"", "59-01-01","98-01-01", "", "");
		//Test inserts
		$this->assertTrue(insert_dbVolunteers($vol1));
		$this->assertTrue(insert_dbVolunteers($vol2));
		$this->assertTrue(insert_dbVolunteers($vol3));
		$this->assertTrue(insert_dbVolunteers($vol4));
		$this->assertTrue(insert_dbVolunteers($vol5));
		//Test Retrieve
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_id (), "Hartley1112345678");
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_first_name (), "Hartley");
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_last_name (), "Brody");
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_address(), "1 Scarborough Head Rd");
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_city (), "Hilton Head");
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_state (), "SC");
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_zip(), "29928");
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_phone1 (), 1112345678);
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_phone2 (), null);
		$this->assertEqual(retrieve_dbVolunteers($vol1->get_id())->get_email(), "Hartley.Brody@gmail.com");
		
		//Test Update with a change of address
		$vol2 = new Volunteer("Hopkins", "Richardo", "444 Park","Hilton Head", "SC", "29928", "1112345678", "", 
    				"milkywayw@gmail.com", "driver", "active", "HHI", "234567890","SC", "14-01-29", "", "", "Wed:1,Fri:5","",
    				"", "59-01-01","98-01-01", "", "");
		$this->assertTrue(update_dbVolunteers($vol2));
		$this->assertEqual(retrieve_dbVolunteers($vol2->get_id())->get_address(), "444 Park");
/*		
		//Test Delete
		$this->assertTrue(delete_dbVolunteers($vol1->get_id()));
		$this->assertTrue(delete_dbVolunteers($vol2->get_id()));
		$this->assertTrue(delete_dbVolunteers($vol3->get_id()));
		$this->assertTrue(delete_dbVolunteers($vol4->get_id()));
		$this->assertTrue(delete_dbVolunteers($vol5->get_id()));
		$this->assertFalse(retrieve_dbVolunteers($vol2->get_id()));
*/		
		echo ("testdbVolunteers complete \n");
	}
}
?>