<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * dbMonths module for SH Homeplate
 * @author Nicholas Wetzel and Alex Lucyk
 * @version February 22, 2012
 */

include_once(dirname(__FILE__).'/../domain/Month.php');
include_once(dirname(__FILE__).'/../database/dbMonths.php');
class testdbMonths extends UnitTestCase {
    function testdbMonthsModule() {
    	
        // creates some Months to add to the database
       	$month = new Month("12-01","published", "12-01-01-HHI","");
    	$month2 = new Month("12-02","published","12-02-05-HHI","");
    	$month3 = new Month("12-03","unpublished","12-02-03-HHI","");
        
        // tests the insert function
        $this->assertTrue(insert_dbMonths($month));
        $this->assertTrue(insert_dbMonths($month2));
        $this->assertTrue(insert_dbMonths($month3));                
        
        //tests the retrieve function
        $this->assertEqual(retrieve_dbMonths($month3->get_id())->get_id (), "12-03");
        $this->assertEqual(retrieve_dbMonths($month3->get_id())->get_status (), "unpublished");
        $this->assertEqual(retrieve_dbMonths($month3->get_id())->get_routes (), array("12-02-03-HHI"));
        $this->assertEqual(retrieve_dbMonths($month3->get_id())->get_notes (), "");    
                 
        //tests the update function
        $month->set_notes("Completed");
        $this->assertTrue(update_dbMonths($month));
        $this->assertEqual(retrieve_dbMonths($month->get_id())->get_notes (), "Completed");   
         
        //tests the delete function
        $this->assertTrue(delete_dbMonths($month->get_id()));
        $this->assertTrue(delete_dbMonths($month2->get_id()));
        $this->assertTrue(delete_dbMonths($month3->get_id()));
        $this->assertFalse(retrieve_dbMonths($month2->get_id()));
                 
        echo ("testdbMonths complete");
    }
}
