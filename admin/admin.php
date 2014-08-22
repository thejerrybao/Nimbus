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

$page = "admin";
$pageTitle = "General Administration";
$customCSS = true;
$customJS = true;
?>

<!DOCTYPE html>
<html lang="en">

<? require_once("header.php"); ?>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <? require_once("nav.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <? switch ($_GET['view']):
                case "access": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Manage Member Access</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
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
                                            <td class="checkbox-series-checkbox"><input type="checkbox" name="user_ids[]" value="<?= $activeUser["user_id"] ?>" class="checkbox_series"></td>
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
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
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
                                    <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline; margin-right: 5px;">
                                        <input type="hidden" name="form_submit_type" value="verify_event_approve">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" class="btn btn-primary btn-xs">&nbsp;Approve&nbsp;</button>
                                    </form>
                                    <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline;">
                                        <input type="hidden" name="form_submit_type" value="verify_event_deny">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" class="btn btn-primary btn-xs">&nbsp;&nbsp;&nbsp;Deny&nbsp;&nbsp;&nbsp;</button>
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

    <? require_once("scripts.php") ?>

</body>
</html>
?>