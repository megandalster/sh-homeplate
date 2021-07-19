<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/

/*
 *	volunteerEdit.php
 *  oversees the editing of a volunteer to be added, changed, or deleted from the database
 *	@author Allen Tucker
 *	@version April 1, 2012
 */
	session_start();
	session_cache_expire(30);		
    include_once('database/dbVolunteers.php');
    include_once('domain/Volunteer.php');
    include_once('database/dbSchedules.php'); 
	 include_once('database/dbAffiliates.php');
    include_once('domain/Affiliate.php');
    
//    include_once('database/dbLog.php');
	$id = $_GET["id"];
	if ($id=='new') {
	 	$person = new Volunteer(null,'new',null,null,null,null,null,null,null,null,"active",
	 	     	null,null,null,null,null,null,null,null,null,null,null,md5("new"), 0, null, null, null, 'M', null);
	}
	else {
		$person = retrieve_dbVolunteers($id);
		if (!$person) {
	         echo('<p id="error">Error: there\'s no person with this id in the database</p>'. $id);
		     die();
        }  
	}
?>
<html>
	<head>
		<title>
			Editing <?PHP echo($person->get_first_name()." ".$person->get_last_name());?>
		</title>
		
		 <link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
<body>
  <div id="container">
    <?PHP include('header.php');?>
	<div id="content">
<?PHP
	include('volunteerValidate.inc');
	if($_POST['_form_submit']!=1)
	//in this case, the form has not been submitted, so show it
		include('volunteerForm.inc');
	else {
	//in this case, the form has been submitted, so validate it
		if ($_POST['availability']==null)
			   $avail = "";
		else $avail = implode(',',$_POST['availability']);
		if ($id=='new') {
				$first_name = trim($_POST['first_name']);
				$last_name = $_POST['last_name'];
				$phone1 = $_POST['phone1'];
				$tripCount = 0;
				$lastTripDates = "";
		}
		else {
				$first_name = $person->get_first_name();
				$last_name = $person->get_last_name();
				$phone1 = $person->get_phone1();
				$tripCount = $person->get_tripCount();
				$lastTripDates = implode(',',$person->get_lastTripDates());
		}
		$person = new Volunteer($last_name, $first_name, $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'],
								 $phone1, $_POST['phone2'], $_POST['email'], implode(',',$_POST['type']),
								 $_POST['status'], $_POST['area'], $_POST['license_no'], $_POST['license_state'], $_POST['license_expdate'],
                                 $_POST['accidents'],
                                 $avail, $_POST['schedule'], $_POST['history'],
                                 $_POST['birthday'],
                                 $_POST['start_date'],
                                 $_POST['notes'], $_POST['old_pass'], $tripCount, $lastTripDates, 
								 $_POST['volunteerTrainingDate'], $_POST['driverTrainingDate'], $_POST['shirtSize'], $_POST['affiliateId']);
		$errors = validate_form($id); 	//step one is validation.
									// errors array lists problems on the form submitted
		if ($errors) {
		// display the errors and the form to fix
			show_errors($errors);
			include('volunteerForm.inc');
		}
		// this was a successful form submission; update the database and exit
		else
			process_form($id, $person);
		include('footer.inc');
		echo('</div></div></body></html>');
		die();
	}
	
