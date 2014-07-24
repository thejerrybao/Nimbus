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
?>

<!DOCTYPE html>
    <head>
        <title>Project Nimbus - Event Management</title>
    </head>
    <body>
        <?php if ($_GET["page"] == "create") : ?>
            <h3 class="title">Create Event</h3>
            <div class="form">
                <form action="" method="post" enctype="multipart/form-data" id="createEvent">
                    <p class="formLabel"></p>
                        <input type="text" name="name" id="name" size="50" required><br />
        <?php else if ($_GET["page"] == "list") : ?>
        <?php else : ?>
            <h1>No query given to PHP.</h1>
        <?php endif; ?>
    </body>
</html>