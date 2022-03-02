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

global $fn;
include_once(dirname(__FILE__).'/Utils.php');


if($_SESSION['access_level']>=2){
   echo('<p><strong>Donor/Recipient Information Form</strong><br />');
   echo('Here you can edit or delete a client in the database. ');
   echo '(<span style="font-size:x-small;color:#FF0000;">*</span> indicates required information.)';
}
else {
    echo("<p id=\"error\">You do not have sufficient permissions to edit clients in the database.</p>");
	include('footer.inc');
	echo('</div></div></body></html>');
	die();
}

if ($id == 'new') {
    $client->set_type('recipient');
}

echo <<<END
<style>
    .required {
        font-size: x-small;
        color: #FF0000;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script>
    $(function() {
        $( "#survey_date" ).datepicker();
        $( "#visit_date" ).datepicker();
        $( "#foodsafe_date" ).datepicker();
        $( "#pestctrl_date" ).datepicker();
    });
</script>
<form method="POST">
	<input type="hidden" name="old_id" value="{$id}">
	<input type="hidden" name="_form_submit" value="1">

    <table>
        <tr>
            <td style="width:55px; text-align: right">Name<span class="required">*</span>:
            </td>
            <td>
                {$fn($id=='new' ? '<input type="text" size="35" name="id" tabindex=1 value="">' : '<span style="font-weight:bold;">'.$client->get_id().'</span>')}
            </td>
            <td style="width:55px; text-align: right">Status:</td>
            <td>
                <select name="status">
                    <option value="active" {$fn(selected($client->get_status(),'active'))}>active</option>
                    <option value="inactive" {$fn(selected($client->get_status(),'inactive'))}>inactive</option>
                    <option value="former" {$fn(selected($client->get_status(),'former'))}>former</option>
                </select>
            </td>
		    <td>
                <div class="donor" id="chain-name-div" style="display:none;">&nbsp;&nbsp;&nbsp;&nbsp;Group/Chain:
                    <select name="chain_name">
                        <option value=""></option>
                        <option value="Big Lots" {$fn(selected($client->get_chain_name(),'Big Lots'))}>Big Lots</option>
                        <option value="BiLo" {$fn(selected($client->get_chain_name(),'BiLo'))}>BiLo</option>
                        <option value="Food Lion" {$fn(selected($client->get_chain_name(),'Food Lion'))}>Food Lion</option>
                        <option value="Fresh Market" {$fn(selected($client->get_chain_name(),'Fresh Market'))}>Fresh Market</option>
                        <option value="Harris Teeter" {$fn(selected($client->get_chain_name(),'Harris Teeter'))}>Harris Teeter</option>
                        <option value="Kroger" {$fn(selected($client->get_chain_name(),'Kroger'))}>Kroger</option>
                        <option value="Publix" {$fn(selected($client->get_chain_name(),'Publix'))}>Publix</option>
                        <option value="Restaurants" {$fn(selected($client->get_chain_name(),'Restaurants'))}>Restaurants</option>
                        <option value="Target" {$fn(selected($client->get_chain_name(),'Target'))}>Target</option>
                        <option value="WalMart" {$fn(selected($client->get_chain_name(),'WalMart'))}>WalMart</option>
                        <option value="Whole Foods" {$fn(selected($client->get_chain_name(),'Whole Foods'))}>Whole Foods</option>
                    </select>
                </div>
            </td>
		    <td class="recipient">&nbsp;&nbsp;&nbsp;&nbsp;LCFB:
                <select name="lcfb">
                    <option value=""></option>
                    <option value="yes" {$fn(selected($client->get_lcfb(),'yes'))}>Yes</option>
                    <option value="no" {$fn(selected($client->get_lcfb(),'no'))}>No</option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;Char Trkr:
                <select name="chartrkr">
                    <option value=""></option>
                    <option value="yes" {$fn(selected($client->get_chartrkr(),'yes'))}>Yes</option>
                    <option value="no" {$fn(selected($client->get_chartrkr(),'no'))}>No</option>
                </select>
            </td>
        </tr>
    </table>
END;
	
    
    // Line 2 --------------------------------------------------------------------------
	echo <<<END
        <table class="recipient">
            <tr>
		        <td style="width:55px; text-align: right">Applied:</td>
		        <td style="width:100px;">
		            <input type="text" id="survey_date" name="survey_date" size="10"
		                value="{$fn(showDate($client->get_survey_date()))}">
		        </td>
		        <td>Visit:</td>
		        <td>
		            <input type="text" id="visit_date" name="visit_date" size="10"
		                value="{$fn(showDate($client->get_visit_date()))}">
		        </td>
		        <td>Food Safe:</td>
		        <td>
		            <input type="text" id="foodsafe_date" name="foodsafe_date" size="10"
		                value="{$fn(showDate($client->get_foodsafe_date()))}">
		        </td>
                <td>Pest Ctrl: <input type="text" id="pestctrl_date" name="pestctrl_date" size="10"
                    value="{$fn(showDate($client->get_pestctrl_date()))}">
                </td>
                <td id="td-adults">&nbsp;&nbsp;&nbsp;Adults served/Week<span class="required">*</span>: <input type="text" id="number" name="number_served" size="5"
                    value="{$client->get_number_served()}">
                </td>
                <td id="td-children">Children<span class="required">*</span>: <input type="text" id="number" name="children_served" size="5"
                    value="{$client->get_children_served()}">
                </td>
                <td id="td-seniors">Seniors<span class="required">*</span>: <input type="text" id="number" name="seniors_served" size="5"
                    value="{$client->get_seniors_served()}">
                </td>
            </tr>
        </table>
END;

    // Line 3 --------------------------------------------------------------------------
    echo <<<END
            <table>
                <tr>
                    <td style="width:55px; text-align: right">Base<span class="required">*</span>:</td>
                    <td style="width:100px;">
                        <select name="area">
                            <option value=""></option>
                            <option value="HHI" {$fn(selected($client->get_area(),'HHI'))}>Hilton Head</option>
                            <option value="SUN" {$fn(selected($client->get_area(),'SUN'))}>Bluffton</option>
                            <option value="BFT" {$fn(selected($client->get_area(),'BFT'))}>Beaufort</option>
                        </select>
                    </td>
                    <td style="width:40px; text-align: right">Type<span class="required"">*</span>:</td>
                    <td>
                        <select name="type" id="type-select">
                            <option value=""></option>
                            <option value="donor"' {$fn(selected($client->get_type(),'donor'))}>Donor</option>
                            <option value="recipient" {$fn(selected($client->get_type(),'recipient'))}>Recipient</option>
                        </select>
                    </td>
                    <td>
                        <div class="donor" id="donor-type-div" style="display:none;">Donor Type<span class="required"">*</span>:
                            <select name="donor_type">
                                <option value=""></option>
                                <option value="Rescued Food" {$fn(selected($client->get_donor_type(),'Rescued Food'))}>Rescued Food</option>
                                <option value="Purchased Food" {$fn(selected($client->get_donor_type(),'Purchased Food'))}>Purchased Food</option>
                                <option value="Food Drive Food" {$fn(selected($client->get_donor_type(),'Food Drive Food'))}>Food Drive Food</option>
                                <option value="Transported Food" {$fn(selected($client->get_donor_type(),'Transported Food'))}>Transported Food</option>
                            </select>
                        </div>
                    </td>
                    <td style="width:67px; text-align: right">County<span class="required"">*</span>:</td>
                    <td>
                        <select name="county">
                            <option value=""></option>
                            <option value="Beaufort" {$fn(selected($client->get_county(),'Beaufort'))}>Beaufort</option>
                            <option value="Hampton" {$fn(selected($client->get_county(),'Hampton'))}>Hampton</option>
                            <option value="Jasper" {$fn(selected($client->get_county(),'Jasper'))}>Jasper</option>
                        </select>
                    </td>
                    <td><span id="area-label">Delivery Area</span><span class="required">*</span>:
                        <select name="deliveryAreaId">
                            <option value=""></option>
END;
    
	$deliveryAreas = getall_dbDeliveryAreas();
	foreach($deliveryAreas as $deliveryArea){
		echo <<<END
            <option
                value="{$deliveryArea->get_deliveryAreaId()}"
                {$fn(selected($client->get_deliveryAreaId(),$deliveryArea->get_deliveryAreaId()))}>
                {$deliveryArea->get_deliveryAreaName()}
            </option>
END;
	}
    
    echo <<<END
                        </select>
                    </td>
                    <td>No/So:
                        <select name="noso">
                            <option value=""></option>
                            <option value="North" {$fn(selected($client->get_noso(),'North'))}>North of the Broad</option>
                            <option value="South" {$fn(selected($client->get_noso(),'South'))}>South of the Broad</option>
                        </select>
                    </td>
                    <td class="recipient">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Target D/O - Lbs:
                        <input name="target_do" type="number" step="1"
                            min="0" max="9999" style="width: 3em;" value="{$client->get_target_do()}">
                    </td>
                </tr>
            </table>
END;

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
		<tr><td>Address<span class="required">*</span>:</td><td> <input type="text" size="30" name="address" tabindex=5 value="<?PHP echo($client->get_address())?>"></td>
		<td>City<span class="required">*</span>:</td><td> <input type="text" name="city" tabindex=6 value="<?PHP echo($client->get_city())?>"></td>
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
    <script>
        $('#type-select').change(() => {
          let val = $( "#type-select option:selected" ).text()
          if (val === 'Donor') {
            $('.donor').show()
            $('.recipient').hide()
            $('#area-label').text('Pickup Area')
          } else {
            $('.donor').hide()
            $('.recipient').show()
            $('#area-label').text('Delivery Area')
          }
        })
        $('#type-select').trigger('change')
      </script>
</form>