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
     				$areas = array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort");
     				date_default_timezone_set('America/New_York');
						
     		//		include_once('database/dbLog.php');
     				if($_SESSION['_id']!="guest"){
     				    $person = retrieve_dbVolunteers($_SESSION['_id']);
     				    $_SESSION['name'] = $person->get_first_name()." ".$person->get_last_name();
     				    echo "<p>Welcome, ".$_SESSION['name'].", to <i>Homeplate</i>! ";    
     				}
     				else
     				    echo "<p>Welcome to <i>Homeplate</i>! ";
     				$today = time();
					echo "<br>Today is ".date('l F j, Y h:i A', $today).".";
					
					if($_SESSION['access_level']==0) 
					    echo('<p> To apply to become a driver with Second Helpings, select <a href="'.$path.
					         'volunteerEdit.php?id='.'new'.'">apply</a>.');
					    else if ($_SESSION['access_level']==1){
					        echo "<br><br>Check that your truck is in " . $areas[$_SESSION['_area']] . " and then hit <b>today's toute </b> to get started."; 
					       echo "<br>&nbsp;&nbsp;Otherwise, hit <b>logout</b>.";
					    }
					else {
					//	include_once('ftp.php');
					//	update_ftp();
						echo "<p>Route and Weight data are always up to date.";
						echo "<br>Please select <b>routes</b> above to view details.";
					}
				?>

			<?PHP
				if ($person){
					/*
					 * Check type of person, and display home page based on that.
					 * level 0: General public, view and edit on-line application
					 * level 1: Volunteers, helpers, and subs: view today's route, upcoming driver schedule
					 * level 2: Day Captains: view this week's route data
					 * level 3: Coordinators: view weekly and monthly reports, export data
					*/
                    //DEFAULT PASSWORD CHECK
					if (md5($person->get_id())==$person->get_password()){
						 if(!isset($_POST['_rp_submitted']))
						 	echo('<div class="warning"><form method="post">
                                <p><strong>We recommend that you change your password, which is currently default.</strong>
                                <table class="warningTable">
                                    <tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr>
                                    <tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr>
                                    <tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr>
                                    <tr><td colspan="2" align="right" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></td></tr>
                                </table></p></form></div>');
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
						 		'<tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></td></tr>'.
						 		'</table></p></form></div>');
						 	else if(md5($_POST['_rp_old'])!=$person->get_password())
						 		echo('<div class="warning"><form method="post"><p>Error with old password.</p><br /><table class="warningTable">'.
						 		'<tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr>'.
						 		'<tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr>'.
						 		'<tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr>'.
						 		'<tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></td></tr>'.
						 		'</table></p></form></div>');
						 	else if((md5($_POST['_rp_old'])==$person->get_password()) && ($_POST['_rp_newa']==$_POST['_rp_newb'])){
						 		$newPass = md5($_POST['_rp_newa']);
						 		$person->set_password($newPass); 
						 		update_dbVolunteers($person);
						 	}
						 }
					}
				}
				?>
			</div>
		</div>
		<?PHP include('footer.inc');?>
	</body>
</html>
