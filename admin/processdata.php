<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Event Administration (events.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/
ini_set('display_errors', 1);
require_once("dbfunc.php");
$db = new DatabaseFunctions;

switch ($_POST["form_submit_type"]) {
    case "create_event":
        $eventData = array(
            "name" => $_POST["name"],
            "chair_id" => $_POST["chair_id"],
            "start_datetime" => strtotime($_POST["start_datetime"]),
            "end_datetime" => strtotime($_POST["end_datetime"]),
            "description" => $_POST["description"],
            "location" => $_POST["location"],
            "meeting_location" => $_POST["meeting_location"],
            "online_signups" => $_POST["online_signups"],
            "online_end_datetime" => strtotime($_POST["online_end_datetime"]));
        if ($db->createEvent($eventData, $_POST["tag_ids"])) { 
            $location = 'Location: events.php?view=list&month=' . idate('m') . '&year=' . date('Y');
            header($location);
            exit;
        } else { echo "Event failed to create. Try again."; }
        break;
    case "edit_event":
        $status = $_POST["status"];
        if ($status == 0) {
            $_POST["pros"] = $_POST["cons"] = $_POST["do_again"] = "Fill me in!";
            $_POST["funds_raised"] = 0.00;
            $_POST["service_hours"] = $_POST["admin_hours"] = $_POST["social_hours"] = 0;
        }
        if (strtotime($_POST["end_datetime"]) <= time() && $_POST["status"] == 0) { $status = 1; }
        else if (strtotime($_POST["end_datetime"]) >= time() && $_POST["status"] == 1) { $status = 0; }
        $eventData = array(
            "name" => $_POST["name"],
            "chair_id" => $_POST["chair_id"],
            "start_datetime" => strtotime($_POST["start_datetime"]),
            "end_datetime" => strtotime($_POST["end_datetime"]),
            "description" => $_POST["description"],
            "location" => $_POST["location"],
            "meeting_location" => $_POST["meeting_location"],
            "online_signups" => $_POST["online_signups"],
            "online_end_datetime" => strtotime($_POST["online_end_datetime"]),
            "status" => $status,
            "pros" => $_POST["pros"],
            "cons" => $_POST["cons"],
            "do_again" => $_POST["do_again"],
            "funds_raised" => $_POST["funds_raised"],
            "service_hours" => $_POST["service_hours"],
            "admin_hours" => $_POST["admin_hours"],
            "social_hours" => $_POST["social_hours"]
            );
        if ($db->editEvent($_POST["event_id"], $eventData, $_POST["tag_ids"])) { 
            $location = 'Location: events.php?view=event&id=' . $_POST["event_id"];
            header($location);
            exit;
        } else { echo "Event failed to edit. Try again."; }
        break;
    default:
        echo "No Form Submit Type Passed.";
}
?>

