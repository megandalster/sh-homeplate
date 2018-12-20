<?php
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Volunteer.php');
class VolunteerTest extends TestCase {
    function testVolunteer() {
             
    	//fake person to test
        $volunteer = new Volunteer("Smith", "John", "1 Scarborough Head Rd","Hilton Head", "SC", "29928", "8431112345", "", 
    				"jsmith@aol.com", "driver", "active", "HHI", "123456789","SC", "14-01-29", "", "Wed:3,Fri:4","",
    				"", "59-01-01","98-01-01", "", "","","","","","","");
                 
        //testing getter functions
        $this->assertTrue($volunteer->get_first_name() == "John");
        $this->assertTrue($volunteer->get_last_name() == "Smith");
        $this->assertTrue($volunteer->get_address() == "1 Scarborough Head Rd");
        $this->assertTrue($volunteer->get_city() == "Hilton Head");
        $this->assertTrue($volunteer->get_state() == "SC");
        $this->assertTrue($volunteer->get_zip() == "29928");
        $this->assertTrue($volunteer->get_phone1() == "8431112345");
        $this->assertTrue($volunteer->get_phone2() == "");
        $this->assertTrue($volunteer->get_email() == "jsmith@aol.com");

        $this->assertTrue($volunteer->is_type("driver"));
        $this->assertTrue($volunteer->is_available("Fri", 4));
    }
}

?>
