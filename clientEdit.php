<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/

/*
 *	clientEdit.php
 *  oversees the editing of a client to be added, changed, or deleted from the database
 *	@author Hartley Brody
 *	@version April 4, 2012
 */
	session_start();
	session_cache_expire(30);
    include_once('database/dbClients.php');
    include_once('domain/Client.php');
//    include_once('database/dbLog.php');
	$id = $_GET["id"];
	if ($id=='new') {
	 	$client = new Client(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
	}
	else {
		$client = retrieve_dbClients($id);
		if (!$client) {
	         echo('<p id="error">Error: there\'s no client with this id in the database</p>'. $id);
		     die();
        }  
	}
?>
<html>
	<head>
		<title>
			Editing <?PHP echo($client->get_id());?>
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
<body>
  <div id="container">
    <?PHP include('header.php');?>
	<div id="content">
<?PHP

    include('clientValidate.php');
	if( !array_key_exists('_form_submit', $_POST) )
	//in this case, the form has not been submitted, so show it
		include('clientForm.php');
	else {
	//in this case, the form has been submitted, so validate it
		$errors = validate_form(); 	//step one is validation, "errors" array lists problems on the form submitted
		if ($errors) {
		// display the errors and the form to fix
			show_errors($errors);
			if ($_POST['availability']==null)
			   $avail = "";
			else $avail = implode(',',$_POST['availability']);
			
			if ($_POST['chain_name']=="Wal-Mart") $weight_type="foodtypeboxes";
        	else if ($_POST['chain_name']=="Publix") $weight_type="foodtype";
        	else $weight_type = "pounds";
			
        	$client = new Client($_POST['id'], $_POST['chain_name'], $_POST['area'], $_POST['type'], 
                                 $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'],
								 $_POST['geocoordinates'], $_POST['phone1'], $_POST['phone2'], implode(',',$_POST['days']),
								 $feed_america, $weight_type, $_POST['notes'] );
			include('clientForm.php');
		}
		// this was a successful form submission; update the database and exit
		else
			process_form($id);
		include('footer.inc');
		echo('</div></div></body></html>');
		die();
	}

/**
* process_form sanitizes data, concatenates needed data, and enters it all into a database
*/
function process_form($id)	{
	//step one: sanitize data by replacing HTML entities and escaping the ' character
		$id =           trim(str_replace('\\\'','',htmlentities(str_replace(' ','_',$_POST['id']))));
		$chain_name =   trim(str_replace('\\\'','\'',htmlentities($_POST['chain_name'])));
		$address =      trim(str_replace('\\\'','\'',htmlentities($_POST['address'])));
		$city =         trim(str_replace('\\\'','\'',htmlentities($_POST['city'])));
		$state =        trim(htmlentities($_POST['state']));
		$zip =          trim(htmlentities($_POST['zip']));
        
		$phone1 = trim(str_replace(' ','',htmlentities($_POST['phone1'])));
		$clean_phone1 = preg_replace("/[^0-9]/", "", $phone1);
		$phone2 = trim(str_replace(' ','',htmlentities($_POST['phone2'])));
		$clean_phone2 = preg_replace("/[^0-9]/", "", $phone2);
			
        $type = $_POST['type'];
        $area = $_POST['area'];
        if ($_POST['days'])
        	$days=implode(',', $_POST['days']);
        else $days="";
        if ($chain_name=="Wal-Mart") $weight_type="foodtypeboxes";
        else if ($chain_name=="Publix") $weight_type="foodtype";
        else $weight_type = "pounds"; 
        
        $notes = $_POST['notes'];
        
        // if ($_POST['availability'] != null)
			// $availability=implode(',', $_POST['availability']);
		// else $availability = "";

        //step two: try to make the deletion, addition, or change
		if($_POST['deleteMe']=="DELETE"){
			$result = retrieve_dbClients($id);
			if (!$result)
				echo('<p>Unable to delete. ' . $id . ' is not in the database. <br>Please report this error to the House Manager.');
			else {
                $result = delete_dbVolunteers($id);
                echo("<p>You have successfully removed " . $id . " from the database.</p>");
					
            }
		}


		// try to add a new client to the database
		else if ($_POST['old_id']=='new') {
				//check if there's already an entry
				$dup = retrieve_dbClients($id);
				if ($dup)
					echo('<p class="error">Unable to add ' . $id . ' to the database. <br>Another client with the same id is already there.');
				else {
					$newperson = new Client($id, $chain_name, $area, $type, $address, $city, $state, $zip, $geocoordinates,
	                        $phone1, $phone2, $days, $feed_america, $weight_type, $notes);
                    $result = insert_dbClients($newperson);
					if (!$result)
                        echo ('<p class="error">Unable to add '. $id . ' in the database. <br>Please report this error to the Program Coordinator.');
				}
		}

		// try to replace an existing client in the database by removing and adding
		else {
				$id = $_POST['old_id'];
				$result = delete_dbClients($id);
                if (!$result)
                   echo ('<p class="error">Unable to update ' .$id. '. <br>Please report this error to the Program Coordinator.');
				else {
					$newperson = new Client($id, $chain_name, $area, $type, $address, $city, $state, $zip, $geocoordinates,
	                        $phone1, $phone2, $days, $feed_america, $weight_type, $notes);
                	$result = insert_dbClients($newperson);
					if (!$result)
                   		echo ('<p class="error">Unable to update ' .$id. '. <br>Please report this error to the Program Coordinator.');
					else echo("<p>You have successfully updated " .$id. " in the database.</p>");
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
