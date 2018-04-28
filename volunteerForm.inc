<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/
/*
 *	volunteerForm.inc
 *  a form for a volunteer to be added or edited in the database
 *	@author Allen Tucker
 *	@version 4/1/2012
 */

if ($_SESSION['access_level']==0) {
    echo('<p><strong>New Volunteer Application Form</strong><br />');
	echo('Please provide some basic information about yourself and your interests in Second Helpings.' .
		 ' When you are finished, hit <b>Submit</b> at the bottom of this page. (<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)');
}
else if($_SESSION['access_level']==1)
    if ($_SESSION['_id']!=$person->get_id()){
		echo("<p id=\"error\">You do not have sufficient permissions to edit this user.</p>");
		include('footer.inc');
		echo('</div></div></body></html>');
		die();
	}
	else {
		echo('<p><strong>Volunteer Information Form</strong><br />');
	    echo('Here you can edit your own information in the database.' .
	    	 '<br>When you are finished, hit <b>Submit</b> at the bottom of this page. (<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)');
	}
else // $_SESSION['access_level']>=2
	if ($id=='new') {
	  echo('<p><strong>Volunteer Input Form</strong><br />');
	  echo('Here you can add a new volunteer to the database. (<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)</p>');
    }
    else {
	   echo('<p><strong>Volunteer Information Form</strong><br />');
	   echo('Here you can edit, delete, or reset the password for a volunteer. (<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)</p>');
    }

?>
<form method="POST">
	<input type="hidden" name="old_id" value=<?PHP echo("\"".$id."\"");?>>
	<input type="hidden" name="old_pass" value=<?PHP echo("\"".$person->get_password()."\"");?>>
	<input type="hidden" name="_form_submit" value="1">

<p>	<?php 
	if ($id=="new") {
		echo 'First Name<span style="font-size:x-small;color:FF0000">*</span>: <input type="text" name="first_name" tabindex=1 value="'.$person->get_first_name().'">';
		echo 'Last Name<span style="font-size:x-small;color:FF0000">*</span>: <input type="text" name="last_name" tabindex=2 value="'.$person->get_last_name().'">';
	}
	else 	
		echo 'Name: '.$person->get_first_name().' '.$person->get_last_name();		
?>
<fieldset>
<legend>Basic Information</legend>
	<table>	<tr><td>Address<span style="font-size:x-small;color:FF0000">*</span>:</td>
				<td> <input type="text" size="30" name="address" tabindex=3 value="<?PHP echo($person->get_address())?>"></td>
				<td>City<span style="font-size:x-small;color:FF0000">*</span>:</td>
				<td> <input type="text" name="city" tabindex=4 value="<?PHP echo($person->get_city())?>"></td>
				<td>State<span style="font-size:x-small;color:FF0000">*</span>:</td>
				<td><select name="state" tabindex=5>
					<?PHP
						$states = array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA",
					        "KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM",
					        "NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA",
					        "WV","WI","WY");
						foreach ($states as $st) {
							echo "<option value='" . $st . "' ";
                			if($person->get_state() == $st ) echo("SELECTED");
                			else if ($id == "new" && $st =="SC") echo("SELECTED");
                			echo ">" . $st . "</option>";
						}
					?>
					</select>
				<td>Zip<span style="font-size:x-small;color:FF0000">*</span>:</td>
				<td><input type="text" name="zip" size="5" tabindex=6 value="<?PHP echo($person->get_zip())?>"></td>
			</tr>
		<tr>
		<?php 
			if ($id=="new")
				echo '<td>Primary Phone<span style="font-size:x-small;color:FF0000">*</span>:</td><td> <input type="text" name="phone1" MAXLENGTH=12 tabindex=7 value="'.$person->get_phone1().'"></td>';
			else 
				echo '<td>Primary Phone:</td><td>'.$person->get_phone1().'</td>';
		?>
		<td>Cell Phone:</td><td><input type="text" name="phone2" MAXLENGTH=12 tabindex=8 value="<?PHP echo $person->get_phone2()?>"></td></tr>
		<tr><td>Email:</td><td><input type="text" name="email" size="30" tabindex=9 value="<?PHP echo($person->get_email())?>"></td></tr>


       <tr><td>Birthday:</td><td>
            <?PHP 
            	$mm = substr($person->get_birthday(),3,2);
            	$dd = substr($person->get_birthday(),6,2);
            	$yy = substr($person->get_birthday(),0,2);
            	select_date($mm,$dd,$yy, "birthday_Month", "birthday_Day", "birthday_Year",date("Y")-90, date("Y")-18);
             
            echo '<td>Start Date: </td><td><input type="text" tabindex=10 id="startDate" name="startDate" value="';
				if ($person->get_start_date() != ''){
					$time = strtotime($person->get_start_date());
					echo date("m/d/Y", $time);
				}
				echo '">';
		?>
		</td></tr>
		<tr><td>Shirt Size:</td><td>
		<?PHP 
		 $shirtSizes = array("S", "M", "L", "XL", "2XL");
		 
		 echo('<select name="shirtSize" tabindex="11">');
		 echo "<option value='" . $shirtSize . "' ";
		  if($person->get_shirtSize() == '' ) echo("SELECTED");
                echo ">-- Select Shirt Size --</option>";
		 
		 foreach ($shirtSizes as $shirtSize) {
				echo "<option value='" . $shirtSize . "' ";
                if($person->get_shirtSize() == $shirtSize ) echo("SELECTED");
                echo ">" . $shirtSize . "</option>";
			}
		echo('</select>');
		
		?>
		</td></tr>
		
		<tr><td>Trip Count: </td><td><input type="text" name="tripCount" size="4" tabindex=11 value="<?PHP echo($person->get_tripCount())?>"></td>
		<?PHP 	echo '<td>Last Trip Date: </td><td><input type="text" tabindex=12 id="lastTripDate" name="lastTripDate" value="';
				if ($person->get_lastTripDate() != ''){
					$time = strtotime($person->get_lastTripDate());
					echo date("m/d/Y", $time);
				}
				echo '">';
		?>
		</tr>
		
		<tr>
		<?PHP 	echo '<td>Volunteer Training: </td><td><input type="text" tabindex=13 id="volunteerTrainingDate" name="volunteerTrainingDate" value="';
				if ($person->get_volunteerTrainingDate() != ''){
					$time = strtotime($person->get_volunteerTrainingDate());
					echo date("m/d/Y", $time);
				}
				echo '">';
			 	echo '<td>Driver Training: </td><td><input type="text" tabindex=14 id="driverTrainingDate" name="driverTrainingDate" value="';
				if ($person->get_driverTrainingDate() != ''){
					$time = strtotime($person->get_driverTrainingDate());
					echo date("m/d/Y", $time);
				}
				echo '">';
		?>
		</tr>
