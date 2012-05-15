<?PHP
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
			Homeplate
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
					include_once('database/dbVolunteers.php');
     				include_once('domain/Volunteer.php');
     		//		include_once('database/dbLog.php');
     				if($_SESSION['_id']!="guest"){
     				    $person = retrieve_dbVolunteers($_SESSION['_id']);
     					$first_name = $person->get_first_name();
     					echo "<p>Welcome, ".$first_name.", to <i>Homeplate</i>! ";
     				}
     				else
     				    echo "<p>Welcome to <i>Homeplate</i>! ";
     				$today = time();
					echo "Today is ".date('l F j, Y', $today).".<p>";
     		
					if($_SESSION['access_level']==0) 
					    echo('<p> To apply to become a driver with Second Helpings, select <a href="'.$path.
					         'volunteerEdit.php?id='.'new'.'">apply</a>.');
				?>

			<?PHP
				if ($person){
					/*
					 * Check type of person, and display home page based on that.
					 * level 0: General public, driver applications: login screen and on-line application
					 * level 1: Volunteers: view route, select stops and enter pickup or delivery amounts
					 * level 2: Team Captains: view and edit volunteer and route data, generate weekly and monthly reports
					 * level 3: Officers: view weekly and monthly reports, export data
					*/
                    //DEFAULT PASSWORD CHECK
					if (md5($person->get_id())==$person->get_password()){
						 if(!isset($_POST['_rp_submitted']))
						 	echo('<div class="warning"><form method="post"><p><strong>We recommend that you change your password, which is currently default.</strong><table class="warningTable"><tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr><tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr><tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr><tr><td colspan="2" align="right" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></td></tr></table></p></form></div>');
						 else{
						 	//they've submitted
						 	if(($_POST['_rp_newa']!=$_POST['_rp_newb']) || (!$_POST['_rp_newa']))
						 		echo('<div class="warning"><form method="post">'.
						 		'<p>Error with new password. Ensure passwords match.</p><br />'.
						 		'<table class="warningTable">'.
						 		'<tr><td class="warningTable">Old Password:</td>'.
						 		'<td class="warningTable"><input type="password" name="_rp_old"></td></tr>'.
						 		'<tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr>'.
						 		'<tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr>'.
						 		'<tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></form></td></tr>'.
						 		'</table></div>');
						 	else if(md5($_POST['_rp_old'])!=$person->get_password())
						 		echo('<div class="warning"><form method="post"><p>Error with old password.</p><br /><table class="warningTable">'.
						 		'<tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr>'.
						 		'<tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr>'.
						 		'<tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr>'.
						 		'<tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></form></td></tr>'.
						 		'</table></div>');
						 	else if((md5($_POST['_rp_old'])==$person->get_password()) && ($_POST['_rp_newa']==$_POST['_rp_newb'])){
						 		$newPass = md5($_POST['_rp_newa']);
						 		$person->set_password($newPass); 
						 		update_dbVolunteers($person);
						 	}
						 }
						 echo('<br clear="all">');
					}

			    //NOTES OUTPUT
				echo('<div class="infobox"><p class="notes"><strong>Notes to/from the team captain:</strong><br />');
				echo($person->get_notes().'</div>');

				// we have a guest authenticated
				if($_SESSION['access_level']==0) {
					echo('<div class="infobox"><p><strong>Your application has been submitted.  Thank you!</strong><br></p></div>)');
				}
				// We have a driver authenticated -- show them today's route
				if ($_SESSION['access_level']==1) {
					echo "<p>(Here we need to show the driver today's route for his/her area.)";
				}
				//We have a team captain authenticated	 
				if($_SESSION['access_level']==2) {
					echo "<p>(Here we need to show the team captain today's system status: routes completed this week, notes from drivers, etc.)";
				}
				//We have a program officer authenticated	 
				if($_SESSION['access_level']==3) {
					echo "<p>(Here we need to show the program officer today's system status: weekly report, monthly report, notes from team captains, etc.)";
				}
				}
				?>
				<br clear="all">
				
			</div>
			<?PHP include('footer.inc');?>
		</div>
	</body>
</html>