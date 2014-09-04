<?php 
	ini_set('display_errors', 1);
	session_start();
	require_once("../admin/dbfunc.php");
	$eventdb = new EventFunctions;
	$eventdb->deleteEventAttendee($_POST['event_id'], $_SESSION['nimbus_user_id']);
    $location = "Location: Calendar.php?event_id=".$_POST['event_id']; 
    header($location);
?>
 