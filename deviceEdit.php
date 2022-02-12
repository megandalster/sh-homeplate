<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/

/*
 *	deviceEdit.php
 *  oversees the editing of a device to be added, changed, or deleted from the database
 *	@author Allen Tucker
 *	@version February 18, 2018
 */
	session_start();
	//session_cache_expire(30);
    include_once('database/dbDevices.php');
    include_once('domain/Device.php');
	
	$id = $_GET["id"];
	if ($id=='new') {
	 	$device = new Device(null,null,null,null,null,null,null);
	}
	else {
		$device = retrieve_dbDevices($id);
		if (!$device) {
	         echo('<p id="error">Error: there\'s no device with this id in the database</p>'. $id);
		     die();
        }  
	}
?>
<html>
	<head>
		<title>
			Editing device
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
	if( !array_key_exists('_form_submit', $_POST) ) {
	//in this case, the form has not been submitted, so show it
		include('deviceForm.php');
	}
	else {
	//in this case, the form has been submitted, so validate it
		$old_id=$id;
		if ($old_id=="new"){
			$id = trim(str_replace('\\\'','',htmlentities(str_replace('&','and',str_replace('#',' ',$_POST['id'])))));
			$id_retype = trim(str_replace('\\\'','',htmlentities(str_replace('&','and',str_replace('#',' ',$_POST['id_retype'])))));
		}
		else $id_retype="";
		$status =    $_POST['status'];
		$base =      $_POST['base'];
		$owner =     $_POST['owner'];
		$date_activated =  $_POST['date_activated'];
		$last_used =  $_POST['last_used'];
		$notes = $_POST['notes'];
		//step one is validation, "errors" array lists problems on the form submitted
		$errors = validate_form($id,$old_id,$id_retype,$date_activated,$status); 	
		if (sizeof($errors)>0) {
		// display the errors and the form to fix
			show_errors($errors);
			if ($old_id=="new")
				$id = "new";
        	$device = new Device($id, $_POST['status'], $_POST['base'], 
        			$_POST['owner'], $_POST['date_activated'], $_POST['last_used'], $_POST['notes']);
			include('deviceForm.php');
		}
		// this was a successful form submission; update the database and exit
		else
		    process_form($id,$old_id, $status, $base, $owner, $date_activated, $last_used, $notes);
		echo('</div>');
		include('footer.inc');
		echo('</div></body></html>');
		die();
	}

/**
* process_form sanitizes data, concatenates needed data, and enters it all into a database
*/
	function process_form($id,$old_id, $status, $base, $owner, $date_activated, $last_used, $notes)	{

        //step two: try to make the deletion, addition, or change
		if($_POST['deleteMe']=="DELETE"){
			$result = retrieve_dbDevices($id);
			if (!$result)
				echo('<p>Unable to delete. ' . $id . ' is not in the database. <br>Please report this error to the Program Coordinator.');
			else {
                $result = delete_dbDevices($id);
                echo("<p>You have successfully removed " . $id . " from the database.</p>");
					
            }
		}

		// try to add a new Device to the database
		else if ($old_id=='new') {
				//check if there's already an entry
				$dup = retrieve_dbDevices($id);
				if ($dup)
					echo('<p class="error">Unable to add ' . $id . ' to the database. <br>Another device with the same id is already there.');
				else {
					$newdevice = new Device($id, $status, $base, $owner, $date_activated, $last_used, $notes);
                    $result = insert_dbDevices($newdevice);
					if (!$result)
                        echo ('<p class="error">Unable to add '. $id . ' in the database. <br>Please report this error to the Program Coordinator.');
					else echo("<p>You have successfully added " .$id. " to the database.</p>");
				}
		}

		// try to replace an existing device in the database by removing and adding
		else {
			$newdevice = new Device($id, $status, $base, $owner, $date_activated, $last_used, $notes);
				$result = insert_dbDevices($newdevice);
                if (!$result)
                   	echo ('<p class="error">Unable to update ' .$id. '. <br>Please report this error to the Program Coordinator.');
				else echo("<p>You have successfully updated " .$id. " in the database.</p>");
		}
}
function validate_form($id,$old_id,$id_retype,$date_activated,$status){
	$errors = array();
	if($old_id=="new"){
		if (!$id || trim($id)=="" || 
		    strlen($id)!=16 || $id != strtolower($id))    
		        $errors[] = 'Please enter a valid device id -- 16 characters and NO caps';
		if ($id!=$id_retype) 
		    $errors[] = "id and retype do not match";
		if (!$date_activated || trim($date_activated)=="")  
		    $errors[] = 'Please enter a date activated';
		if ($status=="")
		    $errors[] = "Please select a status";
	}
	return $errors;
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
    </div>
    <?PHP include('footer.inc');?>		
  </div>
</body>
</html>
