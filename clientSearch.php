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
			Search for Clients
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
				// display the search form
					$area = $_GET['area'];
					$areas = array("HHI"=>"Hilton Head","SUN"=>"Sun City","BFT"=>"Beaufort");
					$days = array("Mon"=>"Monday","Tue"=>"Tuesday","Wed"=>"Wednesday","Thu"=>"Thursday","Fri"=>"Friday","Sat"=>"Saturday","Sun"=>"Sunday");
					$weeks = array("1"=>"1st","2"=>"2nd","3"=>"3rd","4"=>"4th","5"=>"5th");
					echo('<p><a href="'.$path.'clientSchedule.php">View client schedule</a>');
					echo('<a href="'.$path.'clientEdit.php?id=new"> | Add new client</a>');	
					echo('<form method="post">');
						echo('<p><strong>Search for clients:</strong>');
						echo '<p>Area: <select name="s_area">' .
							'<option value=""></option>'; 
                            echo '<option value="HHI"'; if ($area=="HHI") echo " SELECTED"; echo '>Hilton Head</option>' ;
                            echo '<option value="SUN"'; if ($area=="SUN") echo " SELECTED"; echo '>Sun City</option>' ;
                            echo '<option value="BFT"'; if ($area=="BFT") echo " SELECTED"; echo '>Beaufort</option>';
						echo '</select>';
                        
                        if( !array_key_exists('s_type', $_POST) ) $type = ""; else $type = $_POST['s_type'];
						echo '&nbsp;&nbsp;Type:<select name="s_type">';
                            echo '<option value=""'; if ($type=="") echo " SELECTED"; echo '></option>'; 
                            echo '<option value="donor"'; if ($type=="donor") echo " SELECTED"; echo '>Donor</option>'; 
                            echo '<option value="recipient"'; if ($type=="recipient") echo " SELECTED"; echo '>Recipient</option>'; 
                        echo '</select>';
                        
                        if( !array_key_exists('s_status', $_POST) ) $status = ""; else $status = $_POST['s_status'];
						echo '&nbsp;&nbsp;Feed America:<select name="s_status">';
                                echo '<option value=""'; if ($status=="") echo " SELECTED"; echo '></option>';
                                echo '<option value="yes"'; if ($status=="yes") echo " SELECTED"; echo '>Yes</option>';
                                echo '<option value="no"'; if ($status=="no") echo " SELECTED"; echo '>No</option>';
                        echo '</select>';
						echo '&nbsp;&nbsp;Name: ' ;
						echo '<input type="text" name="s_name">';
						echo '<fieldset>';
						echo '<legend>Availability:</legend>';
						echo '<p id="s_day">Day:&nbsp;&nbsp;&nbsp;&nbsp;';
							foreach($days as $day=>$dayname)
							  echo '<label><input type="checkbox" name="s_day[]" value='.$day.' />'.$day.'</label>';
						echo '</fieldset>';
						echo('<p><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search">');
						echo('</form></p>');
					
				// if user hit "Search"  button, query the database and display the results
					if( array_key_exists('s_submitted', $_POST) ){
						$area = $_POST['s_area'];
						$type = $_POST['s_type'];
						$status = $_POST['s_status'];
                        $name = trim(str_replace('\'','&#39;',htmlentities($_POST['s_name'])));
                        
                        $availability = array();
                        if ( !array_key_exists('s_day', $_POST) ) 
                            $_POST['s_day'][] = ""; // allow "any" day if none checked
                        foreach ($_POST['s_day'] as $day) 
                        		$availability[] = $day;
                        
                        //print_r( $availability );
     					//echo "search criteria: ", $area.$type.$status.$name.$availability[0];
     					//echo "type: ", $type;
                        
                        // now go after the volunteers that fit the search criteria
                        include_once('database/dbClients.php');
     					include_once('domain/Client.php');
						
                        $result = getall_clients($area, $type, $status, $name, $availability);
                        //print_r( $result );
						
                        echo '<p><strong>Search Results:</strong> <p>Found ' . sizeof($result). ' ';
                            if (!$type) echo "client(s)"; 
                            else echo $type.'s';
						if ($areas[$area]!="") echo ' from '.$areas[$area];
						if ($name!="") echo ' with name like "'.$name.'"';
						if (sizeof($result)>0) {
							echo ' (select one for more info).';
							echo '<p><table> <tr><td><strong>Name</strong></td><td><strong>Phone</strong></td><td><strong>Area</strong></td><td><strong>Availability</strong></td></tr>';
							foreach ($result as $client) {
								echo ("<tr><td><a href='clientEdit.php?id=" . $client->get_id() ."'>" .
									$client->get_id() . "</td><td>" . 
									$client->get_phone1() . "</td><td>" . 
									$client->get_area() . "</td><td>");
								foreach($client->get_days() as $availableon)
									echo ( $availableon . ", ") ;
								echo "</td></a></tr>";
							}
						}
						echo '</table>';
						
					}
					
				?>
				<!-- below is the footer that we're using currently-->
				<?PHP include('footer.inc');?>
			</div>
		</div>
	</body>
</html>

