<?PHP
/*
 * Copyright 2011 by Alex Lucyk, Jesus Navarro, and Allen Tucker.
 * This program is part of RMH Homeroom, which is free software.
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
			RMH Homeroom
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
					include_once('database/dbPersons.php');
     				include_once('domain/Person.php');
     				include_once('database/dbBookings.php');
     				include_once('domain/Booking.php');
     				include_once('database/dbLog.php');
     				if($_SESSION['_id']!="guest"){
     				    $person = retrieve_dbPersons($_SESSION['_id']);
     					$first_name = $person->get_first_name();
     					echo "<p>Welcome, ".$first_name.", to RMH Homeroom!";
     				}
     				else
     				    echo "<p>Welcome to RMH Homeroom!";

     			?>
				<p>
			    <?PHP
					if($_SESSION['access_level']==0) 
					    echo('<p> To request a room at the Ronald McDonald House, go <a href="'.$path.
					         'newBooking.php?id='.'new'.'">here</a>.');
				?>

				<br>If you want to learn about this software, select <a href="<?php echo($path);?>about.php">about</a>.
				<p>	When you are finished, please remember to <a href="<?php echo($path);?>logout.php">logout</a>.</p>

				<?PHP
				if ($person){
					/*
					 * Check type of person, and display home page based on that.
					 * level 0: General public, clients: login screen only
					 * level 1: Volunteers: view roomlogs, edit room status and check clients in and out
					 * level 2: Social Workers: view and edit referral forms, view persons and roomlogs
					 * level 3: Managers: view and edit bookings (referral forms), persons, roomlogs, and rooms, view occupancy statistics
					*/
                    echo ('<p>If you want help using this software, select <a href="'.$path.'help.php">help</a> at any time.');
					//DEFAULT PASSWORD CHECK
					if (md5($person->get_id())==$person->get_password()){
						 if(!isset($_POST['_rp_submitted']))
						 	echo('<div class="warning"><form method="post"><p><strong>We recommend that you change your password, which is currently default.</strong><table class="warningTable"><tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr><tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr><tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr><tr><td colspan="2" align="right" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></td></tr></table></p></form></div>');
						 else{
						 	//they've submitted
						 	if(($_POST['_rp_newa']!=$_POST['_rp_newb']) || (!$_POST['_rp_newa']))
						 		echo('<div class="warning"><form method="post"><p>Error with new password. Ensure passwords match.</p><br /><table class="warningTable"><tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr><tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr><tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr><tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></form></td></tr></table></div>');
						 	else if(md5($_POST['_rp_old'])!=$person->get_password())
						 		echo('<div class="warning"><form method="post"><p>Error with old password.</p><br /><table class="warningTable"><tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr><tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr><tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr><tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></form></td></tr></table></div>');
						 	else if((md5($_POST['_rp_old'])==$person->get_password()) && ($_POST['_rp_newa']==$_POST['_rp_newb'])){
						 		$newPass = md5($_POST['_rp_newa']);
						 		$person->set_password($newPass); 
						 		update_dbPersons($person);
						 	}
						 }
						 echo('<br clear="all">');
					}

			    //NOTES OUTPUT
				echo('<div class="infobox"><p class="notes"><strong>Notes to/from the manager:</strong><br />');
				echo($person->get_mgr_notes().'</div></p>');

				// we have a guest authenticated
				if($_SESSION['access_level']==0) {
						 	//SHOW STATUS
					echo('<div class="infobox"><p><strong>Your request has been submitted.</strong><br></p></div><br>');
				}

				//We have a manager authenticated	 
				if($_SESSION['access_level']==3) {
					$two_weeks = $today + 14*86400; 
				    echo('<div class="logBox"><p><strong>Recent bookings, referrals, and room log changes:</strong><br />');
					echo('<table class="searchResults">');
					echo('<tr><td class="searchResults"><u>Time</u></td><td class="searchResults"><u>Message</u></td></tr>');
						 	$log=get_last_log_entries(5);
						 	for($i=0;$i<5;++$i) 
						 		echo('<tr><td class="searchResults">'.$log[$i][1].'</td>' .
						 				'<td class="searchResults">'.$log[$i][2].'</td></tr>');
						 	echo ('</table><br><a href="'.$path.'log.php">View full log</a></p></div><br>');
				    
				}

				}
				?>
				<br clear="all">
				<?PHP include('footer.inc');?>
			</div>
		</div>
	</body>
</html>