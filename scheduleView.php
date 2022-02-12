<?php 
/*
 * Created on April 1, 2012
 * @author Judy Yang <jyang2@bowdoin.edu>
 */
	
session_start();
//session_cache_expire(30);
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
		
		<style type="text/css">
			.calendarDayBox{
				border: solid 1px #264576;font-size:10px;  padding:0px; width:180px;
			}
			
			.calendarDayBoxHeader{
				border: solid 1px #c9c9c9;
				width:60px;
				font-size:9px;
				color:#0c0c0c;
			}
			
			.calendarDayBoxData{
			}
		</style>
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
				$weekly_groups = array("odd"=>"odd", "even"=>"even");
				$monthly_groups = array("1"=>"1st","2"=>"2nd", "3"=>"3rd", "4"=>"4th", "5"=>"5th");
				$area = $_GET['area'];
				$areas = array("HHI"=>"Hilton Head", "SUN"=> "Bluffton", "BFT" => "Beaufort");
				echo "<p><strong>".$areas[$area]." Master Schedule</strong> (View others: ";
				foreach ($areas as $otherArea=>$areaName) {
				   if ($otherArea!=$area)
				   	  echo "<a href=scheduleView.php?area=".$otherArea."> $areaName</a>";	
				}
				echo ")<br><br>";
				if ($area=="BFT" || $area=="HHI") // Beaufort and Hilton Head are monthly schedules
					show_master_weeks($areas, $area, $monthly_groups, $week_days);
				else  // Bluffton is a bi-weekly schedule
					show_master_weeks($areas, $area, $weekly_groups, $week_days);
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
	function show_master_weeks($areas, $area, $groups, $days){
		echo "This schedule automatically assigns the crew whenever you create a new route.";
		echo "  An <b>open</b> entry means that no crew will be assigned to that route.<b>*</b><p>";
		
		if (sizeof($groups)>2) {
			$today = date('y-m-d');
			 
			echo "('1st' means the first Monday of the month, '2nd' means the second Monday of the month, and so forth. ";
			echo " This allows volunteers to be scheduled on the same day(s) of each month, like the 1st and 3rd Friday.)";
			echo ('<br><br><table><tr><td colspan="'.(sizeof($days)+2).'" ' .
				'bgcolor="#bbe1d1" align="center" >');
			echo ($areas[$area].' Monthly Driver Schedule');
		}
		else {
			echo "(An 'odd week' is one of the 1st, 3rd, 5th, ... weeks of the year. ";
			echo " An 'even week' is one of the 2nd, 4th, 6th, ... weeks of the year.  This allows volunteers to be scheduled on a bi-weekly basis.)";
			echo ('<br><br><table><tr><td colspan="'.(sizeof($days)+2).'" ' .
				'bgcolor="#bbe1d1" align="center" >');
			echo ('<b>'.$areas[$area].' Bi-Weekly Volunteer Schedule</b>');
		}
		
		echo ('</td></tr><tr><td bgcolor="#bbe1d1"></td>');
		foreach ($days as $day => $dayname)
			echo ('<td align="left"><b>'. $dayname .'</b></td>');
		echo('<td bgcolor="#bbe1d1"></td></tr>');
		
		foreach ($groups as $group=>$group_name){
			echo ("<tr><td bgcolor=\"#bbe1d1\" align=\"center\" valign=\"top\"><b>".$group_name."</b></td>");
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
			echo ("<td bgcolor=\"#bbe1d1\" align=\"center\" valign=\"top\"><b>".$group_name."</b></td></tr>");
		}
		echo ('<tr><td bgcolor="#bbe1d1"></td>'.'<td colspan="7"></td>'.'<td bgcolor="#bbe1d1"></td></tr>');
		echo ('<tr><td colspan="9" bgcolor="#bbe1d1">');
			echo ("</td></tr>");
		echo "</table>";	
		//do_month($area, $groups, $days);
		write_RollingWeeklyView($area, $groups, $days);
		echo "<br><b>*</b>To change an entry on this schedule, you must <a href='volunteerSearch.php'>edit</a> volunteer records individually.  ";
		echo "Whenever you change a volunteer's record, this schedule will be automatically updated.";	
	}
	
	function write_RollingWeeklyView($area, $groups, $days){
		$today = strtotime("today")+1209600;
		
		
		$startDate = date("Y-m-d",strtotime('monday this week'));
		$thisMonday = date("m",$today);
		$endDate = date("Y-m-d",strtotime('monday this week +4 weeks -1 day'));
		
		$thisMonth=date("m",strtotime('monday this week'));
		$thisYear = date("Y",strtotime('monday this week'));
		$thisStartMonday = date("d",$thisMonday);
		
		
		$dayses=array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
		
		echo "<p>Advance view beginning Monday " .   date('F', strtotime('monday this week')) . " " . date("d",strtotime('monday this week')) . ", "  . $thisYear . "&nbsp;&nbsp; Odd/Even week</p>";
		
		
		echo "<table border='1'><tr>";
	
		foreach ($dayses as $weekHeaderText){
			echo "<td style='font-weight:bold;'>";
		  echo $weekHeaderText;
		  echo "</td>";
		}
		echo "</tr>";
		
		$currentDate = $startDate;
		
		for($w=0; $w <4; $w++){
			echo "<tr>";
			for ($x=0; $x<7; $x++){
			   echo "<td valign='top' class='calendarDayBox'";
			   if($currentDate == date("Y-m-d",strtotime('now'))){
				echo " style='background-color:#c0c0c0;'";
			   }
			   echo ">";
			   echo "<div class='calendarDayBoxHeader'>";
			   echo $currentDate;
			   echo "</div>";
			   
			  
			   
			   echo "<div class='calendarDayBoxData'>";
      
      
				$dayCount = date("d",strtotime($currentDate));
			   $shiftID=$thisYear.'-'.$thisMonth.'-'.date("d",mktime(0,0,0,$thisMonth,$dayCount,$thisYear));
			   
			   	
				
		    	if ($area=="BFT" || $area=="HHI")
		    		//$week = $groups[floor(($dayCount-1) / 7)];
		    		$week = substr($groups[floor(($dayCount-1) / 7) + 1],0,1);
		    	else if (date("W", strtotime($currentDate)) % 2 == 0) 
		    		$week="even";
		    	else $week="odd";
		    
		    	$driver_ids = get_drivers_scheduled($area, $week, $dayses[$x]);
				foreach($driver_ids as $driver_id){
		    		$driver = retrieve_dbVolunteers($driver_id);
		      		if ($driver)
		    			echo $driver->get_first_name()." ".$driver->get_last_name()."<br>" ;
		    		else echo $driver_id;
		    	}
				
			   echo "</div>";
			   
			   echo "</td>\n";
			   
				
				//$currentDate = date('d m Y', strtotime($currentDate . ' + 1 day'));
				
				$currentDate = date('Y-m-d', strtotime($currentDate. ' + 1 days'));
				$thisYear = date("Y",strtotime($currentDate));
				$thisMonth=date("m",strtotime($currentDate));
			}
			echo "</tr>";
		}
		
		
		echo "</table>";
	}
	
	function do_month($area, $groups, $days) {
		$today = strtotime("today")+1209600;
		$thisMonth=date("m",$today);
		$thisYear = date("y",$today);
		$dayses=array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
		echo '<p>View a month in advance: <strong>'; echo date("F Y",mktime(1,1,1,$thisMonth,1,$thisYear))."</strong>";
	  	echo '<br><br><table>';
	 	echo '<tr>';
		foreach ($days as $day=>$dayname)
		   echo '<td><b>' . $dayname . '</b></td>';  
		echo '</tr>';
		$dayCount = 1;
		$daytime = mktime(0,0,0,$thisMonth,1,$thisYear);
		$weekCount = 1;
		while($dayCount<=date("t",$today)){  // number of days in this month = date("t",$today)
		  	echo('<tr>');
		  	for ($i=1;$i<=7;++$i){
	  	 	  echo('<td valign="top">');
	  		  if($dayCount>date("t",$today))
	  		    continue;
	  		  else if($weekCount==1 && get_first_day($thisMonth, $thisYear)>$i)
	  		    continue;
	  	  	  else{
	  	    	echo('<strong>'.$dayCount.'</strong>');
		    	$shiftID=$thisYear.'-'.$thisMonth.'-'.date("d",mktime(0,0,0,$thisMonth,$dayCount,$thisYear));
		    	if ($area=="BFT" ||$area=="HHI")
		    		//$week = $groups[floor(($dayCount-1) / 7)];
		    		$week = substr($groups[floor(($dayCount-1) / 7) + 1],0,1);
		    	else if (date("W",$daytime) % 2 == 0) 
		    		$week="even";
		    	else $week="odd";
		    	// echo $week.$dayses[$i-1];
		    	$driver_ids = get_drivers_scheduled($area, $week, $dayses[$i-1]);
				echo '<br>';
		    	foreach($driver_ids as $driver_id){
		    		$driver = retrieve_dbVolunteers($driver_id);
		      		if ($driver)
		    			echo $driver->get_first_name()." ".$driver->get_last_name()."<br>" ;
		    		else echo $driver_id;
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
		$s= "<td valign='top'>".
			//	"<a id=\"shiftlink\" href=\"scheduleEdit.php?area=".
			//	$master_shift->get_area()."&day=".$master_shift->get_day()."&group=".
			//	$master_shift->get_group()."\">".
				get_people_for_shift($master_shift).
			"</td>";
		return $s;
	}
	
	function get_people_for_shift($master_shift) {
		/* $master_shift is a ScheduleEntry object 
		*/
		$people=$master_shift->get_drivers(); // id's of drivers scheduled
		$p="";
		for($i=0;$i<count($people);++$i) {
			$person = retrieve_dbVolunteers($people[$i]);
			if ($person)
			   $p = $p."&nbsp;".$person->get_first_name()." ".$person->get_last_name()."<br>";
			else
			   $p = $p."&nbsp;".$people[$i]."<br>";
		}
		if(count($people)==0 )
			$p=$p."&nbsp;<strong>open</strong><br>";
		else 
		    $p=$p."&nbsp;<br>";
		return substr($p,0,strlen($p)-4) ;
	}
	
?>