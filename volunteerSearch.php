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
					$areas = array("HHI"=>"Hilton Head","SUN"=>"Sun City","BFT"=>"Beaufort");
					$days = array("Mon"=>"Monday","Tue"=>"Tuesday","Wed"=>"Wednesday","Thu"=>"Thursday","Fri"=>"Friday","Sat"=>"Saturday","Sun"=>"Sunday");
					$weeks = array("odd"=>"odd","even"=>"even","any"=>"any","1"=>"1st","2"=>"2nd","3"=>"3rd","4"=>"4th","5"=>"5th");
					echo('<p><a href="'.$path.'volunteerSchedule.php?area='.$area.'">View driver schedule</a>');
					echo('<a href="'.$path.'volunteerEdit.php?id=new"> | Add new volunteer</a>');	
					echo('<form method="post">');
						echo('<p><strong>Search for volunteers:</strong>');
						echo '<p>Area: <select name="s_area">' .
							'<option value="">--all--</option>'; 
						echo '<option value="HHI"'; if ($area=="HHI") echo " SELECTED"; echo '>Hilton Head</option>' ;
						echo '<option value="SUN"'; if ($area=="SUN") echo " SELECTED"; echo '>Sun City</option>' ;
						echo '<option value="BFT"'; if ($area=="BFT") echo " SELECTED"; echo '>Beaufort</option>';
						echo '</select>';
						echo('&nbsp;&nbsp;Type:<select name="s_type">' .
							'<option value="">--all--</option>' . 
							'<option value="driver" SELECTED>Driver</option>' . '<option value="helper">Helper</option>' .
							'<option value="teamcaptain">Team Captain</option>' . '<option value="coordinator">Coordinator</option>' .
							'<option value="associate">Associate</option>' .
							'</select>');
						echo('&nbsp;&nbsp;Status:<select name="s_status">' .
							'<option value="">--all--</option>' . '<option value="applicant">Applicant</option>' . '<option value="active" SELECTED>Active</option>' .
							'<option value="on-leave">On Leave</option>' . '<option value="former">Former</option>' .
							'</select>');
						echo '<p>Name: ' ;
						echo '<input type="text" name="s_name">';
						
					//	echo '<p id="s_week"><label>Week:&nbsp;&nbsp;</label>';
					//		foreach($weeks as $week=>$weekname)
					//		  echo '<input type="checkbox" name="s_week[]" value='.$week.' />'.$weekname;
						echo '<p id="s_day"><label>Availability:&nbsp;&nbsp;&nbsp;&nbsp;</label>';
							foreach($days as $day=>$dayname)
							  echo '<input type="checkbox" name="s_day[]" value='.$day.' />'.$day;
						
						echo('<p><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search">');
						echo('<ca></p>');
					
				// if user hit "Search"  button, query the database and display the results
					if($_POST['s_submitted']){
						$area = $_POST['s_area'];
						$type = $_POST['s_type'];
						$status = $_POST['s_status'];
                        $name = trim(str_replace('\'','&#39;',htmlentities($_POST['s_name'])));
                        $availability = array();
                        if (!$_POST['s_day'])      // allow "any" day if none checked
                                $_POST['s_day'][] = "";
                        if (!$_POST['s_week'])     // allow "any" week if none checked
                                $_POST['s_week'][] = "";
                        foreach ($_POST['s_day'] as $day) 
                            $availability[] = $day;
                 //               foreach($_POST['s_week'] as $week)
                 //                       $availability[] = $day . ":" . $week;
                        $availability[] = "";
                        // now go after the volunteers that fit the search criteria
                        include_once('database/dbVolunteers.php');
                        include_once('domain/Volunteer.php');
                        //echo "search criteria: ", $area.$type.$status.$name.$availability[0];
                        $result = getonlythose_dbVolunteers($area, $type, $status, $name, $availability[0]);  

						echo '<p><strong>Search Results:</strong> <p>Found '.sizeof($result).' '.$status.' '.
							$type;
						if ($type!="") echo "s";
						if ($areas[$area]!="") echo ' from '.$areas[$area];
						if ($name!="") echo ' with name like "'.$name.'"';
						if ($availability[0]!="") echo ' with availability '. $availability[0];
						if (sizeof($result)>0) {
							echo ' (select one for more info).';
							echo '<p><table> <tr><td>Name</td><td>Phone</td><td>E-mail</td><td>Availability</td></tr>';
							foreach ($result as $vol) {
								echo "<tr><td><a href=volunteerEdit.php?id=".$vol->get_id().">" . 
									$vol->get_first_name() .  " " . $vol->get_last_name() . "</td><td>" . 
									phone_edit($vol->get_phone1()) . "</td><td>" . 
									$vol->get_email() . "</td><td>";
								foreach($vol->get_availability() as $availableon)
									echo ($weeks[substr($availableon,4,1)] . " " . $days[substr($availableon,0,3)] . ", ") ;
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

