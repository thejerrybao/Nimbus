<?php 
	ini_set('display_errors', 1);
	session_start();
	require_once("../admin/dbfunc.php");
	$eventdb = new EventFunctions;
	$eventdb->addEventAttendee($_POST['event_id'], $_SESSION['nimbus_user_id']);
	$location = "Location: Calendar.php"; 
    header($location);
?>


