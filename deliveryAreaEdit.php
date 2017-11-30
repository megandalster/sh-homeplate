<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/

/*
 *	affiliateEdit.php
 *  oversees the editing of a volunteer to be added, changed, or deleted from the database
 *	@author Allen Tucker
 *	@version April 1, 2012
 */
	session_start();
	session_cache_expire(30);		
	 include_once('database/dbDeliveryAreas.php');
    include_once('domain/DeliveryArea.php');
    
	include_once('database/dbClients.php');
    include_once('domain/Client.php');
	
//    include_once('database/dbLog.php');
	$id = $_GET["id"];
	if ($id=='new') {
	 	$deliveryArea = new DeliveryArea(null,'new');
	}
	else {
		$deliveryArea = retrieve_dbDeliveryAreas($id);
		if (!$deliveryArea) {
	         echo('<p id="error">Error: there\'s no area with this id in the database</p>'. $id);
		     die();
        }  
	}
?>
<html>
	<head>
		<title>
			Editing <?PHP echo($deliveryArea->get_deliveryAreaName());?>
		</title>
		
		 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
<body>
  <div id="container">
    <?PHP include('header.php');?>
	<div id="content">
<?PHP
	
	if($_POST['_form_submit']!=1)
	//in this case, the form has not been submitted, so show it
		include('deliveryAreaForm.inc');
	else {
	//in this case, the form has been submitted, so validate it
		
		/*
		if ($id=='new') {
				$deliveryAreaName = trim($_POST['deliveryAreaName']);
				
		}
		else {
				$deliveryAreaName = $deliveryArea->get_deliveryAreaName();

		}
		*/
		$deliveryAreaName = trim($_POST['deliveryAreaName']);
		
		$deliveryArea = new DeliveryArea($_POST['deliveryAreaId'], $deliveryAreaName);
		//$errors = validate_form($id); 	//step one is validation.
									// errors array lists problems on the form submitted
		if ($errors) {
		// display the errors and the form to fix
			show_errors($errors);
			include('deliveryAreaForm.inc');
		}
		// this was a successful form submission; update the database and exit
		else
			process_form($id, $deliveryArea);
		include('footer.inc');
		echo('</div></div></body></html>');
		die();
	}
	
/**
* process_form sanitizes data, concatenates needed data, and enters it all into a database
*/
function process_form($id, $deliveryArea)	{
	//step one: sanitize data by replacing HTML entities and escaping the ' character

		$deliveryAreaName = trim(str_replace('\\\'','\'',htmlentities($_POST['deliveryAreaName'])));
		
		$newDeliveryArea = new DeliveryArea($id, $deliveryAreaName);
        
	//step two: try to make the deletion, password change, addition, or change
		if($_POST['deleteMe']=="DELETE"){
			$result = retrieve_dbDeliveryAreas($id);
			if (!$result)
				echo('<p>Unable to delete. ' .$deliveryAreaName. ' is not in the database. <br>Please report this error to the House Manager.');
			else {
				
					$result = delete_dbDeliveryAreas($id);
					
					echo("<p>You have successfully removed " .$deliveryAreaName. " from the database.</p>");		
				
			}
		}

		// try to add a new person to the database
		else if ($_POST['old_id']=='new') {
			 
					
					$result = insert_dbDeliveryAreas($newDeliveryArea);
					
					if (!$result)
                        echo ('<p class="error">Unable to add "' .$deliveryAreaName. '" to the database. <br>Please report this error to the Program Coordinator.');
					else echo("<p>You have successfully added " .$deliveryAreaName. " to the database.</p>");
				
		}

		// try to replace an existing person in the database by removing and adding
		else {
				$id = $_POST['old_id'];
				$result = delete_dbDeliveryAreas($id);
                if (!$result)
                   echo ('<p class="error">Unable to update ' .$deliveryAreaName. '. <br>Please report this error to the Program Coordinator.');
				else {
					$result = insert_dbDeliveryAreas($newDeliveryArea);
                	
                	if (!$result)
                   		echo ('<p class="error">Unable to update ' .$deliveryAreaName. '. <br>Please report this error to the Program Coordinator.');
					else echo("<p>You have successfully updated " .$deliveryAreaName. " in the database.</p>");
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