</table>
</fieldset>

 <script>
$(function() {
$( "#lastTripDate" ).datepicker();
$( "#startDate" ).datepicker();
$( "#volunteerTrainingDate" ).datepicker();
$( "#driverTrainingDate" ).datepicker();
});
</script>

<?PHP
    echo ('<p>Base<span style="font-size:x-small;color:FF0000">*</span>: ');
    echo('<select name="area">');
 //   echo ('<option value=""></option>');
    echo ('<option value="HHI"');if ($person->get_area()=='HHI') echo (' SELECTED'); echo('>Hilton Head</option>');
    echo ('<option value="SUN"');if ($person->get_area()=='SUN') echo (' SELECTED'); echo('>Bluffton</option>');
	echo ('<option value="BFT"');if ($person->get_area()=='BFT') echo (' SELECTED'); echo('>Beaufort</option>');
	echo('</select>');
	
	echo ('&nbsp;&nbsp;Status: ');
    echo('<select name="status">');
 //   echo ('<option value=""></option>');
    echo ('<option value="applicant"');if ($person->get_status()=='applicant') echo (' SELECTED'); echo('>applicant</option>');
    if ($_SESSION['access_level'] > 1) {
      echo ('<option value="active"');if ($person->get_status()=='active') echo (' SELECTED'); echo('>active</option>');
	  echo ('<option value="on-leave"');if ($person->get_status()=='on-leave') echo (' SELECTED'); echo('>on leave</option>');
	  echo ('<option value="former"');if ($person->get_status()=='former') echo (' SELECTED'); echo('>former</option>');
    }
	echo('</select>');
	
	 echo ('&nbsp;&nbsp;Affiliate<span style="font-size:x-small;color:FF0000">*</span>: ');
    echo('<select name="affiliateId">');
    echo ('<option value=""></option>');
	
	$affiliates = getall_dbAffiliates();
	foreach($affiliates as $affiliate){
		echo ('<option value="'); 
		echo($affiliate->get_affiliateId()); 
		echo('"');
		if ($person->get_affiliateId()==$affiliate->get_affiliateId()) 
		echo (' SELECTED'); echo('>'); echo($affiliate->get_affiliateName()); echo('</option>');
	}
    
	echo('</select>');
	
	
	$st = implode(',',$person->get_type());
    $types = array('driver'=>'Driver', 'helper'=>'Helper', 'teamcaptain'=>'Day Captain', 'sub' => "Sub",
                   'coordinator'=>'Coordinator', 'associate'=>"Associate", 'boardmember'=>"Board Member");
	echo('<p>Role(s)');
	echo('<span style="font-size:x-small;color:FF0000">*</span>: ');

    foreach ($types as $key=>$value) {
    	echo ('<input type="checkbox" name="type[]" value=' . $key);
    	if (strpos($st,$key)!==false) echo(' CHECKED');
    	echo ('>' . $value . '&nbsp;&nbsp;');
    }

    if ($id!="new") {
    echo "</p><fieldset id='availability'><legend>Schedule:</strong> </legend><table>".
		"<tr><td> &nbsp;&nbsp;</td><td>Mon&nbsp;&nbsp;</td><td>Tue&nbsp;&nbsp;</td><td>Wed&nbsp;&nbsp;</td>".
		"<td>Thu&nbsp;&nbsp;</td><td>Fri&nbsp;&nbsp;</td><td>Sat&nbsp;&nbsp;</td><td>Sun</td></tr>";
    $weeks = array('1'=>'1st' , '2'=>'2nd', '3'=>'3rd', '4'=>'4th', '5'=>'5th' );
    $oddeven = array ('odd', 'even');
    $days = array('Mon', 'Tue', 'Wed' , 'Thu', 'Fri', 'Sat', 'Sun');
    $person_availability = implode(',',$person->get_availability());
    if ($person->get_area()=="BFT" || $_SESSION['area']=="BFT")
    	foreach ($weeks as $weekno => $weekvalue) {
       		echo ('<tr><td>'. $weekvalue . '</td>');
       		foreach ($days as $day) {
       	  		$realkey = $day . ":". $weekno;
       	  		echo ('<td><input type="checkbox" name="availability[]" value=' . $realkey);
    	  		if (in_array($day.":".$weekno, $person->get_availability())) echo(' CHECKED');
    	  		echo ('></td>');
       		}
       		echo ('</tr>');
    	}
    else {
       foreach ($oddeven as $weekno) {
       	  echo ('<tr><td>'. $weekno . '</td>');
       	  foreach ($days as $day) {
       	  	$realkey = $day. ":". $weekno;;
       	  	echo ('<td><input type="checkbox" name="availability[]" value=' . $realkey);
    	  	if (in_array($day.":".$weekno, $person->get_availability()) ||
    	  		in_array($day, $person->get_availability())) echo(' CHECKED');
    	  	echo ('></td>');
       	  }
       echo ('</tr>');
       }
    }
    echo "</table></fieldset><p>";
    }
    else echo "<p>(<i>Note: A new volunteer's schedule cannot be made until the above contact data has been submitted.</i>)";
