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
			<?PHP include('footer.inc');?>		
		</div>
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
		echo "Here you may schedule drivers by selecting a day and week and removing or adding drivers where there are <strong>openings</strong>.";
		echo " The drivers you assign to this schedule will be autormatically assigned to each new route when it is created.<br>";
		if (sizeof($groups)>2) { 
			echo "<br>'1st' means the first week of the month, '2nd' means the second week of the month, and so forth. ";
			echo " This allows drivers to be scheduled on the same day(s) of each month, like the 1st and 3rd Friday for example.<br>";
			echo ('<br><table align="left"><tr><td colspan="'.(sizeof($days)+2).'" ' .
				'bgcolor="#99B1D1" align="center" >');
			echo ($areas[$area].' Monthly Driver Schedule');
		}
		else {
			echo "<br>An 'odd week' is one of the 1st, 3rd, 5th, ... weeks of the year. ";
			echo " An 'even week' is one of the 2nd, 4th, 6th, ... weeks of the year.  This allows drivers to be scheduled on a bi-weekly basis.<br>";
			echo ('<br><table align="left"><tr><td colspan="'.(sizeof($days)+2).'" ' .
				'bgcolor="#99B1D1" align="center" >');
			echo ($areas[$area].' Bi-Weekly Driver Schedule');
		}
		
		echo ('</td></tr><tr><td bgcolor="#99B1D1"></td>');
		foreach ($days as $day => $dayname)
			echo ('<td align="center"> '. $dayname .' </td>');
		echo('<td bgcolor="#99B1D1"></td></tr>');
		
		foreach ($groups as $group){
			echo ("<tr><td bgcolor=\"#99B1D1\" valign=\"middle\">"."&nbsp;&nbsp;".$group."&nbsp;&nbsp;"."</td>");
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
			echo ("<td bgcolor=\"#99B1D1\" valign=\"middle\">"."&nbsp;&nbsp;".$group."&nbsp;&nbsp;"."</td></tr>");
		}
		echo ('<tr><td bgcolor="#99B1D1"></td>'.'<td colspan="'.sizeof($days).'"></td>'.'<td bgcolor="#99B1D1"></td></tr>');
		echo ('<tr><td colspan="'.(sizeof($days)+2).'" ' .
				'bgcolor="#99B1D1" align="center" >');
			echo ("</td></tr>");
		echo "</table>";
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
		$slots=max(sizeof($people), 2);		  // allow a minimum of 2 drivers per shift
	//	if(!$people[0])
	//		array_shift($people);
		$p="<br>";
		for($i=0;$i<count($people);++$i) {
			$person = retrieve_dbVolunteers($people[$i]);
			if ($person)
			   $p = $p."&nbsp;".$person->get_first_name()." ".$person->get_last_name()."<br>";
			else
			   $p = $p."&nbsp;".$people[$i]."<br>";
		}
		if($slots-count($people)>0 )
			$p=$p."&nbsp;openings (".($slots-count($people)).")<br>";
		else if (count($people) == 0)
		    $p=$p."&nbsp;<br>";
		return substr($p,0,strlen($p)-4) ;
	}
	
?>