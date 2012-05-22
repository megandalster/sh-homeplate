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
			Search for People
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
					$areas = array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort");
					$days = array("Mon"=>"Monday","Tue"=>"Tuesday","Wed"=>"Wednesday","Thu"=>"Thursday","Fri"=>"Friday","Sat"=>"Saturday","Sun"=>"Sunday");
					echo('<p><a href="'.$path.'volunteerEdit.php?id=new">Add new volunteer</a>');
					echo('<form method="post">');
						echo('<p><strong>Search for volunteers:</strong>');
                        
                        if( array_key_exists('s_area', $_POST) ) $area = $_POST['s_area']; //override the GET variable if we just conducted a search
						echo '<p>Area: <select name="s_area">' .
							'<option value="">--all--</option>'; 
                            echo '<option value="HHI"'; if ($area=="HHI") echo " SELECTED"; echo '>Hilton Head</option>' ;
                            echo '<option value="SUN"'; if ($area=="SUN") echo " SELECTED"; echo '>Bluffton</option>' ;
                            echo '<option value="BFT"'; if ($area=="BFT") echo " SELECTED"; echo '>Beaufort</option>';
						echo '</select>';
                        
                        if( !array_key_exists('s_type', $_POST) ) $type = ""; else $type = $_POST['s_type'];
						echo '&nbsp;&nbsp;Type:<select name="s_type">';
							echo '<option value=""'; if ($type=="") echo " SELECTED"; echo '>--all--</option>'; 
							echo '<option value="driver"'; if ($type=="driver") echo " SELECTED"; echo '>Driver</option>' . '<option value="helper">Helper</option>'; 
							echo '<option value="sub"'; if ($type=="sub") echo " SELECTED"; echo '>Sub</option>';
							echo '<option value="teamcaptain"'; if ($type=="teamcaptain") echo " SELECTED"; echo '>Day Captain</option>' . '<option value="coordinator">Coordinator</option>'; 
							echo '<option value="associate"'; if ($type=="associate") echo " SELECTED"; echo '>Associate</option>';
							echo '<option value="boardmember"'; if ($type=="boardmember") echo " SELECTED"; echo '>Board Member</option>';
                        echo '</select>';
                        
                        if( !array_key_exists('s_status', $_POST) ) $status = ""; else $status = $_POST['s_status'];
						echo '&nbsp;&nbsp;Status:<select name="s_status">';
							echo '<option value=""';            if ($status=="")            echo " SELECTED"; echo '>--all--</option>';
                            echo '<option value="applicant"';   if ($status=="applicant")   echo " SELECTED"; echo '>Applicant</option>';
                            echo '<option value="active"';      if ($status=="active")      echo " SELECTED"; echo '>Active</option>';
							echo '<option value="on-leave"';    if ($status=="on-leave")    echo " SELECTED"; echo '>On Leave</option>';
                            echo '<option value="former"';      if ($status=="former")      echo " SELECTED"; echo '>Former</option>';
                        echo '</select>';
                        
						if( !array_key_exists('s_name', $_POST) ) $name = ""; else $name = $_POST['s_name'];
						echo '&nbsp;&nbsp;Name: ' ;
						echo '<input type="text" name="s_name" value="' . $name . '">';
					
						echo '<fieldset>';
						echo '<legend>Availability:</legend>';
                        echo '<p id="s_day">Day:&nbsp;&nbsp;&nbsp;&nbsp;';
                            if( array_key_exists('s_day', $_POST) ){
                                foreach($days as $day=>$dayname){
                                  echo '<label><input type="checkbox" name="s_day[]" value="'.$day.'"'; 
                                  if (in_array($day, $_POST['s_day'])) 
                                    echo " CHECKED"; echo' />'.$day.'</label>&nbsp;&nbsp;';
                                }
                            }
                            else{
                                foreach($days as $day=>$dayname){
                                  echo '<label><input type="checkbox" name="s_day[]" value="'.$day.'" />'.$day.'</label>&nbsp;&nbsp;';
                                }
                            }
						echo '</fieldset>';
						echo('<p><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search">');
						echo('</form></p>');
                        
                        //print_r( $_POST );
					
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
                        
                        //echo "search criteria: ", $area.$type.$status.$name.$availability[0];
                        
                        // now go after the volunteers that fit the search criteria
                        include_once('database/dbVolunteers.php');
                        include_once('domain/Volunteer.php');
                        
                        $result = getonlythose_dbVolunteers($area, $type, $status, $name, $availability[0]);  

						echo '<p><strong>Search Results:</strong> <p>Found ' . sizeof($result). ' ';
                            if (!$type) echo "volunteer(s)"; 
                            else echo $type.'s';
						if ($areas[$area]!="") echo ' from '.$areas[$area];
						if ($name!="") echo ' with name like "'.$name.'"';
						if ($availability[0]!="") echo ' with availability '. $availability[0];
						if (sizeof($result)>0) {
							echo ' (select one for more info).';
							echo '<p><table> <tr><td><strong>Name</strong></td><td><strong>Phone</strong></td><td><strong>E-mail</strong></td><td><strong>Availability</strong></td></tr>';
                            $allEmails = array(); // for printing all emails
                            foreach ($result as $vol) {
								echo "<tr><td><a href=volunteerEdit.php?id=".$vol->get_id().">" . 
									$vol->get_last_name() .  ", " . $vol->get_first_name() . "</td><td>" . 
									$vol->get_phone1() . "</td><td>" . 
									$vol->get_email() . "</td><td>"; $allEmails[] = $vol->get_email();
								foreach($vol->get_availability() as $availableon)
									echo ($availableon . ", ") ;
								echo "</td></a></tr>";
							}
						}
						echo '</table>';
                        
                        echo "<br/><strong>email these people:</strong> <br/>";
                        if ($allEmails)
                          foreach($allEmails as $email)
                            if ($email!="")
                              echo $email . ", ";
						
					}
					
				?>
				<!-- below is the footer that we're using currently-->
				
			</div>
			<?PHP include('footer.inc');?>
		</div>
	</body>
</html>

