<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/
/*
 *	clientForm.inc
 *  a form for a client to be added or edited in the database
 *	@author Hartley Brody
 *	@version 4/4/2012
 */


if($_SESSION['access_level']>=2){
   echo('<p><strong>Donor/Recipient Information Form</strong><br />');
   echo('Here you can edit or delete a client in the database. ');
   echo '(<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)';
}
else {
    echo("<p id=\"error\">You do not have sufficient permissions to edit clients in the database.</p>");
	include('footer.inc');
	echo('</div></div></body></html>');
	die();
}

?>
<form method="POST">
<script>
$(function() {
	$( "#survey_date" ).datepicker();
	$( "#visit_date" ).datepicker();
	$( "#foodsafe_date" ).datepicker();
	$( "#pestctrl_date" ).datepicker();
});
</script>
	<input type="hidden" name="old_id" value=<?PHP echo("\"".$id."\"");?>>
	<input type="hidden" name="_form_submit" value="1">

<p>	Name<span style="font-size:x-small;color:FF0000">*</span>: 
<?PHP 
	if ($id=="new") 
		echo '<input type="text" size="35" name="id" tabindex=1 value="">';
	else echo $client->get_id();
	
	if ($id=="new" || $client->get_type() == "donor") {
		echo ('&nbsp;&nbsp;&nbsp;&nbsp;Chain Name: ');
		echo('<select name="chain_name">');
		echo ('<option value=""></option>');
		echo ('<option value="BiLo"');if ($client->get_chain_name()=='BiLo') echo (' SELECTED'); echo('>BiLo</option>');
		echo ('<option value="Food Lion"');if ($client->get_chain_name()=='Food Lion') echo (' SELECTED'); echo('>Food Lion</option>');
		echo ('<option value="Harris Teeter"');if ($client->get_chain_name()=='Harris Teeter') echo (' SELECTED'); echo('>Harris Teeter</option>');
		echo ('<option value="Piggly Wiggly"');if ($client->get_chain_name()=='Piggly Wiggly') echo (' SELECTED'); echo('>Piggly Wiggly</option>');
		echo ('<option value="Publix"');if ($client->get_chain_name()=='Publix') echo (' SELECTED'); echo('>Publix</option>');
		echo ('<option value="Target"');if ($client->get_chain_name()=='Target') echo (' SELECTED'); echo('>Target</option>');
		echo ('<option value="WalMart"');if ($client->get_chain_name()=='WalMart') echo (' SELECTED'); echo('>WalMart</option>');
		echo('</select>');
	}
	if ($id=="new" || $client->get_type() == "recipient") {
		echo ('&nbsp;&nbsp;&nbsp;&nbsp;LCFB: ');
		echo('<select name="lcfb">');
		echo ('<option value=""></option>');
		echo ('<option value="yes"');if ($client->get_lcfb()=='yes') echo (' SELECTED'); echo('>Yes</option>');
		echo ('<option value="no"');if ($client->get_lcfb()=='no') echo (' SELECTED'); echo('>No</option>');
		echo('</select>');
		echo ('&nbsp;&nbsp;&nbsp;&nbsp;Char Trkr: ');
		echo('<select name="chartrkr">');
		echo ('<option value=""></option>');
		echo ('<option value="yes"');if ($client->get_chartrkr()=='yes') echo (' SELECTED'); echo('>Yes</option>');
		echo ('<option value="no"');if ($client->get_chartrkr()=='no') echo (' SELECTED'); echo('>No</option>');
		echo('</select>');
	}
	echo "<table>";
	if ($id=="new" || $client->get_type() == "recipient") {
		echo "<tr>";
		echo '<td>Applied: <input type="text" id="survey_date" name="survey_date" size="10" value="';
		if ($client->get_survey_date() != ''){
			$time = strtotime($client->get_survey_date());
			echo date("m/d/Y", $time);
		}
		echo '"></td>';
		echo '<td>Visit: <input type="text" id="visit_date" name="visit_date" size="10" value="';
		if ($client->get_visit_date() != ''){
			$time = strtotime($client->get_visit_date());
			echo date("m/d/Y", $time);
		}
		echo '"></td>';
		echo '<td>Food Safe: <input type="text" id="foodsafe_date" name="foodsafe_date" size="10" value="';
		if ($client->get_foodsafe_date() != ''){
			$time = strtotime($client->get_foodsafe_date());
			echo date("m/d/Y", $time);
		}
		echo '"></td>';
		echo '<td>Pest Ctrl: <input type="text" id="pestctrl_date" name="pestctrl_date" size="10" value="';
		if ($client->get_pestctrl_date() != ''){
			$time = strtotime($client->get_pestctrl_date());
			echo date("m/d/Y", $time);
		}
		echo '"></td>';
				echo '<td>Serve/Week: <input type="text" id="number" name="number_served" size="5" value="'.
					$client->get_number_served(). '"></td>';
		echo "</tr>";
	}
    echo ('<tr><td>Base<span style="font-size:x-small;color:FF0000">*</span>: ');
    echo('<select name="area">');
    echo ('<option value=""></option>');
    echo ('<option value="HHI"');if ($client->get_area()=='HHI') echo (' SELECTED'); echo('>Hilton Head</option>');
    echo ('<option value="SUN"');if ($client->get_area()=='SUN') echo (' SELECTED'); echo('>Bluffton</option>');
	echo ('<option value="BFT"');if ($client->get_area()=='BFT') echo (' SELECTED'); echo('>Beaufort</option>');
	echo('</select></td>');
	
	echo ('<td>Type<span style="font-size:x-small;color:FF0000">*</span>: ');
    echo('<select name="type">');
    echo ('<option value=""></option>');
	echo ('<option value="donor"');if ($client->get_type()=='donor') echo (' SELECTED'); echo('>Donor</option>');
	echo ('<option value="recipient"');if ($client->get_type()=='recipient') echo (' SELECTED'); echo('>Recipient</option>');
	echo('</select></td>');

    echo ('<td>County: ');
    echo('<select name="county">');
    echo ('<option value=""></option>');
    echo ('<option value="Beaufort"');if ($client->get_county()=='Beaufort') echo (' SELECTED'); echo('>Beaufort</option>');
    echo ('<option value="Hampton"');if ($client->get_county()=='Hampton') echo (' SELECTED'); echo('>Hampton</option>');
	echo ('<option value="Jasper"');if ($client->get_county()=='Jasper') echo (' SELECTED'); echo('>Jasper</option>');
	echo('</select></td>');
	
	echo ('<td>Area<span style="font-size:x-small;color:FF0000">*</span>: ');
    echo('<select name="deliveryAreaId">');
    echo ('<option value=""></option>');
	$deliveryAreas = getall_dbDeliveryAreas();
	foreach($deliveryAreas as $deliveryArea){
		echo ('<option value="'); 
		echo($deliveryArea->get_deliveryAreaId()); 
		echo('"');
		if ($client->get_deliveryAreaId()==$deliveryArea->get_deliveryAreaId()) 
			echo (' SELECTED');
		 echo('>'); echo($deliveryArea->get_deliveryAreaName()); echo('</option>');
	}
	echo('</select></td>');
	
	echo "</tr></table>";
