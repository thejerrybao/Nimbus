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
session_start();
if (!isset($_SESSION['cki_rf_user_id'])) { header('Location: ../login.php'); }
else if ($_SESSION['cki_rf_access'] == 0) { echo "You don't have access to this page."; exit; }
date_default_timezone_set('America/Los_Angeles');
if (empty($_GET['month']) && empty($_GET['year']) && $_GET['view'] == "list") {
    $location = 'Location: events.php?view=list&month=' . idate('m') . '&year=' . date('Y');
    header($location); 
    exit;
}
$months = ["", "January", "February", "March", "April", "May",
"June", "July", "August", "September", "October", "November", "December"];
require_once("dbfunc.php");
$eventdb = new EventFunctions;
$tagdb = new TagFunctions;
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

    <title>Project Nimbus - Event Administration</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- events.php CSS -->
    <link href="css/events.css" rel="stylesheet" type="text/css">

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
        <? $page = "events"; 
            require_once("nav.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <? switch ($_GET['view']):
                case "create": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Create Event</h1>
                    </div>
                </div>
                <? if (isset($_POST['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_POST['successmsg'] ?></div><? } ?>
                <? if (isset($_POST['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_POST['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Create New Event</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="create_event">
                                    <div class="form-group">
                                        <label>Event Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">        
                                        <label>Chair</label>
                                        <select name="chair_id" class="form-control" required>
                                            <? $users = $userdb->getUsers("active"); ?>
                                            <? foreach ($users as $user) { ?>
                                                <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Start Date and Time</label>
                                        <input type="datetime-local" name="start_datetime" id="start-datetime" class="form-control" value="<?= date("Y-m-d\TH:i:s", time()); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>End Date and Time</label>
                                        <input type="datetime-local" name="end_datetime" id="end-datetime" class="form-control" value="<?= date("Y-m-d\TH:i:s", strtotime('+3 hours')); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" form="create_event" rows="3" class="form-control" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" name="location" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Meeting Location</label>
                                        <input type="text" name="meeting_location" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tags</label>
                                        <select name="tag_ids[]" class="form-control" multiple required>
                                            <? $tags = $tagdb->getTags(); ?>
                                            <? foreach ($tags as $tag) { ?>
                                                <option value="<?= $tag['tag_id'] ?>"><?= $tag['abbr'] ?> (<?= $tag['name'] ?>)</option>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Online Sign-ups?</label>
                                        <input type="hidden" name="online_signups" value="0">
                                        <input type="checkbox" name="online_signups" value="1" checked>
                                    </div>
                                    <div class="form-group">
                                        <label>Online Sign-up End Date</label>
                                        <input type="datetime-local" name="online_end_datetime" id="online-end-datetime" class="form-control" value="<?= date("Y-m-d\TH:i:s", strtotime('-1 day')); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Create Event</button>
                                    <button type="reset" class="btn btn-primary">Reset Fields</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Help Panel</div>
                            <div class="panel-body">
                                <label>Event Name</label>
                                <p>Enter the name of the event here.</p>
                                <label>Chair</label>
                                <p>Select the person that is chairing this event.</p> 
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "list": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Events List</h1>
                    </div>
                </div>
                <? if (isset($_POST['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_POST['successmsg'] ?></div><? } ?>
                <? if (isset($_POST['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_POST['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="events.php" method="get" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="view" value="list">
                                <select name="month" class="form-control" style="width: 20%; display: inline;">
                                    <? for ($i = 1; $i <= 12; $i++) { ?>
                                        <? if ($_GET['month'] == $i) { ?>
                                            <option value="<?= $i ?>" selected><?= $months[$i] ?></option>
                                        <? } else { ?>
                                            <option value="<?= $i ?>"><?= $months[$i] ?></option>
                                        <? } ?> 
                                    <? } ?>
                                </select>
                                <select name="year" class="form-control" style="width: 10%; display: inline;">
                                    <? for ($i = idate("Y"); $i >= 2006; $i--) { ?>
                                        <? if ($_GET['year'] == $i) { ?>
                                            <option value="<?= $i ?>" selected><?= $i ?></option>
                                        <? } else { ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <? } ?> 
                                    <? } ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-xs">Get Events</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <? $events = $eventdb->getEventsByMonth(mktime(0, 0, 0, $_GET['month'], 0, $_GET['year'])); ?>
                        <? if ($events) { ?>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th id="event-name">Name</th>
                                    <th id="event-chair">Chair</th>
                                    <th id="event-status">Status</th>
                                    <th id="event-start-datetime">Start Date</th>
                                    <th id="event-end-datetime">End Date</th>
                                    <th id="event-location">Location</th>
                                    <th id="event-num-attendees"># Attendees</th>
                                </tr>
                            </thead>
                            <tbody>
                            <? foreach ($events as $event) {
                                $chair = $userdb->getUserInfo($event['chair_id']); 
                                if ($event['end_datetime'] <= time() && $event['status'] == 0) { 
                                    $eventdb->setEventStatus($event['event_id'], 1);
                                    $event['status'] = 1;
                                }
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
                                <td><?= $event['location'] ?></td>
                                <td><?= $event['num_attendees'] ?></td></tr>
                            <? } ?>
                            </tbody>
                        </table>
                        <? } else { ?>
                            <h2>No events found for the specified month and year.</h2>
                        <? } ?>
                    </div>
                </div>
            <? break; ?>
            <? case "event": ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No event ID specified.</h2> 
                <? } else { 
                    $event = $eventdb->getEventInfo($_GET['id']);
                    if ($event['end_datetime'] <= time() && $event['status'] == 0) { 
                        $eventdb->setEventStatus($event['event_id'], 1);
                        $event['status'] = 1;
                    }
                } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Event Information</h1>
                        <? if ($event['status'] > 1) { ?>
                        <fieldset disabled>
                        <? } ?>
                        <form action="events.php" method="get" enctype="multipart/form-data" style="display: inline;">
                            <input type="hidden" name="view" value="edit">
                            <input type="hidden" name="id" value="<?= $event['event_id'] ?>">
                            <div class="form-group" style="display: inline;">
                                <button type="submit" class="btn btn-primary" style="margin-bottom: 20px;">Edit Event</button>
                            </div>
                        </form>
                        <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline;">
                            <input type="hidden" name="form_submit_type" value="delete_event">
                            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                            <div class="form-group" style="display: inline;">
                                <button type="submit" class="btn btn-primary" style="margin-bottom: 20px;">Delete Event</button>
                            </div>
                        </form>
                        <? if ($event['status'] > 1) { ?>
                        </fieldset>
                        <? } ?>
                        <? if ($event['status'] == 0) { ?>
                        <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline;">
                            <div class="form-group" style="display: inline;">
                                <input type="hidden" name="form_submit_type" value="post_event">
                                <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                <button type="submit" class="btn btn-primary" style="margin-bottom: 20px;">Override Post-Event</button>
                            </div>
                        </form>
                        <? } else if ($event['status'] == 1) { ?>
                        <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline;">
                            <div class="form-group" style="display: inline;">
                                <input type="hidden" name="form_submit_type" value="confirm_event">
                                <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                <button type="submit" class="btn btn-primary" style="margin-bottom: 20px;">Confirm Event</button>
                            </div>
                        </form>
                        <? } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">All Event Data</div>
                            <div class="panel-body">
                                <label>Status</label>
                                <p><? switch ($event['status']) {
                                        case 0:
                                            echo "Pre-Event";
                                            break;
                                        case 1:
                                            echo "Post-Event";
                                            break;
                                        case 2:
                                            echo "Confirmed";
                                            break;
                                        case 3:
                                            echo "Verified";
                                            break;
                                        default:
                                            echo "Incorrect Status Number";
                                    } ?></p>
                                <label>Event Name</label>
                                <p><?= $event['name'] ?></p>
                                <label>Chair</label>
                                <p><? $chair = $userdb->getUserInfo($event['chair_id']) ?>
                                    <?= $chair['first_name'] ?> <?= $chair['last_name'] ?></p>
                                <label>Start Date and Time</label>
                                <p><?= date("F j, Y, g:i a", $event['start_datetime']); ?></p>
                                <label>End Date and Time</label>
                                <p><?= date("F j, Y, g:i a", $event['end_datetime']); ?></p>
                                <label>Description</label>
                                <p><?= $event['description'] ?></p>
                                <label>Location</label>
                                <p><?= $event['location'] ?></p>
                                <label>Meeting Location</label>
                                <p><?= $event['meeting_location'] ?></p>
                                <label>Tags</label>
                                <p><? foreach ($event['tag_ids'] as $tag_id) { 
                                    $tag = $tagdb->getTag($tag_id) ?>
                                <?= $tag['abbr'] ?> (<?= $tag['name'] ?>)</br>
                                <? } ?></p>
                                <label>Online Signups?</label>
                                <p><? if ($event['online_signups']) { ?> Yes 
                                <? } else { ?> No <? } ?></p>
                                <? if ($event['status'] > 0) { ?>
                                    <label>Pros of the Event</label>
                                    <p><?= $event['pros'] ?></p>
                                    <label>Cons of the Event</label>
                                    <p><?= $event['cons'] ?></p>
                                    <label>Should we do this event again?</label>
                                    <p><?= $event['do_again'] ?></p>
                                    <label>Funds Raised</label>
                                    <p>$<?= $event['funds_raised'] ?></p>
                                    <label>Service Hours Per Person</label>
                                    <p><?= $event['service_hours'] ?></p>
                                    <label>Admin Hours Per Person</label>
                                    <p><?= $event['admin_hours'] ?></p>
                                    <label>Social Hours Per Person</label>
                                    <p><?= $event['social_hours'] ?></p>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Event Attendees</div>
                            <div class="panel-body">
                                <label># Attendees:</label>
                                <?= $event['num_attendees'] ?>
                                <p><? $attendeeEmails = "";
                                    if ($eventAttendees = $eventdb->getEventAttendees($event['event_id'])) { 
                                        foreach ($eventAttendees as $eventAttendee) {
                                            $attendeeEmails .= $eventAttendee['email'] . "; "; ?>
                                            <?= $eventAttendee['first_name'] ?> <?= $eventAttendee['last_name'] ?><br />
                                        <? } ?>
                                    <? } else { ?>No Attendees<? } ?></p>
                                <label>Attendee Emails</label>
                                <textarea rows="3" class="form-control"><?= $attendeeEmails ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "edit": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edit Event</h1>
                    </div>
                </div>
                <? if (isset($_POST['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_POST['successmsg'] ?></div><? } ?>
                <? if (isset($_POST['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_POST['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No event ID specified.</h1>
                <? } else { 
                    $event = $eventdb->getEventInfo($_GET['id']);
                    if ($event['end_datetime'] <= time() && $event['status'] == 0) { 
                        $eventdb->setEventStatus($event['event_id'], 1);
                        $event['status'] = 1;
                    }
                } ?>
                <div class="row">
                    <? if ($event['status'] < 2) { ?>
                        <div class="col-lg-8">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Edit Current Event</div>
                                <div class="panel-body">
                                    <form action="processdata.php" method="post" enctype="multipart/form-data" id="edit_event">
                                        <input type="hidden" name="form_submit_type" value="edit_event">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <input type="hidden" name="status" value="<?= $event['status'] ?>">
                                        <div class="form-group">
                                            <label>Event Name</label>
                                            <input type="text" name="name" class="form-control" value="<?= $event['name']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Chair</label>
                                            <select name="chair_id" class="form-control" id="chair_id" required>
                                                <? $users = $userdb->getUsers("active"); ?>
                                                <? foreach ($users as $user) { ?>
                                                    <? if ($event['chair_id'] == $user['user_id']) { ?>
                                                        <option value="<?= $user['user_id'] ?>" selected><?= $user['first_name'] ?> <?= $user['last_name'] ?></option> 
                                                    <? } else { ?>
                                                        <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                                    <? } ?>
                                                <? } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Start Date and Time</label>
                                            <input type="datetime-local" name="start_datetime" class="form-control" value="<?= date("Y-m-d\TH:i:s", $event['start_datetime']); ?>" required>
                                        </div>    
                                        <div class="form-group">
                                            <label>End Date and Time</label>
                                            <input type="datetime-local" name="end_datetime" class="form-control" value="<?= date("Y-m-d\TH:i:s", $event['end_datetime']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" form="edit_event" rows="3" required><?= $event['description']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Location</label>
                                            <input type="text" name="location" class="form-control" value="<?= $event['location']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Meeting Location</label>
                                            <input type="text" name="meeting_location" class="form-control" value="<?= $event['meeting_location']; ?>" required>
                                        </div> 
                                        <div class="form-group">
                                            <label>Tags</label>
                                            <select name="tag_ids[]" class="form-control" multiple required>
                                                <? $tags = $tagdb->getTags(); ?>
                                                <? foreach ($tags as $tag) { ?>
                                                    <? if (in_array($tag['tag_id'], $event['tag_ids'])) { ?>
                                                        <option value="<?= $tag['tag_id'] ?>" selected><?= $tag['abbr'] ?> (<?= $tag['name'] ?>)</option>
                                                    <? } else { ?>
                                                        <option value="<?= $tag['tag_id'] ?>"><?= $tag['abbr'] ?> (<?= $tag['name'] ?>)</option>
                                                    <? } ?>
                                                <? } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Online Sign-ups?</label>
                                            <input type="hidden" name="online_signups" value="0">
                                            <? if ($event['online_signups']) { ?>
                                                <input type="checkbox" name="online_signups" value="1" checked>
                                            <? } else { ?>
                                                <input type="checkbox" name="online_signups" value="1">
                                            <? } ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Online Sign-up End Date</label>
                                            <input type="datetime-local" name="online_end_datetime" class="form-control" value="<?= date("Y-m-d\TH:i:s", $event['online_end_datetime']); ?>" required>
                                        </div>
                                        <? if ($event['status'] == 1) { ?>
                                        <div class="form-group">
                                            <label>Pros of the Event</label>
                                            <textarea name="pros" class="form-control" form="edit_event" rows="3" required><?= $event['pros'] ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Cons of the Event</label>
                                            <textarea name="cons" class="form-control" form="edit_event" rows="3" required><?= $event['cons'] ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Should we do the event again?</label>
                                            <textarea name="do_again" class="form-control" form="edit_event" rows="3" required><?= $event['do_again'] ?></textarea>
                                        </div>
                                        <label>Funds Raised</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="number" step="any" min="0" name="funds_raised" class="form-control" value="<?= $event['funds_raised'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Service Hours Per Person</label>
                                            <input type="number" min="0" name="service_hours" class="form-control" value="<?= $event['service_hours'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Admin Hours Per Person</label>
                                            <input type="number" min="0" name="admin_hours" class="form-control" value="<?= $event['admin_hours'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Social Hours Per Person</label>
                                            <input type="number" min="0" name="social_hours" class="form-control" value="<?= $event['social_hours'] ?>" required>
                                        </div>
                                        <? } ?>
                                        <button type="submit" class="btn btn-primary">Edit Event</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel panel-info">
                                <div class="panel-heading">Help Panel</div>
                                <div class="panel-body">
                                    <label>Event Name</label>
                                    <p>Enter the name of the event here.</p>
                                    <label>Chair</label>
                                    <p>Select the person that is chairing this event.</p> 
                            </div>
                        </div>
                    <? } else { ?>
                        <h2>Can't edit a completed event! If you need to edit, ask the secretary to un-complete the event.</h2>
                    <? } ?>
                </div>
            <? break; ?>
            <? case "calendar": ?>
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

    <!-- Open Source datejs File -->
    <script src="js/date.js"></script>

    <!-- events.php JS -->
    <script src="js/events.js"></script>

</body>
</html>
?>