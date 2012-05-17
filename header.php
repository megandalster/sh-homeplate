<!--
/*
 * Copyright 2012 by Hartley Brody, Richardo Hopkins, Nicholas Wetzel, and Allen 
 * Tucker.  This program is part of Homeplate, which is free software.  It comes 
 * with absolutely no warranty.  You can redistribute and/or modify it under the 
 * terms of the GNU Public License as published by the Free Software Foundation 
 * (see <http://www.gnu.org/licenses/).
*/
-->

<style type="text/css">
h1 {padding-left: 0px; padding-right:165px;}
</style>
<div id="header">
<!--<br><br><img src="images/Header.gif" align="center"><br>
<h1><br><br>RMH Homebase <br></h1>-->

</div>

<div align="left" id="navigationLinks">

<?PHP
	//Log-in security
	//If they aren't logged in, display our log-in form.
	if(!isset($_SESSION['logged_in'])){
		include('login_form.php');
		die();
	}
	else if($_SESSION['logged_in']){

		/**
		 * Set our permission array for guests, volunteers, social workers, and managers.
		 * If a page is not specified in the permission array, anyone logged into the system
		 * can view it. If someone logged into the system attempts to access a page above their
		 * permission level, they will be sent back to the home page.
		 */
		//pages guests can view
		$permission_array['index.php']=0;
		$permission_array['about.php']=0;
		$permission_array['volunteerEdit.php']=0;
		//pages volunteers can view
		$permission_array['viewRoutes.php']=1;
		$permission_array['viewStop.php']=1;
		$permission_array['viewStop1.php']=1;
		$permission_array['viewStop2.php']=1;
		$permission_array['viewStop3.php']=1;
		//additional pages team captains can view
		$permission_array['clientEdit.php']=2;
		$permission_array['editSchedule.php']=2;
		$permission_array['volunteerSearch.php']=2;
		$permission_array['clientSearch.php']=2;
		$permission_array['generateReports.php']=2;
		//additional pages program coordinators can view
		$permission_array['viewReports.php']=2;
		$permission_array['exportData.php']=2;

		//Check if they're at a valid page for their access level.
		$current_page = substr($_SERVER['PHP_SELF'],1);
		if($permission_array[$current_page]>$_SESSION['access_level']){
			//in this case, the user doesn't have permission to view this page.
			//we redirect them to the index page.
			echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
			//note: if javascript is disabled for a user's browser, it would still show the page.
			//so we die().
			die();
		}

		//This line gives us the path to the html pages in question, useful if the server isn't installed @ root.
		$path = strrev(substr(strrev($_SERVER['SCRIPT_NAME']),strpos(strrev($_SERVER['SCRIPT_NAME']),'/')));
		
		$today=date("y-m-d");
		$week_later = date("y-m-d", strtotime("+1 week"));
		
		//they're logged in and session variables are set.
		echo('<a href="'.$path.'index.php">home</a>');
		if ($_SESSION['access_level']==0) // guests
		    echo('<a href="volunteerEdit.php?id=new'.'"> | apply </a>');
		
		if($_SESSION['access_level']>=1) // drivers, team captains, and officers 
		    echo('<a href="'.$path.'viewRoutes.php?area='.$_SESSION['_area'].'&date='.$today.'"> | routes</a>');
		
	    if($_SESSION['access_level']>=2) { // team captains and board members
	    	echo('<a href="'.$path.'volunteerSearch.php?area='.$_SESSION['_area'].'"> | volunteers</a>');
	    	echo('<a href="'.$path.'clientSearch.php?area='.$_SESSION['_area'].'"> | donors and recipients</a>');
	    	echo '<a href="'.$path.'viewReports.php?id='.$_SESSION['_area'].'&date='.$today.'&enddate='.$week_later.'"> | reports</a>';	    
	    //	echo '<a href="'.$path.'log.php"> | log</a>';
	    }
	 //   echo('<a href="'.$path.'help.php?helpPage='.$current_page.'" target="_BLANK"> | help</a>');
	//	echo('<a href="'.$path.'about.php"> | about</a>');
		echo('<a href="'.$path.'logout.php"> | logout</a>');
	}
?>
</div>
<!-- End Header -->