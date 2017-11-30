<?PHP
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and 
 * Allen Tucker. This program is part of Homeplate, which is free software.
 * It comes with absolutely no warranty.  You can redistribute and/or
 * modify it under the terms of the GNU Public License as published
 * by the Free Software Foundation (see <http://www.gnu.org/licenses/).
*/
/*
 *	affiliateForm.inc
 *  a form for a volunteer to be added or edited in the database
 *	@author Allen Tucker
 *	@version 4/1/2012
 */

	if ($id=='new') {
	  echo('<p><strong>Area Input Form</strong><br />');
	  echo('Here you can add a new area to the database. </p>');
    }
    else {
	   echo('<p><strong>Area Information Form</strong><br />');
	   echo('Here you can edit or delete an area.</p><p>');
    }

?>
<form method="POST">
	<input type="hidden" name="old_id" value=<?PHP echo("\"".$id."\"");?>>
	
	<input type="hidden" name="_form_submit" value="1">
<p>(<span style="font-size:x-small;color:FF0000">*</span> indicates required information.)

<p>	<?php 

		echo 'Name<span style="font-size:x-small;color:FF0000">*</span>: <input type="text" name="deliveryAreaName" tabindex=1 value="'.$deliveryArea->get_deliveryAreaName().'">';
		?>
</p>
<p>	<?php 
		 if ($id=="new")
		     echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to add this new area.<br /><br />');
		  else{
		     echo('Hit <input type="submit" value="Submit" name="Submit Edits"> to complete these changes.<br /><br />');
							 
			 }
		  if ($id != 'new' && $_SESSION['access_level']>=2) {
			echo ('<input type="checkbox" name="deleteMe" value="DELETE"> Check this box and then hit ' .
				'<input type="submit" value="Delete" name="Delete Entry"> to delete this area from the database. <br />');
				
			$allVols = getall_dbClientsForArea($id);
			    if ($allVols){
							echo "<div><strong>Associated with:</strong><br />";
                          foreach($allVols as $vol)
                            if ($vol->get_id()!="")
                              echo $vol->get_id() . ", ";
							  
							  echo "</div>";
							 }
		}
?>
</p>

</form>