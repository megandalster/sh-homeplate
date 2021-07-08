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
	
	include_once('database/dbDeliveryAreas.php');
    include_once('domain/DeliveryArea.php');
//    include_once('database/dbLog.php');
	$id = $_GET["id"];
	$chain_name = "";
	if ($id=='new') {
	 	$client = new Client(null,null,null,null,null,null,null,null,null,null,null,
	 			             null,null,null,null,null,null,null,null,null,null,null,
	 			             null,null,null,null,null,null,null,null,null,null,array(0,0,0),"active");
	}
	else {
		$client = retrieve_dbClients($id);
		if (!$client) {
	         echo('<p id="error">Error: there\'s no client with this id in the database</p>'. $id);
		     die();
        }
        $chain_name = $client->get_chain_name();  
	}
?>
<html>
	<head>
		<title>
			Editing Client
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
	include('clientValidate.php');
	if( !array_key_exists('_form_submit', $_POST) )
	//in this case, the form has not been submitted, so show it
		include('clientForm.php');
	else {
	//in this case, the form has been submitted, so validate it
		$errors = validate_form($id); 	//step one is validation, "errors" array lists problems on the form submitted
		if ($errors) {
		// display the errors and the form to fix
			show_errors($errors);
			if ($_POST['availability']==null)
			   $avail = "";
			else $avail = implode(',',$_POST['availability']);
			$old_id = $id;		
			if ($id=="new"){
				$id = trim(str_replace('\\\'','',htmlentities(str_replace('&','and',str_replace('#',' ',$_POST['id'])))));
				$chain_name =   $_POST['chain_name'];
				if($_POST['type'] == "donor"){
					$weight_type="foodtype";
				}
				else{
					$weight_type = "pounds";
				}
			}
        	$client = new Client($id, $_POST['chain_name'], $_POST['area'], $_POST['type'], 
                                 $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['county'], $_POST['phone1'], 
        						 $_POST['address2'], $_POST['city2'], $_POST['state2'], $_POST['zip2'], $_POST['county2'], $_POST['phone2'], 
        	                     implode(',',$_POST['daysHHI']), implode(',',$_POST['daysSUN']), implode(',',$_POST['daysBFT']), 
        	                     $lcfb, $chartrkr, $weight_type, $_POST['notes'], $_POST['email'],$_POST['email2'],$_POST['ContactName'], 
        	                     $_POST['ContactName2'], $_POST['deliveryAreaId'],$_POST['survey_date'], $_POST['visit_date'], $_POST['foodsafe_date'], 
        	    $_POST['pestctrl_date'],array($_POST['number_served'],$_POST['children_served'],$_POST['seniors_served']),$_POST['status']);
			$id = $old_id;
			include('clientForm.php');
		}
		// this was a successful form submission; update the database and exit
		else
			process_form($id);
		echo('</div>');
		include('footer.inc');
		echo('</div></body></html>');
		die();
	}

