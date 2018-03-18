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
				include_once('domain/Device.php');
				include_once('database/dbDevices.php');
					// display the search form
				$allDevices = getall_dbDevices();
					echo('<p><a href="deviceEdit.php?id=new">Add new tablet</a>');
			        echo('<p>Here are the tablets currently registered with Homeplate:<p>');
					echo "<table> <tr><td>id</td><td>status</td><td>owner</td>
									<td>date activated</td><td>last used</td><td>base</td><td>notes</td></tr>";
					foreach ($allDevices as $device) {
					    echo "<tr><td><a href='deviceEdit.php?id=" . $device->get_id() ."'>".
					    	substr($device->get_id(),0,4)."..."."</a></td>"; 
					    echo "<td>".$device->get_status()."</td>";  
					    echo "<td>".$device->get_owner()."</td>";  
					    echo "<td>".$device->get_date_activated()."</td>";  
					    echo "<td>".pretty($device->get_last_used())."</td>";
					    echo "<td>".$device->get_base()."</td>";
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

