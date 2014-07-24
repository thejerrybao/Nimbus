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
ini_set('display_errors', 1);
$db = new DatabaseFunctions;
?>

<!DOCTYPE html>
    <head>
        <title>Project Nimbus - Event Management</title>
        <link rel="stylesheet" type="text/css" href="../css/chosen.css">
    </head>
    <body>
        <?php switch($_GET["page"]):
            case "create": ?>
                <h3 class="title">Create Event</h3>
                <div class="form">
                    <form action="" method="post" enctype="multipart/form-data" id="createEvent">
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
                            <textarea name="description" id="description" form="createEvent" rows="6" cols="40" required></textarea><br />
                        <p class="formLabel">Location</p>
                            <input type="text" name="location" id="location" size="50" required><br />  
                        <p class="formLabel">Meeting Location</p>
                            <input type="text" name="meeting_location" id="meeting_location" size="50" required><br />  
                        <p class="formLabel">All Day?</p>
                            <input type="hidden" name="all_day" id="all_day" size="50" value="0"><br />  
                            <input type="checkbox" name="all_day" id="all_day" size="50" value="1"><br />  
                        <p class="formLabel">Tags</p>
                            <select class="tagSelect" name="tag_id" id="tag_id" multiple required>
                                <?php $tags = $db->getTags();
                                foreach ($tags as $tag) {
                                    echo "<option value=\"" . $tag['tag_id'] . "\">" 
                                    . $tag['name'] . " (" . $tag['abbr'] . ")</option>";
                                } ?>
                            </select>
                        <p class="formLabel">Online Sign-ups?</p>
                            <input type="hidden" name="online_signups" id="online_signups" size="50" value="0">
                            <input type="checkbox" name="online_signups" id="online_signups" size="50" value="1" checked>
                        <p class="formLabel">Online Sign-up End Date</p>
                            <input type="datetime-local" name="online_end_datetime" id="online_end_datetime" required><br />
                    </form>
                </div>
        <?php break; ?>
        <?php case "list": ?>
        <?php break; ?>
        <?php default: ?>
            <h1>No query given to PHP.</h1>
        <?php endswitch; ?>

        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/chosen.jquery.min.js"></script>
        <script src="../js/events.js"></script>
    </body>
</html>