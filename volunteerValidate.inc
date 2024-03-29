<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/

function validate_form($id){
    $errors = [];
	if(($id=='new' || $_SESSION['access_level']>=2) && ($_POST['first_name']==null || $_POST['first_name']=='new')) $errors[] = 'Please enter a first name';
	if(($id=='new' || $_SESSION['access_level']>=2) && $_POST['last_name']==null) $errors[] = 'Please enter a last name';
	if(($id=='new' || $_SESSION['access_level']>=2) && !valid_phone($_POST['first_name'],$_POST['phone1'])) $errors[] = 'Please enter a valid primary phone number (10 digits: ###-###-####)'.$id;
	if($_POST['city']==null) $errors[] = 'Please enter a city';
	if($_POST['address']==null) $errors[] = 'Please enter an address';
	if($_POST['address']==null) $errors[] = 'Please enter an address';
	if(($_POST['zip'] != strval(intval($_POST['zip']))) || ($_POST['zip']==null) || (strlen($_POST['zip'])!=5)) $errors[] = 'Please enter a valid zip code';
	if ($_SESSION['access_level']==0 && ($_POST['type']==null || !in_array('applicant',$_POST['type'])))
		$_POST['type'][] = 'applicant';
	if($_POST['area']==null && $_SESSION['access_level']>=1) $errors[] = 'Please select a Base';
	if($_POST['type']==null && $_SESSION['access_level']>=1) $errors[] = 'Please select a Role';
	if ($_POST['type']!=null && $_SESSION['access_level']<=1 && (in_array('teamcaptain',$_POST['type']) || in_array('boardmember',$_POST['type']) || in_array('coordinator',$_POST['type'])))
		$errors[] = "Sorry, you can't promote yourself to a higher role.";
	if($_POST['phone2']!=null && !valid_phone($id,$_POST['phone2'])) $errors[] = 'Please enter a valid secondary phone number (10 digits: ###-###-####)';
	if(!valid_email($_POST['email']) && $_POST['email']!=null) $errors[] = "Please enter a valid email";
    if(($_POST['birthday_Month']!=="" || $_POST['birthday_Day']!=="") && $_POST['birthday_Year']==="")
        $birthday = "XX-".$_POST['birthday_Month'].'-'.$_POST['birthday_Day'];
    else
        $birthday = $_POST['birthday_Year'].'-'.$_POST['birthday_Month'].'-'.$_POST['birthday_Day'];
    if($birthday=='--' || $birthday=='XX--') $errors[] = 'Please enter a birthday';
	if (strlen($birthday)>2 && strlen($birthday)<8) $errors[] = 'Please select a valid birthday';
    
    $license_no = $_POST['license_no'];
    $license_state = $_POST['license_state'];
    $license_expdate = $_POST['expdate_Year'].'-'.$_POST['expdate_Month'].'-'.$_POST['expdate_Day'];
    if (strlen($license_expdate) < 8) $license_expdate = null;
    
    if($license_no==null) $errors[] = 'Please enter a License No';
    if($license_state==null) $errors[] = 'Please enter a License State';
    if($license_expdate==null) $errors[] = 'Please enter a License Exp Date';
    
    return $errors;
}


/**
* valid_phone validates a phone on the following parameters:
* 		it assumes the characters '-' ' ' '+' '(' and ')' are valid, but ignores them
*		every other digit must be a number
*		it should be between 7 and 11 digits
* returns boolean if phone is valid
*/
function valid_phone($id,$phone){
        $truckids = array("HiltonHe","Bluffton","Beaufort");
        if (in_array(substr($id,0,8),$truckids) && $phone=="") return true;  // phone can be null for crew logins
		if($phone==null) return false;
		$phone = str_replace(' ','',str_replace('+','',str_replace('(','',str_replace(')','',str_replace('-','',$phone)))));
		$test = str_replace('0','',str_replace('1','',str_replace('2','',str_replace('3','',str_replace('4','',str_replace('5','',str_replace('6','',str_replace('7','',str_replace('8','',str_replace('9','',$phone))))))))));
		if($test != null) return false;
		if ( (strlen($phone)) != 10) return false;
		return true;
}

//Function from <http://www.phpit.net/code/valid-email/>
function valid_email($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9!#$%&#038;'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
				return false;
			}
		}
		if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
}

function show_errors($e){
		//this function should display all of our errors.
		echo('<div class="warning">');
		echo('<ul>');
		foreach($e as $error){
			echo("<li><strong><font color=\"red\">".$error."</font></strong></li>\n");
		}
		echo("</ul></div></p>");
}
?>