//		if($id == 'new' || strpos(implode(',',$person->get_type()),'applicant') !== false) {
		  echo('<p>License no: ');
	      echo('<input type="text" name="license_no" value="'.$person->get_license_no().'">');
	   
	      echo('&nbsp;&nbsp;License state: <select name="license_state" tabindex=5>');
	      foreach ($states as $st) {
				echo "<option value='" . $st . "' ";
                if($person->get_license_state() == $st ) echo("SELECTED");
                else if ($id == "new" && $st =="SC") echo("SELECTED");
                echo ">" . $st . "</option>";
			}
	      echo('</select>');
	   
	      echo('&nbsp;&nbsp;License Expiration: ');
          $mm = substr($person->get_license_expdate(),3,2);
          $dd = substr($person->get_license_expdate(),6,2);
          $yy = substr($person->get_license_expdate(),0,2);
          $start_year = date("Y");
          select_date($mm,$dd,$yy, "expdate_Month", "expdate_Day", "expdate_Year",$start_year, $start_year+10);
          
          echo('<p>Notes:<br />');
	      echo('<textarea name="notes" rows="2" cols="60">');
	      echo($person->get_notes());
	      echo('</textarea>');

		  echo('<input type="hidden" name="schedule" value="'.implode(',',$person->get_schedule()).'">');
		  echo('<input type="hidden" name="history" value="'.implode(',',$person->get_history()).'">');
		  echo('<input type="hidden" name="password" value="'.$person->get_password().'">');
		  echo('<input type="hidden" name="_submit_check" value="1"><p>');
		  if ($_SESSION['access_level']==0)
		  	 echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to complete this application.<br /><br />');
		  else if ($id=="new")
		     echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to add this new volunteer.<br /><br />');
		  else
		     echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to complete these changes.<br /><br />');
		  if ($id != 'new' && $_SESSION['access_level']>=2) {
			echo ('<input type="checkbox" name="deleteMe" value="DELETE"> Check this box and then hit ' .
				'<input type="submit" value="Delete" name="Delete Entry"> to delete this volunteer from the database. <br />' .
				'<input type="checkbox" name="reset_pass" value="RESET"> Check this box and then hit ' .
				'<input type="submit" value="Reset Password" name="Reset Password"> to reset this volunteer\'s password.</p>');
		}
		?>

<?PHP
function &select_date($month, $day, $year, $month_name, $day_name, $year_name,$start_year, $end_year) {
		$months = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        echo('<select name='.$month_name.'><option value=""></option>');
		echo('');
        for ($i = 1; $i <= 12; $i++) {
            echo '<option value='.(($i<10)?"0".$i:$i);
            if ($month==$i) 
            	echo(' SELECTED');
            echo '>' . $months[$i] . ' </option>';
        }
		echo "</select>";

		echo '<select name='.$day_name.'><option value=""></option>';
		for ($i = 1; $i <= 31; $i++) {
            echo '<option value='.(($i<10)?"0".$i:$i); 
            if($day==$i) 
            	echo(' SELECTED'); 
            echo '>' . $i . ' </option>'; 
		}
		echo "</select>";
        // handles a year range
		echo '<select name='.$year_name.'><option value=""></option>';
        for ($i = $end_year; $i >= $start_year; $i--){
         	echo '<option value='.substr($i,2,2); 
         	if($year==substr($i,2,2)) 
         		echo(' SELECTED'); 
         	echo '>' . $i . ' </option>'; 
        }
		echo "</select>";	
}

?>
</form>