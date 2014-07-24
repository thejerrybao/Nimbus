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
if (empty($_GET["month"]) && empty($_GET["year"]) && $_GET["view"] == "list") {
    $location = 'Location: events.php?view=list&month=' . idate('m') . '&year=' . date('Y');
    header($location); 
    exit;
}
$months = ["", "January", "February", "March", "April", "May",
"June", "July", "August", "September", "October", "November", "December"];
require_once("dbfunc.php");
$db = new DatabaseFunctions;
?>

<!DOCTYPE html>
    <head>
        <title>Project Nimbus - Event Management</title>
        <link rel="stylesheet" type="text/css" href="../css/chosen.css">
    </head>
    <body>
        <?php switch ($_GET["view"]):
            case "create": ?>
                <h3 class="title">Create Event</h3>
                <div class="form">
                    <form action="processdata.php" method="post" enctype="multipart/form-data" id="create_event">
                        <input type="hidden" name="form_submit_type" id="form_submit_type" value="create_event">
                        <p class="formLabel">Event Name:</p>
                            <input type="text" name="name" id="name" size="50" required><br />        
                        <p class="formLabel">Chair:</p>
                            <select class="chairSelect" name="chair_id" id="chair_id" required>
                                <?php $members = $db->getMembers();
                                foreach ($members as $member) {
                                    echo "<option value=\"" . $member['user_id'] . "\">" 
                                    . $member['last_name'] . ", " . $member['first_name'] . "</option>";
                                } ?>
                            </select>
                        <p class="formLabel">Start Date and Time:</p>
                            <input type="datetime-local" name="start_datetime" id="start_datetime" required><br />
                        <p class="formLabel">End Date and Time:</p>
                            <input type="datetime-local" name="end_datetime" id="end_datetime" required><br />
                        <p class="formLabel">Description</p>
                            <textarea name="description" id="description" form="create_event" rows="6" cols="40" required></textarea><br />
                        <p class="formLabel">Location</p>
                            <input type="text" name="location" id="location" size="50" required><br />  
                        <p class="formLabel">Meeting Location</p>
                            <input type="text" name="meeting_location" id="meeting_location" size="50" required><br /> 
                        <p class="formLabel">Tags</p>
                            <select class="tagSelect" name="tag_id" id="tag_id" multiple required>
                                <?php $tags = $db->getTags();
                                foreach ($tags as $tag) {
                                    echo "<option value=\"" . $tag['tag_id'] . "\">" 
                                    . $tag['name'] . " (" . $tag['abbr'] . ")</option>";
                                } ?>
                            </select>
                        <p class="formLabel">Online Sign-ups?</p>
                            <input type="hidden" name="online_signups" id="online_signups" value="0">
                            <input type="checkbox" name="online_signups" id="online_signups" value="1" checked>
                        <p class="formLabel">Online Sign-up End Date</p>
                            <input type="datetime-local" name="online_end_datetime" id="online_end_datetime" required><br />
                        <input type="submit">
                    </form>
                </div>
            <?php break; ?>
            <?php case "list": ?>
                <h3 class="title">Event Management</h3>
                    <form action="events.php?page=list" method="get" enctype="multipart/form-data" id="create_event">
                        <input type="hidden" name="page" value="list">
                        <select class="monthSelect" name="month" id="month">
                            <?php for ($i = 1; $i <= 12; $i++) {
                                if ($_GET["month"] == $i) { echo "<option value=\"" . $i . "\" selected>" . $months[$i] . "</option>"; }
                                else { echo "<option value=\"" . $i . "\">" . $months[$i] . "</option>"; }
                            } ?> 
                        </select>
                        <select class="yearSelect" name="year" id="year">
                            <?php for ($i = idate("Y"); $i >= 2006; $i--) {
                                if ($_GET["year"] == $i) { echo "<option value=\"" . $i . "\" selected>" . $i . "</option>"; }
                                else { echo "<option value=\"" . $i . "\">" . $i . "</option>"; }
                            } ?> 
                        </select>
                        <input type="submit">
                    </form>
                    <table class="event_list">
                        <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Name</th>
                                <th>Chair</th>
                                <th>Status</th>
                                <th>Location</th>
                                <th>Meeting Location</th>
                                <th># Attendees</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $events = $db->getEventsByMonth(mktime(0, 0, 0, $_GET["month"], 1, $_GET["year"]));
                            foreach ($events as $event) {
                                $chair = $db->getUserInfo($event["chair_id"]);
                                if ($event["end_datetime"] <= time()) { 
                                    $db->setEventStatus($event["event_id"], 1);
                                    $event["status"] = 1;
                                }
                                switch ($event["status"]) {
                                    case 0:
                                        $status = "Pre-Event";
                                        break;
                                    case 1:
                                        $status = "Post-Event";
                                        break;
                                    case 2:
                                        $status = "Confirmed";
                                        break;
                                    case 3:
                                        $status = "Verified";
                                        break;
                                    default:
                                        $status = "Incorrect Status Number";
                                }
                                echo "<tr><td>" . date("F j, Y, g:i a", $event["start_datetime"]) . "</td>";
                                echo "<td>" . date("F j, Y, g:i a", $event["end_datetime"]) . "</td>";
                                echo "<td>" . $event["name"] . "</td>";
                                echo "<td>" . $chair["first_name"] . " " . $chair["last_name"] . "</td>";
                                echo "<td>" . $status . "</td>";
                                echo "<td>" . $event["location"] . "</td>";
                                echo "<td>" . $event["meeting_location"] . "</td>";
                                echo "<td>" . $event["num_attendees"] . "</td>";
                            } ?>
                        </tbody>
                    </table>
            <?php break; ?>
            <?php case "event": 
                    if (empty($_GET["id"])) { echo "<h1>No event ID specified.</h1>"; }
                    else { 
                        $event = $db->getEventInfo($_GET["id"]);
                        if ($event["end_datetime"] <= time()) { 
                            $db->setEventStatus($event["event_id"], 1);
                            $event["status"] = 1;
                        }
                    } ?>
                    <h3 class="title">Event Information</h3>
                    <div class="form">
                        <form action="processdata.php" method="post" enctype="multipart/form-data" id="edit_event">
                            <input type="hidden" name="form_submit_type" id="form_submit_type" value="edit_event">
                            <p class="formLabel">Event Name:</p>
                                <input type="text" name="name" id="name" size="50" value="<?php echo $event['name']; ?>" required><br />  
                            <p class="formLabel">Chair:</p>
                                <select class="chairSelect" name="chair_id" id="chair_id" required>
                                    <?php $members = $db->getMembers();
                                    foreach ($members as $member) {
                                        if ($event['chair_id'] == $member['user_id']) {
                                            echo "<option value=\"" . $member['user_id'] . "\" selected>" 
                                                . $member['last_name'] . ", " . $member['first_name'] . "</option>"; 
                                        } else { echo "<option value=\"" . $member['user_id'] . "\">" 
                                        . $member['last_name'] . ", " . $member['first_name'] . "</option>"; }
                                    } ?>
                                </select>
                            <p class="formLabel">Start Date and Time:</p>
                                <input type="datetime-local" name="start_datetime" id="start_datetime" value="<?php echo date("Y-m-d\TH:i:s", $event["start_datetime"]); ?>" required><br />
                            <p class="formLabel">End Date and Time:</p>
                                <input type="datetime-local" name="end_datetime" id="end_datetime" value="<?php echo date("Y-m-d\TH:i:s", $event["end_datetime"]); ?>" required><br />
                            <p class="formLabel">Description</p>
                                <textarea name="description" id="description" form="create_event" rows="6" cols="40" required><?php echo $event["description"]; ?></textarea><br />
                            <p class="formLabel">Location</p>
                                <input type="text" name="location" id="location" size="50" value="<?php echo $event["location"]; ?>" required><br />  
                            <p class="formLabel">Meeting Location</p>
                                <input type="text" name="meeting_location" id="meeting_location" size="50" value="<?php echo $event["meeting_location"]; ?>" required><br />  
                            <p class="formLabel">Tags</p>
                                <select class="tagSelect" name="tag_id" id="tag_id" multiple required>
                                    <?php $tags = $db->getTags();
                                    foreach ($tags as $tag) {
                                        echo "<option value=\"" . $tag['tag_id'] . "\">" 
                                        . $tag['name'] . " (" . $tag['abbr'] . ")</option>";
                                    } ?>
                                </select>
                            <p class="formLabel">Online Sign-ups?</p>
                                <input type="hidden" name="online_signups" id="online_signups" value="0">
                                <?php if ($event["online_signups"]): ?>
                                    <input type="checkbox" name="online_signups" id="online_signups" value="1" checked>
                                <?php else: ?>
                                    <input type="checkbox" name="online_signups" id="online_signups" value="1">
                                <?php endif; ?>
                            <p class="formLabel">Online Sign-up End Date</p>
                                <input type="datetime-local" name="online_end_datetime" id="online_end_datetime" value="<?php echo date("Y-m-d\TH:i:s", $event["online_end_datetime"]); ?>" required><br />
                            <input type="submit">
                        </form>
                        <?php switch ($event["status"]):
                            case 0: ?>
                                <form action="processdata.php" method="post" enctype="multipart/form-data" class="form_set_status">
                                    <input type="hidden" name="set_status" value="1">
                                    <input type="submit" id="post_event" value="Override Auto Post-Event">
                                </form>
                            <?php break; ?>
                            <?php case 1: ?>
                                <form action="processdata.php" method="post" enctype="multipart/form-data" class="form_set_status">
                                    <input type="hidden" name="set_status" value="2">
                                    <input type="submit" id="confirm_event" value="Confirm Event">
                                </form>
                            <?php break; ?>
                            <?php default: ?>
                                <h1>Event has a status out of its range.</h1>
                        <?php endswitch; ?>
                    </div>
            <?php break; ?>
            <?php default: ?>
                <h1>No query given to PHP.</h1>
        <?php endswitch; ?>

        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/chosen.jquery.min.js"></script>
        <script src="../js/events.js"></script>
    </body>
</html>