<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/
	session_start();
	session_cache_expire(30);
?>
<html>
	<head>
		<title>
			Manage Tablets
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>	
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
                include_once(dirname(__FILE__).'/domain/Device.php');
                include_once(dirname(__FILE__).'/database/dbDevices.php');
					// display the devices
	echo "we are here";
                $allDevices = getall_dbDevices();
                echo('<p><a href=deviceEdit.php?id=new">Add new tablet</a>');
			        echo('<p><strong>Here are the tablets currently registered with Homeplate:</strong>');
					echo "<table> <tr><td>id</td><td>status</td><td>base</td><td>owner</td><td>date activated</td><td>notes</td></tr>";
					foreach ($allDevices as $device) {
					    echo "<tr><td><a href=deviceEdit.php?id=" . $device->get_id() ."></td>"; 
					    echo "<td>".$device->get_status()."</td>";  
					    echo "<td>".$device->get_base()."</td>";  
					    echo "<td>".$device->get_owner()."</td>";  
					    echo "<td>".$device->get_date_activated()."</td>";  
					    echo "<td>".$device->get_notes()."</td></tr>";  
					}
                    echo "</table>";  
			?>	
			<!-- below is the footer that we're using currently-->
			</div>
			<?PHP include('footer.inc');?>
		</div>
	</body>
</html>

