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


if($_SESSION['access_level']==2){
   echo('<p><strong>Client Information Form</strong><br />');
   echo('Here you can edit or delete a client in the database.</p><p>');
}
else {
    echo("<p id=\"error\">You do not have sufficient permissions to edit clients in the database.</p>");
	include('footer.inc');
	echo('</div></div></body></html>');
	die();
}

?>
<form method="POST">
	<input type="hidden" name="old_id" value=<?PHP echo("\"".$id."\"");?>>
	<input type="hidden" name="_form_submit" value="1">
<p>(<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)

<p>	Name<span style="font-size:x-small;color:FF0000">*</span>: <input type="text" name="id" tabindex=1 value="<?PHP echo( $client->get_id() )?>">
	Chain Name: <input type="text" name="chain_name" tabindex=2 value="<?PHP echo($client->get_chain_name() )?>">

<?PHP
    echo ('<p>Area: ');
    echo('<select name="area">');
    echo ('<option value=""></option>');
    echo ('<option value="HHI"');if ($client->get_area()=='HHI') echo (' SELECTED'); echo('>Hilton Head</option>');
    echo ('<option value="SUN"');if ($client->get_area()=='SUN') echo (' SELECTED'); echo('>Sun City</option>');
	echo ('<option value="BFT"');if ($client->get_area()=='BFT') echo (' SELECTED'); echo('>Beaufort</option>');
	echo('</select>');
	
	echo ('&nbsp;&nbsp;Type: ');
    echo('<select name="type">');
    echo ('<option value=""></option>');
	echo ('<option value="donor"');if ($client->get_type()=='donor') echo (' SELECTED'); echo('>Donor</option>');
	echo ('<option value="recipient"');if ($client->get_type()=='recipient') echo (' SELECTED'); echo('>Recipient</option>');
	echo('</select>');

    
?>    
    
<fieldset>
<legend>Contact Information</legend>
	<table>		<tr><td>Address<span style="font-size:x-small;color:FF0000">*</span>:</td><td> <input type="text" name="address" tabindex=3 value="<?PHP echo($client->get_address())?>"></td></tr>
		<tr><td>City<span style="font-size:x-small;color:FF0000">*</span>:</td><td> <input type="text" name="city" tabindex=4 value="<?PHP echo($client->get_city())?>"></td></tr>
		<tr><td>State, Zip:</td>
		<td><select name="state" tabindex=5>
		<?PHP

			$states = array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA",
					        "KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM",
					        "NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA",
					        "WV","WI","WY");
			foreach ($states as $st) {
				echo "<option value='" . $st . "' ";
                if($client->get_state() == $st ) echo("SELECTED");
                else if ($id == "new" && $st =="SC") echo("SELECTED");
                echo ">" . $st . "</option>";
			}
		?>
		</select>,
		<input type="text" name="zip" size="5" tabindex=6 value="<?PHP echo($client->get_zip())?>"></td></tr>
		<tr><td>Primary Phone:</td><td> <input type="text" name="phone1" MAXLENGTH=12 tabindex=7 value="<?PHP echo($client->get_phone1())?>"></td></tr>
		<tr><td>Alternate Phone:</td><td><input type="text" name="phone2" MAXLENGTH=12 tabindex=8 value="<?PHP echo($client->get_phone2())?>"></td></tr>

</table>
</fieldset>

</p>
<fieldset id="availability">
<legend>Availability:</strong><span style="font-size:x-small;color:FF0000">*</span> </legend>
	<table>
	<tr>
		<td>Mon&nbsp;&nbsp;</td><td>Tue&nbsp;&nbsp;</td><td>Wed&nbsp;&nbsp;</td>
		<td>Thu&nbsp;&nbsp;</td><td>Fri&nbsp;&nbsp;</td><td>Sat&nbsp;&nbsp;</td><td>Sun</td></tr>
<?PHP
    $weeks = array('1'=>'1st', '2'=>'2nd', '3'=>'3rd', '4'=>'4th', '5'=>'5th');
    $days = array('Mon', 'Tue', 'Wed' , 'Thu', 'Fri', 'Sat', 'Sun');
    $client_availability = implode(',',$client->get_days());
       echo ('<tr>');
       foreach ($days as $day) {
       	  echo ('<td><input type="checkbox" name="days[]" value=' . $day);
    	  if (in_array($day, $client->get_days())) echo(' CHECKED');
    	  echo ('></td>');
       }
       echo ('</tr>');
?>
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