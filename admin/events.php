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
                            <select class="searchSelect" id="chair">
                                <?php 
                                $members = $db->getMembers();
                                foreach ($members as $member) {
                                    echo "<option value=\"" . $member['user_id'] . "\">" . $member['last_name'] . ", " . $member['first_name'] . "</option>";
                                }
                                ?>
                            </select>
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