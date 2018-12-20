<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * testdbStops module for SH Homeplate
 * @author Nicholas Wetzel
 * @version May 8, 2012
 */

/*
 * Provides the test module for the dbStops file.
 */
use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__).'/../domain/Stop.php');
include_once(dirname(__FILE__).'/../database/dbStops.php');
class dbStopsTest extends TestCase {
    function testdbStopsModule() {
    	
        // Setup
       	$stop = new Stop("11-12-29-HHI","Food Lion - Palmetto Bay","donor","","");
    	$stop2 = new Stop("11-12-29-HHI","Piggly Wiggly","donor","meat:100","");
    	$stop3 = new Stop("11-12-29-HHI","Walmart","donor","meat:75","Completed");
        $this->assertTrue(insert_dbStops($stop));
        $this->assertTrue(insert_dbStops($stop2));
        $this->assertTrue(insert_dbStops($stop3));                
        
        // Test -- retrieve and update
        $this->assertEquals(retrieve_dbStops($stop3->get_id())->get_id (), "11-12-29-HHIWalmart");
        $this->assertEquals(retrieve_dbStops($stop3->get_id())->get_type (), "donor");
        $this->assertEquals(retrieve_dbStops($stop3->get_id())->get_items (), array("meat:75"));
        $this->assertEquals(retrieve_dbStops($stop3->get_id())->get_total_weight (), "75");
        $this->assertEquals(retrieve_dbStops($stop3->get_id())->get_notes (), "Completed");    
        $stop->set_notes("Completed");
        $this->assertTrue(update_dbStops($stop));
        $this->assertEquals(retrieve_dbStops($stop->get_id())->get_notes (), "Completed");   
         
        // Teardown
        $this->assertTrue(delete_dbStops($stop->get_id()));
        $this->assertTrue(delete_dbStops($stop2->get_id()));
        $this->assertTrue(delete_dbStops($stop3->get_id()));
        $this->assertFalse(retrieve_dbStops($stop2->get_id()));
    }
}
