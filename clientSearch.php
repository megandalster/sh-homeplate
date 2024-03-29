<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/
	session_start();
	//session_cache_expire(30);
	
	include_once('database/dbDeliveryAreas.php');
	include_once('database/dbClients.php');
?>
<html>
	<head>
		<title>
			Search for Clients
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>	
	    <script type="text/javascript">
			function showPrintWindow(){
				
				var printWin = window.open('', 'winReport', 'width=690px;height:600px;resizable=1');
				var html = $("#tblReport").parent().html();
				
				printWin.document.open();
				printWin.document.write("<html><head><title>Print Donor/Recipients</title><style>#tblReport td {border:1px solid black;}</style></head><body>");
				printWin.document.writeln(html);
				printWin.document.write('<scr');
				printWin.document.write('ipt>');
				printWin.document.writeln('setTimeout("window.print()", 200);');
				printWin.document.write('</scr');
				printWin.document.write('ipt>');
				printWin.document.write('</body>');
				printWin.document.write('</ht>');   
				printWin.document.write('<ml>');
				printWin.document.close();
				
			}
		</script>
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
				
				// display the search form
					$area = $_GET['area'] ?? '';
					$deliveryAreaId = $_GET['deliveryAreaId'] ?? '';
					$deliveryAreas = getall_dbDeliveryAreas();
					$counties = array("Beaufort", "Jasper", "Hampton");
					$areas = array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort");
					$days = array("Mon"=>"Monday","Tue"=>"Tuesday","Wed"=>"Wednesday","Thu"=>"Thursday","Fri"=>"Friday","Sat"=>"Saturday","Sun"=>"Sunday");
					echo('<p><a href="'.$path.'clientEdit.php?id=new">Add new donor or recipient</a>');	
					echo('<form method="post">');
					echo('<p><strong>Search for donors and recipients:</strong>');
                    
                    $area = "";
                    if( array_key_exists('s_area', $_POST) ) 
                    	$area = $_POST['s_area']; //override the GET variable if we just conducted a search
					echo '<p>Base: <select name="s_area">' .
							'<option value="">--all--</option>'; 
                        echo '<option value="HHI"'; if ($area=="HHI") echo " SELECTED"; echo '>Hilton Head</option>' ;
                        echo '<option value="SUN"'; if ($area=="SUN") echo " SELECTED"; echo '>Bluffton</option>' ;
                        echo '<option value="BFT"'; if ($area=="BFT") echo " SELECTED"; echo '>Beaufort</option>';
					echo '</select>';
					
                    $type = "";
					if( array_key_exists('s_type', $_POST) )
                    	$type = $_POST['s_type'];
					echo '&nbsp;&nbsp;Donor/Recipient:<select name="s_type">';
                        echo '<option value=""';            if ($type=="")          echo " SELECTED"; echo '>--all--</option>'; 
                        echo '<option value="donor"';       if ($type=="donor")     echo " SELECTED"; echo '>Donor</option>'; 
                        echo '<option value="recipient"';   if ($type=="recipient") echo " SELECTED"; echo '>Recipient</option>'; 
                    echo '</select>';
                    
                    $status = "active";
                    if( array_key_exists('status', $_POST) )
                        $status = $_POST['status'];
                    
                    echo '&nbsp;&nbsp;Status:<select name="status">';
                    echo '<option value="active"';       if ($status=="active")     echo " SELECTED"; echo '>active</option>';
                    echo '<option value="inactive"';       if ($status=="inactive")     echo " SELECTED"; echo '>inactive</option>';
                    echo '<option value="former"';   if ($status=="former") echo " SELECTED"; echo '>former</option>';
                    echo '<option value=""';            if ($status=="")          echo " SELECTED"; echo '>--all--</option>';
                    echo '</select>';
                        
                    $county="";
                    if( array_key_exists('s_county', $_POST) ) 
                    	$county = $_POST['s_county']; //override the GET variable if we just conducted a search
					echo '<p>County: <select name="s_county">' .
							'<option value="">--all--</option>'; 
                        echo '<option value="Beaufort"'; if ($county=="Beaufort") echo " SELECTED"; echo '>Beaufort</option>' ;
                        echo '<option value="Hampton"'; if ($county=="Hampton") echo " SELECTED"; echo '>Hampton</option>' ;
                        echo '<option value="Jasper"'; if ($county=="Jasper") echo " SELECTED"; echo '>Jasper</option>';
					echo '</select>';
					
					echo '&nbsp;&nbsp;Pickup/Delivery Area:';
					echo('<select name="s_deliveryAreaId">');
						echo ('<option value="--all--" SELECTED>--all--</option>');
						foreach($deliveryAreas as $deliveryArea){
							echo ('<option value="'); 
							echo($deliveryArea->get_deliveryAreaId()); 
							echo('"');
							if ($deliveryAreaId==$deliveryArea->get_deliveryAreaId()) //if( !array_key_exists('deliveryAreaId', $_POST) ) 
								echo (' SELECTED');
							echo('>'); 
							echo($deliveryArea->get_deliveryAreaName()); 
							echo('</option>');
						}
					echo('</select>');
                    
                    $noso = "";
					if( array_key_exists('noso', $_POST) )
					    $noso = $_POST['noso']; //override the GET variable if we just conducted a search
                    echo '&nbsp;&nbsp;No/So: <select name="noso">' .
                                    '<option value="">--all--</option>';
                    echo '<option value="North"'; if ($noso=="North") echo " SELECTED"; echo '>North of the Broad</option>' ;
                    echo '<option value="South"'; if ($noso=="South") echo " SELECTED"; echo '>South of the Broad</option>' ;
                    echo '</select>';
                
                
                    $lcfb = "";
                    if( array_key_exists('s_lcfb', $_POST) )
                        $lcfb = $_POST['s_lcfb'];
                    
					echo '&nbsp;&nbsp;LCFB:<select name="s_lcfb">';
                        echo '<option value=""';    if ($lcfb=="")    echo " SELECTED"; echo '>--all--</option>';
                        echo '<option value="yes"'; if ($lcfb=="yes") echo " SELECTED"; echo '>Yes</option>';
                        echo '<option value="no"';  if ($lcfb=="no")  echo " SELECTED"; echo '>No</option>';
                    echo '</select>';
                
                    $name = "";
                    if( array_key_exists('s_name', $_POST) )
                       $name = $_POST['s_name'];
					echo '&nbsp;&nbsp;Name: ' ;
					echo '<input type="text" name="s_name" value="' . $name . '">';
					
					echo('<p><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search">');
					echo('</form></p>');    
					
				// if user hit "Search"  button, query the database and display the results
				if( array_key_exists('s_submitted', $_POST) ){
						if ($_POST['s_area']=="--all--")
							$area = "";
						else $area = $_POST['s_area'];
						$type = $_POST['s_type'];
						$status = $_POST['status'];
						$noso = $_POST['noso'];
                        $name = trim(str_replace('\'','&#39;',htmlentities($_POST['s_name'])));
                        
                        $availability = array();
                        if ( !array_key_exists('s_day', $_POST) ) 
                            $_POST['s_day'][] = ""; // allow "any" day if none checked
                        foreach ($_POST['s_day'] as $day) 
                            $availability[] = $day;
							
						if ($_POST['s_deliveryAreaId']=="--all--") 
							$deliveryAreaId = "";
						else $deliveryAreaId = $_POST['s_deliveryAreaId'];
						if ($_POST['s_county']=="--all--")
							$county = "";
						else $county = $_POST['s_county'];
						
     					//echo "search criteria: ", $area.$type.$status.$name.$availability[0].$deliveryAreaId;
                        
                        // now go after the clients that fit the search criteria
                        include_once('database/dbDeliveryAreas.php');
                        include_once('database/dbClients.php');
     					include_once('domain/Client.php');
						
     					$result = getall_clients($area, $type, $lcfb, $name, "","","", $deliveryAreaId, $county, $status, $noso);
						
                        echo '<p><strong>Search Results:</strong> <p>Found ' . sizeof($result). ' ';
                            if (!$type) echo $status . " client(s)"; 
                            else echo $status ." ". $type.'s';
						if ($areas[$area]!="") echo ' from '.$areas[$area];
						if ($noso) echo " (".$noso." of the Broad)";
						if ($name!="") echo ' with name like "'.$name.'"';
				
			     		if ($county!="") echo ' in county: '.$county;
						if ($deliveryAreaId !="") echo ' in delivery area: '.retrieve_dbDeliveryAreas($deliveryAreaId)->get_deliveryAreaName();
						if ($lcfb!="") echo ' with LCFB =  '.$lcfb;
						if (sizeof($result)>0) {
							echo '. Select one for more info.';
							echo '<div>';
							echo '<table id="tblReport"> <tr><td><strong>Name</strong></td>';
							echo '<td><strong>Contact (F/A) </strong></td><td><strong>Phone</strong></td><td><strong>E-mail</strong></td>';
                            echo '<td><strong>Schedule</strong></td></tr>';
							 $allEmails = array(); // for printing all emails
							 
							foreach ($result as $client) {
								if ($client->get_ContactName()=="") $cn="";
									else $cn = $client->get_ContactName() . " (F)";
								echo ("<tr><td><a href='clientEdit.php?id=" . $client->get_id() ."'>" .
									$client->get_id() . "</a></td><td>" . 
									$cn ."</td><td>" .
									$client->get_phone1() . "</td><td>" .
									$client->get_email() . "</td><td>");
									$allEmails[] = $client->get_email();
								foreach(array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort") as $area=>$areaname) {
								    if (sizeof($client->get_days($area))>0) echo $areaname.": ";
								    foreach($client->get_days($area) as $availableon)
									    echo ( $availableon . ", ") ;
								    echo " ";
								}
								echo "</td></tr>"; 
								if ($client->get_ContactName2()!="") {
									echo ("<tr><td></td><td>" .
										$client->get_ContactName2() ." (A)</td><td>" .
										$client->get_phone2() . "</td><td>" .
										$client->get_email2() . "</td><td>").
										$client->get_address2() ." ". $client->get_city2() ." ". $client->get_state2() ." ". $client->get_zip2();
								echo "</td></tr>";
								}
								$allEmails[] = $client->get_email2();
							}
							echo '</table>';
						
						echo "<br><strong>Mailing List</strong><br>";
						echo '<table id="tblReport"> <tr><td><strong>Name</strong></td>';
						echo '<td><strong>Contact </strong></td><td><strong>Address</strong></td><td><strong>City</strong></td>';
						echo '<td><strong>State</strong></td><td><strong>Zip</strong></td></tr>';
						
						foreach ($result as $client) {
						    if ($client->get_type()=="donor") {
						       echo "<tr><td>" . $client->get_id() . "</td><td>" .
							     $client->get_ContactName() . " (F)</td><td>" .
								 $client->get_address() . "</td><td>" .
								 $client->get_city() . "</td><td>" .
								 $client->get_state() ."</td><td>". $client->get_zip();
							   echo "</td></tr>";
						    }
						    else {
						        echo "<tr><td>" . $client->get_id() . "</td><td>" .
						          $client->get_ContactName2() . " (A)</td><td>" .
						          $client->get_address2() . "</td><td>" .
						          $client->get_city2() . "</td><td>" .
						          $client->get_state2() ."</td><td>". $client->get_zip2();
						        echo "</td></tr>";
						    }
						}
						echo '</table>';
						}
						?>
						</div>
						<div style="padding:10px;">
						<input type="button" value="Print List" onclick="showPrintWindow();" />
						</div>
						<?PHP
						 echo "<br/><strong>email these people:</strong> <br/>";
                        if ($allEmails)
                          foreach($allEmails as $email)
                            if ($email!="")
                              echo $email . ", ";
				}
				?>
				<!-- below is the footer that we're using currently-->
			</div>
			<?PHP include('footer.inc');?>
		</div>
		
	</body>
</html>