/**
* process_form sanitizes data, concatenates needed data, and enters it all into a database
*/
function process_form($id)	{
	//step one: sanitize data by replacing HTML entities and escaping the ' character
	if ($id=="new"){
		$id = trim(str_replace('\\\'','',htmlentities(str_replace('&','and',str_replace('#',' ',$_POST['id'])))));
	}
		$chain_name =    trim(str_replace('\\\'','\'',htmlentities($_POST['chain_name'])));
		$address =      trim(str_replace('\\\'','\'',htmlentities($_POST['address'])));
		$city =         trim(str_replace('\\\'','\'',htmlentities($_POST['city'])));
		$state =        trim(htmlentities($_POST['state']));
		$zip =          trim(htmlentities($_POST['zip']));
		$county =          trim(htmlentities($_POST['county']));
		$county2 = $county;  // assume both contacts are in the same county
        $email =          trim(htmlentities($_POST['email']));
		$phone1 = trim(str_replace(' ','',htmlentities($_POST['phone1'])));
		$clean_phone1 = preg_replace("/[^0-9]/", "", $phone1);
		$address2 =      trim(str_replace('\\\'','\'',htmlentities($_POST['address2'])));
		$city2 =         trim(str_replace('\\\'','\'',htmlentities($_POST['city2'])));
		$state2 =        trim(htmlentities($_POST['state2']));
		$zip2 =          trim(htmlentities($_POST['zip2']));
        $email2 =          trim(htmlentities($_POST['email2']));
		$phone2 = trim(str_replace(' ','',htmlentities($_POST['phone2'])));
		$clean_phone2 = preg_replace("/[^0-9]/", "", $phone2);
		$ContactName = trim(htmlentities($_POST['ContactName']));
		$ContactName2 = trim(htmlentities($_POST['ContactName2']));
		$deliveryAreaId = $_POST['deliveryAreaId'];
		$survey_date = substr($_POST['survey_date'],8,2)."-".substr($_POST['survey_date'],0,2)."-".substr($_POST['survey_date'],3,2);
		if (strlen($survey_date) < 8) $survey_date = '';
		$visit_date = substr($_POST['visit_date'],8,2)."-".substr($_POST['visit_date'],0,2)."-".substr($_POST['visit_date'],3,2);
		if (strlen($visit_date) < 8) $visit_date = '';
		$foodsafe_date = substr($_POST['foodsafe_date'],8,2)."-".substr($_POST['foodsafe_date'],0,2)."-".substr($_POST['foodsafe_date'],3,2);
		if (strlen($foodsafe_date) < 8) $foodsafe_date = '';
		$pestctrl_date = substr($_POST['pestctrl_date'],8,2)."-".substr($_POST['pestctrl_date'],0,2)."-".substr($_POST['pestctrl_date'],3,2);
		if (strlen($pestctrl_date) < 8) $pestctrl_date = '';
		$number_served = $_POST['number_served'];
		$children_served = $_POST['children_served'];
		$seniors_served = $_POST['seniors_served'];
        $type = $_POST['type'];
        $area = $_POST['area'];
        if ($_POST['daysHHI'])
        	$daysHHI=implode(',', $_POST['daysHHI']);
        else $daysHHI="";
        if ($_POST['daysSUN'])
            $daysSUN=implode(',', $_POST['daysSUN']);
        else $daysSUN="";
        if ($_POST['daysBFT'])
            $daysBFT=implode(',', $_POST['daysBFT']);
        else $daysBFT="";
        if($type == "donor"){
			$weight_type="foodtype";
		}
		else{
			$weight_type = "pounds"; 
		}
		$lcfb = $_POST['lcfb'];
		$chartrkr = $_POST['chartrkr'];
        $notes = $_POST['notes'];
        $status = $_POST['status'];

        //step two: try to make the deletion, addition, or change
		if($_POST['deleteMe']=="DELETE"){
			$result = retrieve_dbClients($id);
			if (!$result)
				echo('<p>Unable to delete. ' . $id . ' is not in the database. <br>Please report this error to the Program Coordinator.');
			else {
                $result = delete_dbClients($id);
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
					$newperson = new Client($id, $chain_name, $area, $type, $address, $city, $state, $zip, $county, $phone1, 
	                        $address2, $city2, $state2, $zip2, $county2, $phone2, $daysHHI, $daysSUN, $daysBFT, $lcfb, $chartrkr, $weight_type, $notes, 
							$email, $email2, $ContactName, $ContactName2, $deliveryAreaId, $survey_date, $visit_date, 
							$foodsafe_date, $pestctrl_date, array($number_served,$children_served,$seniors_served),$status);
                    $result = insert_dbClients($newperson);
					if (!$result)
                        echo ('<p class="error">Unable to add '. $id . ' in the database. <br>Please report this error to the Program Coordinator.');
					else echo("<p>You have successfully added " .$id. " to the database.</p>");
				}
		}

		// try to replace an existing client in the database by removing and adding
		else {
				$id = $_POST['old_id'];
				$chain_name = $_POST['chain_name'];
				if($type == "donor"){
					$weight_type="foodtype";
				}
				else{
					$weight_type = "pounds"; 
				}
				$newperson = new Client($id, $chain_name, $area, $type, $address, $city, $state, $zip, $county, $phone1, 
				    $address2, $city2, $state2, $zip2, $county2, $phone2, $daysHHI, $daysSUN, $daysBFT, $lcfb, $chartrkr, $weight_type, $notes, 
							$email, $email2, $ContactName, $ContactName2, $deliveryAreaId, $survey_date, $visit_date, 
						    $foodsafe_date, $pestctrl_date, array($number_served,$children_served,$seniors_served),$status);
				$result = insert_dbClients($newperson);
                if (!$result)
                   	echo ('<p class="error">Unable to update ' .$id. '. <br>Please report this error to the Program Coordinator.');
				else echo("<p>You have successfully updated " .$id. " in the database.</p>");
		}
}
?>
    </div>
    <?PHP include('footer.inc');?>		
  </div>
</body>
</html>
