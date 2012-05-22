<?php 
/*
 * Created on April 1, 2012
 * @author Judy Yang <jyang2@bowdoin.edu>
 */
	
session_start();
session_cache_expire(30);
include_once('database/dbVolunteers.php');
include_once('database/dbSchedules.php');
include_once('domain/ScheduleEntry.php');
include_once('domain/Volunteer.php');
?>
<html>
	<head>
		<title>Master Schedule</title>
		<!--  Choose a style sheet -->
		<link rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body>
		<div id="container">
			<?php include_once("header.php");?>
			<div id="content">
				<?php
				if ($_SESSION['access_level']<2){
					die("<p>Only team captains can edit the master schedule.</p>");
				}
				$week_days = array("Mon"=>"Monday","Tue"=>"Tuesday","Wed"=>"Wednesday",
									"Thu"=>"Thursday","Fri"=>"Friday","Sat"=>"Saturday","Sun"=>"Sunday");
				$weekly_groups = array("odd", "even");
				$monthly_groups = array("1st","2nd", "3rd", "4th", "5th");
				$area = $_GET['area'];
				if ($area=="BFT") // Beaufort is a monthly schedule
					show_master_weeks($area, $monthly_groups, $week_days);
				else  // Hilton Head and Bluffton are bi-weekly schedules
					show_master_weeks($area, $weekly_groups, $week_days);
				?>
			</div>
		</div>
		<?PHP include('footer.inc');?>			
	</body>
</html>

