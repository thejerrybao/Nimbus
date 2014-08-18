<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Tech Chair Administration (admin.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['cki_rf_user_id'])) { header('Location: ../login.php'); }
else if ($_SESSION['cki_rf_access'] == 0) { echo "You don't have access to this page."; exit; }
require_once("dbfunc.php");
$eventdb = new EventFunctions;
$userdb = new UserFunctions;
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Project Nimbus - General Administration</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- admin.php CSS -->
    <link href="css/admin.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <? $page = "admin"; 
            require_once("nav.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <? switch ($_GET["view"]):
                case "access": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Manage Member Access</h1>
                    </div>
                </div>
                <? if (isset($_POST['errormsg'])) { ?><div class="alert alert-danger"><?= $_POST['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                        <? $activeUsers = $userdb->getUsers("active"); ?>
                            <div class="panel-heading">Set Member Access</div>
                            <div class="panel-body">
                                <h4>Select members below to give access to.</h4>
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="set_access">
                                    <div class="form-group">
                                    <? if ($activeUsers) { ?>
                                        <? $i = 0; ?>
                                        <table>
                                        <? foreach ($activeUsers as $activeUser) {  ?>
                                            <? if ($i % 4 == 0) { ?>
                                                <tr>
                                            <? } ?>
                                            <td class="checkbox-series-name"><?= $activeUser["first_name"] ?> <?= $activeUser["last_name"] ?></td>
                                            <td class="checkbox-series-checkbox"><input type="checkbox" name="non_active_users[]" value="<?= $activeUser["user_id"] ?>" class="checkbox_series"></td>
                                            <? if ($i % 4 == 3) { ?>
                                            </tr>
                                            <? }
                                            $i++; ?>
                                        <? } ?>
                                        </table>
                                        <select name="access" class="form-control" style="margin-top: 10px; width: 20%; display: inline;">
                                            <option value="0">General Member</option>
                                            <option value="1">Board Member</option>
                                            <option value="2">MRP Chair</option>
                                            <option value="3">Secretary</option>
                                            <option value="4">Technology Chair/Administrator</option>
                                        </select> 
                                        <button type="submit" class="btn btn-primary" style="display: inline;">Set Access</button>
                                    <? } else { ?>
                                        <h2>No active members exist.</h2>
                                    <? } ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "verify": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Verify Events</h1>
                    </div>
                </div>
                <? if (isset($_POST['errormsg'])) { ?><div class="alert alert-danger"><?= $_POST['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <? $events = $eventdb->getConfirmedEvents(); ?>
                        <? if ($events) { ?>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th id="event-name">Name</th>
                                    <th id="event-chair">Chair</th>
                                    <th id="event-status">Status</th>
                                    <th id="event-start-datetime">Start Date</th>
                                    <th id="event-end-datetime">End Date</th>
                                    <th id="event-verify">Verify Event?</th>
                                </tr>
                            </thead>
                            <tbody>
                            <? foreach ($events as $event) {
                                $chair = $userdb->getUserInfo($event['chair_id']);
                                switch ($event['status']) {
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
                                } ?>
                                <tr><td><a href="events.php?view=event&id=<?= $event['event_id'] ?>"><?= $event['name'] ?></a></td>
                                <td><?= $chair['first_name'] ?> <?= $chair['last_name'] ?></td>
                                <td><?= $status ?></td>
                                <td><?= date("F j, Y, g:i a", $event['start_datetime']) ?></td>
                                <td><?= date("F j, Y, g:i a", $event['end_datetime']) ?></td>
                                <td>
                                    <form action="processdata.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="form_submit_type" value="verify_event">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" class="btn btn-primary btn-xs">Verify Event</button>
                                    </form>
                                </td></tr>
                            <? } ?>
                            </tbody>
                        </table>
                        <? } else { ?>
                            <h2>No confirmed events found.</h2>
                        <? } ?>
                    </div>
                </div>
            <? break; ?>
            <? default: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>No view was selected.</h1>
                    </div>
                </div>
            <? endswitch; ?>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    <!-- admin.php JS -->
    <script src="js/admin.js"></script>

</body>
</html>
?>