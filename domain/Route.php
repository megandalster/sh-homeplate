<?php 
class Route {
	private $id;			// String: "yy-mm-dd-area" serves as a unique id 
							// for the route.  e.g. 11-12-29-HHI
 	private $drivers;       // array of driver id's scheduled for this route,
							// e.g. ["malcom1234567890", Òsandi8437891234Ó]
	private $teamcaptain_id;
	private $stops;			// array of stops for this Route
    private $day;	      	// string name of this day "Monday"...
    private $notes;			// notes written by the team captain 
}
?>