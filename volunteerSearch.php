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

				include_once(dirname(__FILE__).'/database/dbAffiliates.php');
	?>

<html>
	<head>
		<title>
			Search for People
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
                        
                        if( !array_key_exists('s_status', $_POST) ) $status = ""; else $status = $_POST['s_status'];
						echo '&nbsp;&nbsp;Status:<select name="s_status">';
							echo '<option value="active"';      if ($status=="active")      echo " SELECTED"; echo '>Active</option>';
							echo '<option value="applicant"';   if ($status=="applicant")   echo " SELECTED"; echo '>Applicant</option>';
                            echo '<option value="on-leave"';    if ($status=="on-leave")    echo " SELECTED"; echo '>On Leave</option>';
                            echo '<option value="former"';      if ($status=="former")      echo " SELECTED"; echo '>Former</option>';
                        echo '</select>';
                        
						  if( !array_key_exists('s_affiliate', $_POST) ) $affiliate = ""; else $affiliate = $_POST['s_affiliate'];
						echo '&nbsp;&nbsp;Affiliate:<select name="s_affiliate">';
							echo '<option value=""';            if ($affiliate=="")            echo " SELECTED"; echo '>--all--</option>';
							
							$allAffiliates = getall_dbAffiliates();
							foreach ($allAffiliates as $affiliateRow) {
								echo '<option value="';            
								echo $affiliateRow->get_affiliateId();
								echo '"';
								if ($affiliate==$affiliateRow->get_affiliateId())            
									echo ' SELECTED';
								echo '>';
								echo $affiliateRow->get_affiliateName();
								echo "</option>";
							}
                        echo '</select>';
                        
						
						if( !array_key_exists('s_name', $_POST) ) $name = ""; else $name = $_POST['s_name'];
						echo '&nbsp;&nbsp;Name: ' ;
						echo '<input type="text" name="s_name" value="' . $name . '">';
					
						$types = array('driver'=>'Driver', 'helper'=>'Helper', 'teamcaptain'=>'Day Captain', 'sub' => "Sub",
								'coordinator'=>'Coordinator', 'associate'=>"Associate", 'boardmember'=>"Board Member");
                        echo('<p>Role(s):&nbsp;&nbsp;&nbsp;&nbsp;');
                            if( array_key_exists('s_type', $_POST) ){
                                foreach($types as $type=>$typename){
                                  echo '<label><input type="checkbox" name="s_type[]" value="'.$type.'"'; 
                                  if (in_array($type, $_POST['s_type'])) 
                                    echo " CHECKED"; echo' />'.$typename.'</label>&nbsp;&nbsp;';
                                }
                            }
                            else{
                                foreach($types as $type=>$typename){
                                  echo '<label><input type="checkbox" name="s_type[]" value="'.$type.'" />'.$typename.'</label>&nbsp;&nbsp;';
                                }
                            }
						echo '<p id="s_day">Schedule:&nbsp;&nbsp;&nbsp;&nbsp;';
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
						
						echo('<p><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search">');
						echo('</form></p>');
                        
                        //print_r( $_POST );
					
				// if user hit "Search"  button, query the database and display the results
					if( array_key_exists('s_submitted', $_POST) ){
						$area = $_POST['s_area'];
						$status = $_POST['s_status'];
						$affiliate = $_POST['s_affiliate'];
                        $name = trim(str_replace('\'','&#39;',htmlentities($_POST['s_name'])));
                        
                        $searchtypes = array();
                        if ( !array_key_exists('s_type', $_POST) ) 
                            $_POST['s_type'][] = ""; // allow "any" type if none checked
                        foreach ($_POST['s_type'] as $type) 
                        	$searchtypes[] = $type;
                        
                        $availability = array();
                        if ( !array_key_exists('s_day', $_POST) ) 
                            $_POST['s_day'][] = ""; // allow "any" day if none checked
                        foreach ($_POST['s_day'] as $day) 
                        	$availability[] = $day;
                        
                        echo "search criteria: ", $area.$searchtypes[0].$status.$name.$availability[0];
                        
                        // now go after the volunteers that fit the search criteria
                        include_once('database/dbVolunteers.php');
                        include_once('domain/Volunteer.php');
                        
                        $result = getonlythose_dbVolunteers($area, $searchtypes, $status, $name, $availability, $affiliate);  

						echo '<div id="dvReport"><strong>Search Results:</strong> <p>Found ' . sizeof($result). ' ';
                        echo "volunteer(s)"; 
                        if ($searchtypes[0]!="") echo " with the above Roles ";
						if ($areas[$area]!="") echo ' from '.$areas[$area];
						if ($name!="") echo ' with name like "'.$name.'"';
						if ($availability[0]!="") echo ' with the above Schedule days ';
						if (sizeof($result)>0) {
							echo ' <div id="dvLinkInfo">(select one for more info).</div>';
							echo '<table id="tblReport"> <tr><td><strong>Name</strong></td><td><strong>Phone</strong></td><td><strong>E-mail</strong></td><td><strong>Schedule</strong></td><td><strong>Cell</strong></td><td><strong>Trips</strong></td><td><strong>Last Date</strong></td><td><strong>Notes</strong></td></tr>';
                            $allEmails = array(); // for printing all emails
                            foreach ($result as $vol) {
                            	echo "<tr><td><a href=volunteerEdit.php?id=".$vol->get_id().">" . 
									$vol->get_last_name() .  ", " . $vol->get_first_name() . "</td><td>" . 
									$vol->get_nice_phone1() . "</td><td>" . 
									$vol->get_email() . "</td><td>"; $allEmails[] = $vol->get_email();
								foreach($vol->get_availability() as $availableon)
									echo ($availableon . ", ") ;
								echo "</td></a>";
								echo "<td>" . $vol->get_phone2() . "</td>";
								echo "<td>" . $vol->get_tripCount() . "</td>";
								echo "<td>" . $vol->get_lastTripDate() . "</td>";
								
								echo "<td>" . $vol->get_notes() . "</td>";
								echo "</tr>";
							}
						}
						echo '</table>';
                      ?>

					</div>
						<div style="padding:10px;">
						<input type="button" value="Print List" onclick="showPrintWindow();" />
						</div>
						<?PHP
						
						
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
		
		
<script type="text/javascript">
			function showPrintWindow(){
				
				var printWin = window.open('', 'winReport', 'width=690px;height:600px;resizable=1');
				var html = $("#tblReport").parent().html();
				
				printWin.document.open();
				printWin.document.writeln("<html><head><title>Print Donor/Recipients</title><style>#tblReport td {border:1px solid black;}");			
				printWin.document.write("#dvLinkInfo{display:none;}</style></head><body>");
				printWin.document.writeln(html);
				printWin.document.write('<scr');
				printWin.document.write('ipt>');
				printWin.document.writeln('setTimeout("window.print()", 200);');
				printWin.document.write('</scr');
				printWin.document.write('ipt>');
				printWin.document.write('</body>');
				printWin.document.write('</html>');
				printWin.document.close();
				
			}
		</script>
		
	</body>
</html>

