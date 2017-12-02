<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/

function validate_form($id){
    $errors = array();
	if($id=="new" && (!$_POST['id'] || trim($_POST['id'])==""))    $errors[] = 'Please enter a name';
	if($_POST['address']==null)             $errors[] = 'Please enter an address';
	if($_POST['city']==null)                $errors[] = 'Please enter a city';
	if($_POST['state']==null)               $errors[] = 'Please enter a state';
	if($_POST['zip'] != null && strlen($_POST['zip'])!=5) $errors[] = 'Please enter a valid zip code';
	if($_POST['area']==null)               $errors[] = 'Please enter an Area';
	//if($_POST['days']==null)    		    $errors[] = 'Must have some availabilty';
	if($_POST['phone1']!=null && !valid_phone($_POST['phone1'])) $errors[] = 'Enter a valid food contact phone number (7 or 10 digits)';
	if($_POST['phone2']!=null && !valid_phone($_POST['phone2'])) $errors[] = 'Enter a valid admin contact phone number (7 or 10 digits)';
    
    if(!$errors)
        return "";
    else
        return $errors;
}


/**
* valid_phone validates a phone on the following parameters:
* 		it assumes the characters '-' ' ' '+' '(' and ')' are valid, but ignores them
*		every other digit must be a number
*		it should be between 7 and 11 digits
* returns boolean if phone is valid
*/
function valid_phone($phone){
		if($phone==null) return false;
		$phone = str_replace(' ','',str_replace('+','',str_replace('(','',str_replace(')','',str_replace('-','',$phone)))));
		$test = str_replace('0','',str_replace('1','',str_replace('2','',str_replace('3','',str_replace('4','',str_replace('5','',str_replace('6','',str_replace('7','',str_replace('8','',str_replace('9','',$phone))))))))));
		if($test != null) return false;
		if (strlen($phone) != 7 && strlen($phone) != 10) return false;
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