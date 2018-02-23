<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/
/*
 *	deviceForm.inc
 *  a form for a device to be added or edited in the database
 *	@author Hartley Brody
 *	@version 4/4/2012
 */


if($_SESSION['access_level']>=2){
   echo('<p><strong>Tablet Information Form</strong><br />');
   echo('Here you can edit or delete a tablet in the database. ');
   echo '(<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)';
}
else {
    echo("<p id=\"error\">You do not have sufficient permissions to edit tablets in the database.</p>");
	include('footer.inc');
	echo('</div></div></body></html>');
	die();
}

?>
<form method="POST">
<script>
$(function() {
	$( "#date_activated" ).datepicker();
});
</script>
	<input type="hidden" name="old_id" value=<?PHP echo("\"".$id."\"");?>>
	<input type="hidden" name="_form_submit" value="1">

<p>	Tablet id:   
<?PHP 
	if ($id=="new") 
		echo '<input type="text" size="35" name="id" tabindex=1 value="">';
	else echo $id;
	
		echo ('&nbsp;&nbsp;&nbsp;&nbsp;Status: ');
		echo('<select name="status">');
		echo ('<option value=""></option>');
		echo ('<option value="active"');if ($device->get_status()=='active') echo (' SELECTED'); echo('>active</option>');
		echo ('<option value="inactive"');if ($device->get_status()=='inactive') echo (' SELECTED'); echo('>inactive</option>');
		echo ('<option value="out of service"');if ($device->get_status()=='out of service') echo (' SELECTED'); echo('>out of service</option>');
		echo('</select>');
		echo ('&nbsp;&nbsp;&nbsp;&nbsp;Base: ');
		echo('<select name="base">');
		echo ('<option value=""></option>');
		echo ('<option value="Hilton Head"');if ($device->get_base()=='Hilton Head') echo (' SELECTED'); echo('>Hilton Head</option>');
		echo ('<option value="Bluffton"');if ($device->get_base()=='Bluffton') echo (' SELECTED'); echo('>Bluffton</option>');
		echo ('<option value="Beaufort"');if ($device->get_base()=='Beaufort') echo (' SELECTED'); echo('>Beaufort</option>');
		echo ('<option value="Office"');if ($device->get_base()=='Office') echo (' SELECTED'); echo('>Office Use</option>');
		echo('</select>');
		
		echo '<p>Owner: <input type="text" id="owner" name="owner" value="'.
				$device->get_owner(). '"></p>';
		
		echo '<p>Date Activated: <input type="text" id="date_activated" name="date_activated" size="10" value="';
		echo $device->get_date_activated();
		echo '"></p>';
		echo '<p>Notes: <input type="text" id="notes" name="notes" size="50" value="'.
				$device->get_notes(). '"></p>';
		
	echo('<input type="hidden" name="_submit_check" value="1"><p>');
	if ($id=="new")
		echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to add this new tablet.<br /><br />');
	else
		echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to complete these changes.<br /><br />');
	if ($id != 'new' && $_SESSION['access_level']>=2) {
		echo ('<input type="checkbox" name="deleteMe" value="DELETE"> Check this box and then hit ' .
				'<input type="submit" value="Delete" name="Delete Entry"> to delete this tablet from the database. <br />' );
	}
?>
</form>