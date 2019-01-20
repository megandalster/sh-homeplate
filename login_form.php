<?php
/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>
 */
?>
</div>
	<div id="content">
			<?PHP
				include_once(dirname(__FILE__).'/database/dbVolunteers.php');
     			include_once(dirname(__FILE__).'/domain/Volunteer.php');
     			if(($_SERVER['PHP_SELF'])=="/logout.php"){
     				//prevents infinite loop of logging in to the page which logs you out...
     				echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
     			}
				if(!array_key_exists('_submit_check', $_POST)){
				
					echo('<div align="left"><p>Access to <i>Homeplate</i> requires a Username and a Password. '  );
						 '<ul><li>If you are applying to be a volunteer driver, please sign in with the Username <strong>guest</strong> and no Password. ' .
						 ' Once you sign in, you will be able to fill out and submit an application form on-line.</p>';
					echo('<ul><li>You must be a Second Helpings <i>volunteer, staff member, or board member</i> to access this system. ' .
						'<li> Your Username is your first name followed by your phone number (no spaces). ');
					echo('<br> If you do not remember your Password, please contact your Day Captain.</ul>');
					echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td><td><input type="text" name="user" tabindex="1" onblur="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
					echo('<script>document.forms[0].user.focus();</script>');
				}
				else{
			//check if they logged in as a guest:
					if($_POST['user']=="guest" && $_POST['pass']==""){
					$_SESSION['logged_in']=1;
					$_SESSION['access_level']=0;
					$_SESSION['_area']="all";
					$_SESSION['_id']="guest";
					echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
				}
					//otherwise authenticate their password
				else {
					$db_pass = md5($_POST['pass']);
					$db_id = $_POST['user'];
					$person = retrieve_dbVolunteers($db_id);	
						//echo "loading person<br />";
						//echo $db_pass;
					if($person){ //avoids null results
					//echo "person loaded<br />";
						if($person->get_password()==$db_pass) { //if the passwords match, login
							$_SESSION['logged_in']=1;
							//echo "person logged in<br />";
							if (in_array('boardmember', $person->get_type()) || 
								in_array('coordinator', $person->get_type()) ||
								in_array('teamcaptain', $person->get_type()))
									$_SESSION['access_level'] = 2;
							else if (in_array('driver', $person->get_type()) ||
								in_array('helper', $person->get_type()) ||
								in_array('sub', $person->get_type()))
									$_SESSION['access_level'] = 1;
							else $_SESSION['access_level'] = 0;
							$_SESSION['f_name']=$person->get_first_name();
							$_SESSION['l_name']=$person->get_last_name();
							$_SESSION['_area']=$person->get_area();
							$_SESSION['_id']=$_POST['user'];
							echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
						}
						else {
							echo('<div align="left"><p class="error">Error: invalid username/password.');
							echo('<p>If you are a volunteer, team captain, or officer, your Username is your first name followed by your phone number with no spaces. ' .
								'For instance, if your first name were John and your phone number were (843)-123-4567, ' .
								'then your Username would be <strong>John8431234567</strong>.  ');
							echo('<br /><br />if you cannot remember your password, ask your <a href="mailto:jon25T@gmail.com">Day Captain</a> to reset it for you.</p>');
							echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true">'.
								'<tr><td>Username:</td><td><input type="text" name="user" onblur="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);" tabindex="1"></td></tr><tr><td>Password:</td>'.
								'<td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
						}
					}
					else{
					//At this point, they failed to authenticate
						echo('<div align="left"><p class="error">Error: invalid username/password.');
						echo('<p>If you are a volunteer, team captain, or officer, your Username is your first name followed by your phone number with no spaces. ' .
							'For instance, if your first name were John and your phone number were (207)-123-4567, ' .
							'then your Username would be <strong>John2071234567</strong>.  ');
						echo('<br /><br />if you cannot remember your password, ask your <a href="mailto:jon25T@gmail.com">Day Captain</a> to reset it for you.</p>');
						echo('<p><table><form method="post"><input type="hidden" name="_submit_check" value="true"><tr><td>Username:</td>'.
							'<td><input type="text" name="user" tabindex="1" onblur="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);"></td></tr>'.
							'<tr><td>Password:</td><td><input type="password" name="pass" tabindex="2"></td></tr><tr><td colspan="2" align="center"><input type="submit" name="Login" value="Login"></td></tr></table>');
					}
				}
				}
			?>
				<?PHP include('footer.inc');?>
			</div>
		</div>
	</body>
</html>
