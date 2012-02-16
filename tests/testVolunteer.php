<?php
include_once(dirname(__FILE__).'/../domain/Volunteer.php');
class testVolunteer extends UnitTestCase {
    function testVolunteerModule() {
             
    	echo ("starting testVolunteer\n");
    	
        //fake person to test
        $volunteer = new Volunteer("Smith", "John", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "(843)111-2345", "", 
    				"jsmith@aol.com", "driver", "active", "HHI", "123456789","SC", "14-01-29", "", "", "Wed:3,Fri:4","",
    				"", "59-01-01","98-01-01", "", "");
                 
        //testing getter functions
        $this->assertTrue($volunteer->get_first_name() == "John");
        $this->assertTrue($volunteer->get_last_name() == "Smith");
        $this->assertTrue($volunteer->get_address() == "1 Scarborough Head Rd");
        $this->assertTrue($volunteer->get_city() == "Hilton Head");
        $this->assertTrue($volunteer->get_state() == "SC");
        $this->assertTrue($volunteer->get_zip() == "29928");
        $this->assertTrue($volunteer->get_phone1() == "(843)111-2345");
        $this->assertTrue($volunteer->get_phone2() == "");
        $this->assertTrue($volunteer->get_email() == "jsmith@aol.com");
                 
        // tests if the volunteer is a driver (may or may not be a team leader)
        $this->assertTrue($volunteer->is_type("driver"));
        // tests if available on the 4th Friday of the month
        $this->assertTrue($volunteer->is_available("Fri", 4));
        // tests of the volunteer has any prior convictions
        $this->assertEqual(sizeof($volunteer->get_convictions()), 0);
                 
        echo ("testVolunteer complete\n");
    }
}

?>
