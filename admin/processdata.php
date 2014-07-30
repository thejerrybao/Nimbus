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
        }
        break;
    default:
        echo "No Form Submit Type Passed.";
}
?>