/**
* process_form sanitizes data, concatenates needed data, and enters it all into a database
*/
function process_form($id, $person)	{
	//step one: sanitize data by replacing HTML entities and escaping the ' character
		if ($id=='new') {
		    $first_name = trim(str_replace(' ','',str_replace('\\\'', '\'', htmlentities(str_replace('&', 'and', $_POST['first_name'])))));	
		//    $first_name = trim(str_replace('\\\'','',htmlentities(trim($_POST['first_name']))));
				$last_name = trim(str_replace('\\\'','\'',htmlentities($_POST['last_name'])));
				$phone1 = trim(str_replace(' ','',htmlentities($_POST['phone1'])));
				$clean_phone1 = preg_replace("/[^0-9]/", "", $phone1);
				$tripCount = 0;
				$lastTripDates = "";
		}
		else {
				$first_name = $person->get_first_name();
				$last_name = $person->get_last_name();
				$phone1 = $person->get_phone1();
				$clean_phone1 = $phone1;
				$tripCount = $person->get_tripCount();
				$lastTripDates = implode(',',$person->get_lastTripDates());
		}
		$address = trim(str_replace('\\\'','\'',htmlentities($_POST['address'])));
		$city = trim(str_replace('\\\'','\'',htmlentities($_POST['city'])));
		$state = trim(htmlentities($_POST['state']));
		$zip = trim(htmlentities($_POST['zip']));
		$phone2 = trim(str_replace(' ','',htmlentities($_POST['phone2'])));
		$clean_phone2 = preg_replace("/[^0-9]/", "", $phone2);
		$email = $_POST['email'];

		if ($_SESSION['access_level']==0 && !in_array('applicant',$_POST['type']))
			$_POST['type'][] = 'applicant';
		$type = implode(',', $_POST['type']);
			
        $status = $_POST['status'];
        $area = $_POST['area'];
        $license_no = $_POST['license_no'];
        $license_state = $_POST['license_state'];
        $license_expdate = $_POST['expdate_Year'].'-'.$_POST['expdate_Month'].'-'.$_POST['expdate_Day'];
		if (strlen($license_expdate) < 8) $license_expdate = '';
        $accidents = trim(str_replace('\\\'','\'',htmlentities($_POST['accidents'])));
        if ($_POST['availability'] != null)
			$availability=implode(',', $_POST['availability']);
		else $availability = "";
        
		// these three are not visible for editing, so they go in and out unchanged
		$schedule = $_POST['schedule'];
		$history = $_POST['history'];
		$pass = $_POST['password'];
		$shirtSize = $_POST['shirtSize'];
		
		if (strlen($volunteerTrainingDate) < 8) $volunteerTrainingDate = '';
		$driverTrainingDate = substr($_POST['driverTrainingDate'],8,2)."-".substr($_POST['driverTrainingDate'],0,2)."-".
								substr($_POST['driverTrainingDate'],3,2);
		if (strlen($driverTrainingDate) < 8) $driverTrainingDate = '';
		$affiliateId = $_POST['affiliateId'];
		//rebuild birthday and start_date strings
		if($_POST['birthday_Year']=="")
				$birthday = 'XX-'.$_POST['birthday_Month'].'-'.$_POST['birthday_Day'];
		else
				$birthday = $_POST['birthday_Year'].'-'.$_POST['birthday_Month'].'-'.$_POST['birthday_Day'];
		if (strlen($birthday) < 8) $birthday = '';
		$start_date = substr($_POST['startDate'],8,2)."-".substr($_POST['startDate'],0,2)."-".substr($_POST['startDate'],3,2);
        if (strlen($start_date) < 8) $start_date = '';		
		$notes = trim(str_replace('\\\'','\'',htmlentities($_POST['notes'])));
		$newperson = new Volunteer($last_name, $first_name, $address, $city, $state, $zip, $clean_phone1, $clean_phone2, $email, $type,
                		$status, $area, $license_no, $license_state, $license_expdate, $accidents,
                		$availability, $schedule, $history, $birthday, $start_date, $notes, $pass,
						$tripCount, $lastTripDates, $volunteerTrainingDate, $driverTrainingDate, $shirtSize, $affiliateId);
        
	//step two: try to make the deletion, password change, addition, or change
		if($_POST['deleteMe']=="DELETE"){
			$result = retrieve_dbVolunteers($id);
			if (!$result)
				echo('<p>Unable to delete. ' .$first_name.' '.$last_name. ' is not in the database. <br>Please report this error to the House Manager.');
			else {
				//What if they're the last remaining manager account?
				if(strpos($type,'coordinator')!==false){
				//They're a manager, we need to check that they can be deleted
					$coordinators = getonlythose_dbVolunteers('', 'coordinator', '', '', ''); 
					if ($id==$_SESSION['_id'] || sizeof($coordinators) <= 1)
						echo('<p class="error">You cannot remove yourself or the last remaining coordinator from the database.</p>');
					else {
						$result = delete_dbVolunteers($id);
						update_volunteers_scheduled($newperson->get_area(), $newperson->get_id(), $newperson->get_availability(),"deleteonly");
						echo("<p>You have successfully removed " .$first_name." ".$last_name. " from the database.</p>");
					}
				}
				else {
					$result = delete_dbVolunteers($id);
					update_volunteers_scheduled($newperson->get_area(), $newperson->get_id(), $newperson->get_availability(),"deleteonly");
					echo("<p>You have successfully removed " .$first_name." ".$last_name. " from the database.</p>");		
				}
			}
		}

		// try to reset the person's password
		else if($_POST['reset_pass']=="RESET"){
				$id = $_POST['old_id'];
				$result = delete_dbVolunteers($id);
				$newperson->set_password (md5($first_name . $clean_phone1));
                $result = insert_dbVolunteers($newperson);
				if (!$result)
                   echo ('<p class="error">Unable to reset ' .$first_name.' '.$last_name. "'s password.. <br>Please report this error to the Program Coordinator.");
				else echo("<p>You have successfully reset " .$first_name." ".$last_name. "'s password.</p>");
		}

		// try to add a new person to the database
		else if ($_POST['old_id']=='new') {
			    $id = $first_name.$clean_phone1;
				//check if there's already an entry
				$dup = retrieve_dbVolunteers($id);
				if ($dup)
					echo('<p class="error">Unable to add ' .$first_name.' '.$last_name. ' to the database. <br>Another person with the same id is already there.');
				else {
					$newperson->set_password (md5($first_name.$clean_phone1));
					$result = insert_dbVolunteers($newperson);
					update_volunteers_scheduled($newperson->get_area(), $newperson->get_id(), $newperson->get_availability(),"deleteandinsert");
					if (!$result)
                        echo ('<p class="error">Unable to add "' .$first_name.' '.$last_name. '" to the database. <br>Please report this error to the Program Coordinator.');
					else if ($_SESSION['access_level']==0)
							 echo("<p>Your application has been successfully submitted.  We will contact you. Thank you!");
						 else echo("<p>You have successfully added " .$first_name." ".$last_name. " to the database.</p>");
				}
		}

		// try to replace an existing person in the database by removing and adding
		else {
			
				$id = $_POST['old_id'];
				$result = delete_dbVolunteers($id);
                if (!$result) { 
                   echo ('<p class="error">Unable to delete ' .$first_name.' '.$last_name. '. <br>Please report this error to the Program Coordinator.');
                }
				else {
					$result = insert_dbVolunteers($newperson);
                	update_volunteers_scheduled($newperson->get_area(), $newperson->get_id(), $newperson->get_availability(),"deleteandinsert");
					
                	if (!$result) {
                   		echo ('<p class="error">Unable to update ' .$first_name.' '.$last_name. '. <br>Please report this error to the Program Coordinator.');
					}
					else echo("<p>You have successfully updated " .$first_name." ".$last_name. " in the database.</p>");
//					add_log_entry('<a href=\"viewPerson.php?id='.$id.'\">'.$first_name.' '.$last_name.'</a>\'s database entry has been updated.');
				}
		}
}
?>
    </div>
    <?PHP include('footer.inc');?>		
  </div>
</body>
</html>
