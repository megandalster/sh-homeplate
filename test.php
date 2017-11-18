<html>
	<head>
		<title>
			Homeplate
		</title>
		<link rel="stylesheet" href="styles.css" type="text/css" />
	</head>
	<body>
	<?PHP
		include_once('database/dbVolunteers.php');
     	include_once('domain/Volunteer.php');
					
	$theVol = retrieve_dbVolunteers("rlevine123.123.1234");
	$todayUTC = time();
	$mondaylastweek = strtotime('last monday', strtotime('last monday', $todayUTC));
	echo "start:" . date("Y-m-d H:i:s", $mondaylastweek) . "<br />";
	
	$theVol->set_lastTripDate(date('Y-m-d', $mondaylastweek));
	$theVol->set_tripCount(($theVol->get_tripCount() + 1));
	
	insert_dbVolunteersTest($theVol);
	
							//update_dbVolunteers($theVol);
	?>