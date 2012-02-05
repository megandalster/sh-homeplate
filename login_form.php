<?php
/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>
 */
?>
</div>
	<div id="content">
			<?PHP
				include_once(dirname(__FILE__).'/database/dbPersons.php');
     			include_once(dirname(__FILE__).'/domain/Person.php');
     			if(($_SERVER['PHP_SELF'])=="/logout.php"){
     				//prevents infinite loop of logging in to the page which logs you out...
     				echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
     			}
				if(!array_key_exists('_submit_check', $_POST)){
					echo('<div align="left"><p>Access to <i>Homeroom</i> requires a Username and a Password. '  );
				//		 '<ul><li>If you are a <i>guest</i> requesting a room, please sign in with the Username <strong>guest</strong> and no Password. ' .
				//		 ' Once you sign in, you will be able to fill out and submit a room request form on-line.</p>'
						

					echo('<ul><li>You must be a Ronald McDonald House <i>staff member, volunteer, or social worker</i> to access this system.
					<li> Your Username is your first name followed by your phone number. ' .
							'');
					echo(' If you do not remember your Password, please contact the <a href="mailto:housemngr@rmhportland.org">House Manager</a>.</ul>');
					echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
				}
				else{
					//check if they logged in as a guest:
			//		if($_POST['user']=="guest" && $_POST['pass']==""){
			//			$_SESSION['logged_in']=1;
			//			$_SESSION['access_level']=0;
			//			$_SESSION['_id']="guest";
			//			echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
			//		}
					//otherwise authenticate their password
			//		else {
						$db_pass = md5($_POST['pass']);
						$db_id = $_POST['user'];
						$person = retrieve_dbPersons($db_id);
						
					//	echo $person->get_id() . " = retrieved person_id<br>";
						if($person){ //avoids null results
							if($person->get_password()==$db_pass) { //if the passwords match, login
								$_SESSION['logged_in']=1;
								if (in_array('volunteer', $person->get_type()))
									$_SESSION['access_level'] = 1;
								else if (in_array('socialworker', $person->get_type()))
									$_SESSION['access_level'] = 2;
								else if (in_array('manager', $person->get_type()))
									$_SESSION['access_level'] = 3;
								else $_SESSION['access_level'] = 0;
								$_SESSION['f_name']=$person->get_first_name();
								$_SESSION['l_name']=$person->get_last_name();
								$_SESSION['_id']=$_POST['user'];
								echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
							}
							else {
								echo('<div align="left"><p class="error">Error: invalid username/password.');
								
								echo('<p>If you are a volunteer, social worker, or manager, your Username is your first name followed by your phone number with no spaces. ' .
									'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
									'then your Username would be <strong>John2071234567</strong>.  ');
								echo('<br /><br />if you cannot remember your password, ask the <a href="mailto:housemngr@rmhportland.org">House Manager</a> to reset it for you.</p>');
								echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
							}
						}
						else{
							//At this point, they failed to authenticate
							echo('<div align="left"><p class="error">Error: invalid username/password.');
							
								echo('<p>If you are a volunteer, social worker, or manager, your Username is your first name followed by your phone number with no spaces. ' .
									'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
									'then your Username would be <strong>John2071234567</strong>.  ');
								echo('<br /><br />if you cannot remember your password, ask the <a href="mailto:housemngr@rmhportland.org">House Manager</a> to reset it for you.</p>');
								echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
							}
					}
//				}
			?>
				<?PHP include('footer.inc');?>
			</div>
		</div>
	</body>
</html>