$areas = array("daysHHI"=>"Hilton Head","daysSUN"=>"Bluffton","daysBFT"=>"Beaufort");
$days = array('Mon', 'Tue', 'Wed' , 'Thu', 'Fri', 'Sat', 'Sun');
echo '<table><tr>';
  foreach($areas as $area=>$areaname) {
    echo '<td><fieldset><legend>'.$areaname.' Pickup/Dropoff:</legend>
	   <table><tr>';
        foreach ($days as $day) echo '<td>'.$day.'&nbsp;&nbsp;</td>';
            echo '</tr>';
        $client_availability = $client->get_days(substr($area,4));
        echo ('<tr>');
        foreach ($days as $day) {
            echo ("<td><input type='checkbox' name='".$area."[]' value='".$day."'");
            if (in_array($day, $client_availability)) echo " checked></td>";
            else echo "></td>";
        }
    echo ('</tr></table></fieldset></td>');
  }
echo "</tr></table>";
?>

<fieldset>
<legend>Food Contact</legend>
	<table>		
		<tr><td>Name:</td><td> <input type="text" size="30" name="ContactName" tabindex=2 value="<?PHP echo($client->get_ContactName())?>"></td>
		    <td>Phone:</td><td> <input type="text" name="phone1" MAXLENGTH=12 tabindex=3 value="<?PHP echo $client->get_phone1()?>"></td>
		    <td>Email:</td><td> <input type="text" size="30" name="email" tabindex=4 value="<?PHP echo $client->get_email() ?>"></td>
		</tr>
		<tr><td>Address<span style="font-size:x-small;color:FF0000">*</span>:</td><td> <input type="text" size="30" name="address" tabindex=5 value="<?PHP echo($client->get_address())?>"></td>
		<td>City<span style="font-size:x-small;color:FF0000">*</span>:</td><td> <input type="text" name="city" tabindex=6 value="<?PHP echo($client->get_city())?>"></td>
		<td>State, Zip:</td>
		<td><select name="state" tabindex=7>
		<?PHP

			$states = array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA",
					        "KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM",
					        "NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA",
					        "WV","WI","WY");
			echo "<option value=''></option>";
			foreach ($states as $st) {
				echo "<option value='" . $st . "' ";
                if($client->get_state() == $st ) echo("SELECTED");
                else if ($id == "new" && $st =="SC") echo("SELECTED");
                echo ">" . $st . "</option>";
			}
		?>
		</select>,
		<input type="text" name="zip" size="5" tabindex=8 value="<?PHP echo($client->get_zip())?>">

	</td></tr>
	</table>
