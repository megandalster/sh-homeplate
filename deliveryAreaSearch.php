<?php
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/
	session_start();
	session_cache_expire(30);
	
	  include_once('database/dbDeliveryAreas.php');
      include_once('domain/DeliveryArea.php');
	  
	  // if user hit "Search"  button, query the database and display the results
					if( array_key_exists('s_submitted', $_POST) ){

                       // $name = trim(str_replace('\'','&#39;',htmlentities($_POST['s_name'])));
                  
                        //echo "search criteria: ", $area.$type.$status.$name.$availability[0];
                        
                        // now go after the volunteers that fit the search criteria
                      
                        
						$aid = htmlentities($_POST['deliveryAreaId']);
                        
						header("Location: deliveryAreaEdit.php?id=".$aid);
die();
						
						/*
                        $result = getonlythose_dbAffiliates($name);  

						echo '<p><strong>Search Results:</strong> <p>Found ' . sizeof($result). ' ';
                            if (!$type) echo "area(s)"; 
                            else echo $type.'s';
						
						if ($name!="") echo ' with name like "'.$name.'"';
						
						if (sizeof($result)>0) {
							echo ' (select one for more info).';
							echo '<p><table> <tr><td><strong>Name</strong></td></tr>';
                           
                            foreach ($result as $affiliate) {
								echo "<tr><td><a href=deliveryAreaEdit.php?id=".$affiliate->get_deliveryAreaId().">" . 
									$affiliate->get_deliveryAreaName() .  "</td></tr>";
							}
						}
						echo '</table>';
                        */
						
					}
					
?>
<html>
	<head>
		<title>
			Search for Areas
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>
		<div id="container">
			<?PHP include('header.php');?>
			<div id="content">
				<?PHP
				// display the search form
					$area = $_GET['area'];
					$areas = array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort");
					$days = array("Mon"=>"Monday","Tue"=>"Tuesday","Wed"=>"Wednesday","Thu"=>"Thursday","Fri"=>"Friday","Sat"=>"Saturday","Sun"=>"Sunday");
					echo('<p><a href="'.$path.'deliveryAreaEdit.php?id=new">Add new area</a>');
					echo('<form method="post">');
						echo('<p><strong>Search for area:</strong>');
                        
                      
                         echo('<select name="deliveryAreaId">');
	
	$deliveryAreas = getall_dbDeliveryAreas();
	foreach($deliveryAreas as $deliveryArea){
		echo ('<option value="'); 
		echo($deliveryArea->get_deliveryAreaId()); 
		echo('"');
		
		
		//if ($person->get_deliveryAreaId()==$deliveryArea->get_deliveryAreaId()) 
		if( !array_key_exists('deliveryAreaId', $_POST) ) 
			echo (' SELECTED');
		 echo('>'); echo($deliveryArea->get_deliveryAreaName()); echo('</option>');
	}
    
	echo('</select>');
                     
                        /*
						if( !array_key_exists('s_name', $_POST) ) $name = ""; else $name = $_POST['s_name'];
						echo '&nbsp;&nbsp;Name: ' ;
						echo '<input type="text" name="s_name" value="' . $name . '">';
					*/
						
						echo('<p><input type="hidden" name="s_submitted" value="1"><input type="submit" name="Search" value="Search">');
						echo('</form></p>');
                        
                        //print_r( $_POST );
					
				
				?>
				<!-- below is the footer that we're using currently-->
				
			</div>
			<?PHP include('footer.inc');?>
		</div>
	</body>
</html>

