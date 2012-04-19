<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * dbStops module for SH Homeplate
 * @author Nicholas Wetzel and Alex Lucyk
 * @version February 22, 2012
 */

include_once(dirname(__FILE__).'/../domain/Stop.php');
include_once(dirname(__FILE__).'/../database/dbStops.php');
class testdbStops extends UnitTestCase {
    function testdbStopsModule() {
    	
        // creates an empty dbStops table
        // $this->assertTrue(create_dbStops());
        
        // creates some stops to add to the database
       	$stop = new Stop("11-12-29-HHI","Food Lion - Palmetto Bay","donor","","");
    	$stop2 = new Stop("11-12-29-HHI","Piggly Wiggly","donor","meat:100","");
    	$stop3 = new Stop("11-12-29-HHI","Walmart","donor","meat:75","Completed");
        
        // tests the insert function
        $this->assertTrue(insert_dbStops($stop));
        $this->assertTrue(insert_dbStops($stop2));
        $this->assertTrue(insert_dbStops($stop3));                
        
        //tests the retrieve function
        $this->assertEqual(retrieve_dbStops($stop3->get_id())->get_id (), "11-12-29-HHIWalmart");
        $this->assertEqual(retrieve_dbStops($stop3->get_id())->get_type (), "donor");
        $this->assertEqual(retrieve_dbStops($stop3->get_id())->get_items (), array("meat:75"));
        $this->assertEqual(retrieve_dbStops($stop3->get_id())->get_total_weight (), "75");
        $this->assertEqual(retrieve_dbStops($stop3->get_id())->get_notes (), "Completed");    
                 
        //tests the update function
        $stop->set_notes("Completed");
        $this->assertTrue(update_dbStops($stop));
        $this->assertEqual(retrieve_dbStops($stop->get_id())->get_notes (), "Completed");   
         
        //tests the delete function
        $this->assertTrue(delete_dbStops($stop->get_id()));
        $this->assertTrue(delete_dbStops($stop2->get_id()));
        $this->assertTrue(delete_dbStops($stop3->get_id()));
        $this->assertFalse(retrieve_dbStops($stop2->get_id()));
                 
        echo ("testdbStops complete");
    }
}
