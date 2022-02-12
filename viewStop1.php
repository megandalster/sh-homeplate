<?php

/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/

/*
 * DROPOFF STOPS
 *
 * viewStop1 GUI for Homeplate
 * @author Nicholas Wetzel
 * @version May 8, 2012
 */

/*
 * This file creates the data entry page for stops that record only total weight.
 */
	session_start();
	//session_cache_expire(30);
	
	include_once('database/dbStops.php');
	include_once('domain/Stop.php');
	include_once('database/dbVolunteers.php');
    include_once('domain/Volunteer.php');
	$areas = array("HHI"=>"Hilton Head","SUN"=>"Bluffton","BFT"=>"Beaufort");
					
    // Set necessary values using the GET routine
	$routeID = substr($_GET['stop_id'],0,12);
	$client_id = substr($_GET['stop_id'],12);
    $client_type = $_GET['client_type'];
    $area = substr($_GET['stop_id'],9,3);
    $ndate = substr($_GET['stop_id'],0,8);
    $balance = $_GET['balance'];
    $date = date('l, F j, Y', mktime(0,0,0,substr($ndate,3,2),substr($ndate,6,2),substr($ndate,0,2)));
    $client_items = "";

	// If the current stop has not been created then create it and add it to the database.
    // Otherwise, retrieve it from the database.

    if (!retrieve_dbStops($routeID.$client_id)){
        $stop1 = new Stop($routeID, $client_id, $client_type, $client_items, '');
        insert_dbStops($stop1);
    }
    else{
        $stop1 = retrieve_dbStops($routeID.$client_id);
    }
    
    error_log(print_r($stop1,true));
    // If values have been submitted, then update the database and display the submitted values to the driver.
    if (isset($_POST['submitted'])){
        error_log('resetting weight');
        $rw = $_POST['rescued_weight'];
        $tw = $_POST['transported_weight'];
        $pw = $_POST['purchased_weight'];
        $fw = $_POST['food_drive_weight'];
        $stop1->set_rescued_weight($rw);
        $stop1->set_transported_weight($tw);
        $stop1->set_purchased_weight($pw);
        $stop1->set_food_drive_weight($fw);
        $tw = $rw + $tw + $pw + $fw;
        $stop1->set_total_weight($tw);
        $balance = $balance - $tw;
        update_dbStops($stop1);
    }

    echo <<<END
<html>
	<head>
		<title>
			Viewing {$area}-{$client_id}-{$date}
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
        <style>
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	</head>
	<body>
		<div id="container">
END;
    include('header.php');
    echo <<<END
			<div id="content">
  
  			<!-- Display the name of the current stop -->
			<p><big><b>{$client_id}</b></big></p>
			
			<!-- Display the associated route, driver and date of the stop -->
			<p>Area: {$areas[$area]}<br />
			   Date: {$date}</p>
			   Balance on truck: {$balance}</p>
			
			<!-- The data entry field for total weight and driver notes -->
			<form method = "post">
			<fieldset>
				<legend><b>Data Entry</b></legend><br />
                <table>
                    <tr>
                        <td style="text-align: right;">
                            <i>Rescued Weight: </i>
                        </td>
                        <td>
                            <input type="number"  min="0" style="width: 70px;"
                                   onchange="updateTotal()" step="1" pattern="\d+"
                                   name="rescued_weight"
                                   id="rescued_weight" value="{$stop1->get_rescued_weight()}"> lbs.
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">
                            <i>Transported Weight: </i>
                        </td>
                        <td>
                            <input type="number"  min="0" style="width: 70px;"
                                   onchange="updateTotal()" pattern="\d+" pattern="\d+"
                                   name="transported_weight"
                                   id="transported_weight" value="{$stop1->get_transported_weight()}"> lbs.
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">
                            <i>Purchased Weight: </i>
                        </td>
                        <td>
                            <input type="number"  min="0" style="width: 70px;"
                                   onchange="updateTotal()" pattern="\d+" pattern="\d+"
                                   name="purchased_weight"
                                   id="purchased_weight" value="{$stop1->get_purchased_weight()}"> lbs.
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">
                            <i>Food Drive Weight: </i>
                        </td>
                        <td>
                            <input type="number"  min="0" style="width: 70px;"
                                   onchange="updateTotal()" pattern="\d+" pattern="\d+"
                                   name="food_drive_weight"
                                   id="food_drive_weight" value="{$stop1->get_food_drive_weight()}"> lbs.
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td><td>-----------------</td></tr>
                    <tr>
                        <td style="text-align: right;">
                            <i>Total Weight: </i>
                        </td>
                        <td>
                            <input type="number"  min="0" style="width: 70px;"
                                   name="total_weight" id="total_weight" disabled value="{$stop1->get_total_weight()}"> lbs.
                        </td>
                    </tr>
                </table>
                <script>
                  function updateTotal() {
                    let rw = parseInt($('#rescued_weight').val(),10) || 0
                    $('#rescued_weight').val(rw)
                    let tw = parseInt($('#transported_weight').val(),10) || 0
                    $('#transported_weight').val(tw)
                    let pw = parseInt($('#purchased_weight').val(),10) || 0
                    $('#purchased_weight').val(pw)
                    let fw = parseInt($('#food_drive_weight').val(),10) || 0
                    $('#food_drive_weight').val(fw)
                    $('#total_weight').val(rw+tw+pw+fw)
                  }
                </script>
                
			<!-- A hidden variable that, when submitted, is used to display submitted values and update the databases -->
			<br><input type = "hidden" name = "submitted" value = "true"/>		
			<br><input type="submit" value="Save"/>&nbsp;&nbsp;<i>Hit Save to save this weight and notes.</i>
			<br><br><a href="editRoute.php?routeID={$routeID}"><big>Return to Route</big></a>
			</fieldset></form><br />
END;

			
			// The link to return to the current route.
			echo '</div>';
			include('footer.inc');
			?>
		</div>
	</body>
</html>