<?php 
	/*
	 * displays the master schedule for a given group (odd/even weeks of the year or weeks of the month)
	 * and series of days (Mon-Fri or Sat-Sun)
	 */
	function show_master_weeks($area, $groups, $days){
		echo "<br><strong>Driver Schedule</strong><br><br>";
		$areas = array("HHI"=>"Hilton Head", "SUN"=>"Bluffton", "BFT"=>"Beaufort");
		echo "Here you may schedule drivers for future routes by selecting a day and week and removing or adding drivers.";
		echo " The drivers you assign to this schedule will be autormatically assigned to each new route when it is created.<br>";
		if (sizeof($groups)>2) {
			$today = date();
			 
			echo "<br>'1st' means the first Monday of the month, '2nd' means the second Monday of the month, and so forth. ";
			echo " This allows drivers to be scheduled on the same day(s) of each month, like the 1st and 3rd Friday.<br>";
			echo ('<br><br><div><table align="center"><tr><td colspan="'.(sizeof($days)+2).'" ' .
				'bgcolor="#99B1D1" align="center" >');
			echo ($areas[$area].' Monthly Driver Schedule');
		}
		else {
			echo "<br>An 'odd week' is one of the 1st, 3rd, 5th, ... weeks of the year. ";
			echo " An 'even week' is one of the 2nd, 4th, 6th, ... weeks of the year.  This allows drivers to be scheduled on a bi-weekly basis.<br>";
			echo ('<br><table align="center"><tr><td colspan="'.(sizeof($days)+2).'" ' .
				'bgcolor="#99B1D1" align="center" >');
			echo ($areas[$area].' Bi-Weekly Driver Schedule');
		}
		
		echo ('</td></tr><tr><td bgcolor="#99B1D1"></td>');
		foreach ($days as $day => $dayname)
			echo ('<td align="center"> '. $dayname .' </td>');
		echo('<td bgcolor="#99B1D1"></td></tr>');
		
		foreach ($groups as $group){
			echo ("<tr><td bgcolor=\"#99B1D1\" valign=\"middle\">"."&nbsp;&nbsp;".$group."&nbsp;&nbsp;</td>");
			foreach ($days as $day => $dayname) {
				$master_shift = retrieve_dbSchedules($area, $day.":".$group);	
				if ($master_shift) {
					echo do_shift($master_shift);
				}
				else 
				{
					$master_shift = new ScheduleEntry($area, $day.":".$group, "", "");	
					echo do_shift($master_shift);
				}	
			}
			echo ("<td bgcolor=\"#99B1D1\" valign=\"middle\">"."&nbsp;&nbsp;".$group."&nbsp;&nbsp;</td></tr>");
		}
		echo ('<tr><td bgcolor="#99B1D1"></td>'.'<td colspan="7"></td>'.'<td bgcolor="#99B1D1"></td></tr>');
		echo ('<tr><td colspan="9" bgcolor="#99B1D1">');
			echo ("</td></tr>");
		echo "</table>";	
		do_month($area, $groups, $days);	
	}
	
	function do_month($area, $groups, $days) {
		$today = strtotime("today");
		$thisMonth=date("m",$today);
		$thisYear = date("y",$today);
		$dayses=array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
		echo '<br>For example, this schedule will assign drivers to routes as follows for the month of '; echo date("F Y",mktime(1,1,1,$thisMonth,1,$thisYear)).':';
	  	echo '<br><br><table align="center">';
	 	echo '<tr>';
		foreach ($days as $day=>$dayname)
		   echo '<td>' . $dayname . '</td>';  
		echo '</tr>';
		$dayCount = 1;
		$daytime = mktime(0,0,0,$thisMonth,1,$thisYear);
		$weekCount = 1;
		while($dayCount<=date("t",$today)){  // number of days in this month = date("t",$today)
		  	echo('<tr>');
		  	for ($i=1;$i<=7;++$i){
	  	 	  echo('<td>');
	  		  if($dayCount>date("t",$today))
	  		    continue;
	  		  else if($weekCount==1 && get_first_day($thisMonth, $thisYear)>$i)
	  		    continue;
	  	  	  else{
	  	    	echo('<strong>'.$dayCount.'</strong>');
		    	$shiftID=$thisYear.'-'.$thisMonth.'-'.date("d",mktime(0,0,0,$thisMonth,$dayCount,$thisYear));
		    	if ($area=="BFT")
		    		$week = $groups[floor(($dayCount-1) / 7)];
		    	else if (date("W",$daytime) % 2 == 0) 
		    		$week="even";
		    	else $week="odd";
		    	// echo $week.$dayses[$i-1];
		    	$driver_ids = get_drivers_scheduled($area, $week, $dayses[$i-1]);
				echo '<br>';
		    	foreach($driver_ids as $driver_id){
		    	//	echo $driver_id;
		      		$driver = retrieve_dbVolunteers($driver_id);
		      		if ($driver)
		    		echo $driver->get_first_name()." ".$driver->get_last_name()."<br>" ;
		    	}
		    	echo'</td>';
		    	++$dayCount;
		    	$daytime += 86400;
	  	  	  }
		   	}
	   		echo('</tr>'); 
	   		$weekCount+=1; 
		}
	 	echo '</table>';
	}
	
	function get_first_day($mm, $yy) {
		return date("N",mktime(0,0,0,$mm,1,$yy));
	}
	
	function do_shift($master_shift) {
		/* $master_shift is a ScheduleEntry object
		*/		
		$s= "<td>".
				"<a id=\"shiftlink\" href=\"scheduleEdit.php?area=".
				$master_shift->get_area()."&day=".$master_shift->get_day()."&group=".
				$master_shift->get_group()."\">".
				get_people_for_shift($master_shift).
			"</td>";
		return $s;
	}
	
	function get_people_for_shift($master_shift) {
		/* $master_shift is a ScheduleEntry object 
		*/
		$people=$master_shift->get_drivers(); // id's of drivers scheduled
		$p="<br>";
		for($i=0;$i<count($people);++$i) {
			$person = retrieve_dbVolunteers($people[$i]);
			if ($person)
			   $p = $p."&nbsp;".$person->get_first_name()." ".$person->get_last_name()."<br>";
			else
			   $p = $p."&nbsp;".$people[$i]."<br>";
		}
		if(count($people)==0 )
			$p=$p."&nbsp;open<br>";
		else 
		    $p=$p."&nbsp;<br>";
		return substr($p,0,strlen($p)-4) ;
	}
	
?>