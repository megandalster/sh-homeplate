<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * viewStop GUI for Homeplate
 * @author Nicholas Wetzel
 * @version April 25, 2012
 */

	session_start();
	session_cache_expire(30);
	
	include_once('database/dbClients.php');	
	include_once('domain/Client.php');

	//$area = $_GET["area"];
	//$client_id = $_GET["client_id"];
	
	//$client1 = retrieve_dbClients($client_id);
	//$client_type = $client1->get_weight_type();
	$client_type = 'foodtypeboxes';
	
	switch ($client_type)
	{
		case 'pounds':
			include ('viewStop1.php');
			break;
			
		case 'foodtype':
			include ('viewStop2.php');
			break;
			
		case 'foodtypeboxes':
			include ('viewStop3.php');
	}
?>