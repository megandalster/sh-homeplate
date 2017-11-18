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
	 include_once('database/dbAffiliates.php');
    include_once('domain/Affiliate.php');
    
	include_once('database/dbVolunteers.php');
    include_once('domain/Volunteer.php');
	
//    include_once('database/dbLog.php');
	$id = $_GET["id"];
	if ($id=='new') {
	 	$affiliate = new Affiliate(null,'new');
	}
	else {
		$affiliate = retrieve_dbAffiliates($id);
		if (!$affiliate) {
	         echo('<p id="error">Error: there\'s no affiliate with this id in the database</p>'. $id);
		     die();
        }  
	}
?>
<html>
	<head>
		<title>
			Editing <?PHP echo($affiliate->get_affiliateName());?>
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
		include('affiliateForm.inc');
	else {
	//in this case, the form has been submitted, so validate it
		
		/*
		if ($id=='new') {
				$affiliateName = trim($_POST['affiliateName']);
				
		}
		else {
				$affiliateName = $affiliate->get_affiliateName();

		}
		*/
		$affiliateName = trim($_POST['affiliateName']);
		
		$affiliate = new Affiliate($_POST['affiliateId'], $affiliateName);
		//$errors = validate_form($id); 	//step one is validation.
									// errors array lists problems on the form submitted
		if ($errors) {
		// display the errors and the form to fix
			show_errors($errors);
			include('affiliateForm.inc');
		}
		// this was a successful form submission; update the database and exit
		else
			process_form($id, $affiliate);
		include('footer.inc');
		echo('</div></div></body></html>');
		die();
	}
	
/**
* process_form sanitizes data, concatenates needed data, and enters it all into a database
*/
function process_form($id, $affiliate)	{
	//step one: sanitize data by replacing HTML entities and escaping the ' character

		$affiliateName = trim(str_replace('\\\'','\'',htmlentities($_POST['affiliateName'])));
		
		$newAffiliate = new Affiliate($id, $affiliateName);
        
	//step two: try to make the deletion, password change, addition, or change
		if($_POST['deleteMe']=="DELETE"){
			$result = retrieve_dbAffiliates($id);
			if (!$result)
				echo('<p>Unable to delete. ' .$affiliateName. ' is not in the database. <br>Please report this error to the House Manager.');
			else {
				
					$result = delete_dbAffiliates($id);
					
					echo("<p>You have successfully removed " .$affiliateName. " from the database.</p>");		
				
			}
		}

		// try to add a new person to the database
		else if ($_POST['old_id']=='new') {
			 
					
					$result = insert_dbAffiliates($newAffiliate);
					
					if (!$result)
                        echo ('<p class="error">Unable to add "' .$affiliateName. '" to the database. <br>Please report this error to the Program Coordinator.');
					else echo("<p>You have successfully added " .$affiliateName. " to the database.</p>");
				
		}

		// try to replace an existing person in the database by removing and adding
		else {
				$id = $_POST['old_id'];
				$result = delete_dbAffiliates($id);
                if (!$result)
                   echo ('<p class="error">Unable to update ' .$affiliateName. '. <br>Please report this error to the Program Coordinator.');
				else {
					$result = insert_dbAffiliates($newAffiliate);
                	
                	if (!$result)
                   		echo ('<p class="error">Unable to update ' .$affiliateName. '. <br>Please report this error to the Program Coordinator.');
					else echo("<p>You have successfully updated " .$affiliateName. " in the database.</p>");
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