</fieldset>

<fieldset>
<legend>Administrative Contact</legend>
	<table>		
		<tr><td>Name:</td><td> <input type="text" size="30" name="ContactName2" tabindex=9 value="<?PHP echo($client->get_ContactName2())?>"></td>
		    <td>Phone:</td><td> <input type="text" name="phone2" MAXLENGTH=12 tabindex=10 value="<?PHP echo $client->get_phone2()?>"></td>
		    <td>Email:</td><td> <input type="text" size="30" name="email2" tabindex=11 value="<?PHP echo $client->get_email2() ?>"></td>
		</tr>
		<tr><td>Address:</td><td> <input type="text" size="30" name="address2" tabindex=12 value="<?PHP echo($client->get_address2())?>"></td>
		<td>City:</td><td> <input type="text" name="city2" tabindex=13 value="<?PHP echo($client->get_city2())?>"></td>
		<td>State, Zip:</td>
		<td><select name="state2" tabindex=14>
		<?PHP

			$states = array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA",
					        "KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM",
					        "NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA",
					        "WV","WI","WY");
			echo "<option value=''></option>";
			foreach ($states as $st) {
				echo "<option value='" . $st . "' ";
                if($client->get_state2() == $st ) echo("SELECTED");
                else if ($id == "new" && $st =="SC") echo("SELECTED");
                echo ">" . $st . "</option>";
			}
		?>
		</select>,
		<input type="text" name="zip2" size="5" tabindex=15 value="<?PHP echo($client->get_zip2())?>"></td></tr>
	</table>
</fieldset>


<p>
		<?PHP
		
		  echo('<p>Notes:<br />');
	      echo('<textarea name="notes" rows="2" cols="60">');
	      echo($client->get_notes());
	      echo('</textarea>');

		  echo('<input type="hidden" name="_submit_check" value="1"><p>');
		  if ($_SESSION['access_level']==0)
		  	 echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to complete this application.<br /><br />');
		  else if ($id=="new")
		     echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to add this new client.<br /><br />');
		  else
		     echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to complete these changes.<br /><br />');
		  if ($id != 'new' && $_SESSION['access_level']>=2) {
			echo ('<input type="checkbox" name="deleteMe" value="DELETE"> Check this box and then hit ' .
				'<input type="submit" value="Delete" name="Delete Entry"> to delete this client from the database. <br />' );
		}
		?>

<?PHP
function &select_date($month, $day, $year, $month_name, $day_name, $year_name) {
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
        // handles a year range of 90 years, from today+10 to today-80
		echo '<select name='.$year_name.'><option value=""></option>';
		$start_year = date("Y")+10;
        for ($i = $start_year; $i >= ($start_year-90); $i--){
         	echo '<option value='.substr($i,2,2); 
         	if($year==substr($i,2,2)) 
         		echo(' SELECTED'); 
         	echo '>' . $i . ' </option>'; 
        }
		echo "</select>";	
}
?>
</form>