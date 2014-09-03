<?php
/** Project Name: Nimbus (Circle K Club Management)
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
if (!isset($_SESSION['nimbus_user_id'])) { header('Location: ../login.php'); }
else if ($_SESSION['nimbus_access'] == 0) { echo "You don't have access to this page."; exit; }
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

$page = "events";
$pageTitle = "Event Administration";
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
                case "create": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Create Event</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Create New Event</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data" id="create_event">
                                    <input type="hidden" name="form_submit_type" value="create_event">
                                    <div class="form-group">
                                        <label>Event Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">        
                                        <label>Chair</label>
                                        <select name="chair_id" id="form-event-chair" class="form-control" required>
                                            <? $users = $userdb->getUsers("active"); ?>
                                            <? foreach ($users as $user) { ?>
                                                <? if ($user['user_id'] == $_SESSION['cki_rf_user_id']) { ?>
                                                    <option value="<?= $user['user_id'] ?>" selected><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                                <? } else { ?>
                                                    <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                                <? } ?>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Start Date and Time</label>
                                        <input type="datetime-local" name="start_datetime" id="start-datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", time()); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>End Date and Time</label>
                                        <input type="datetime-local" name="end_datetime" id="end-datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", strtotime('+3 hours')); ?>" required>
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
                                        <select name="tag_ids[]" class="form-control" id="form-event-tags" multiple required>
                                            <? $tags = $tagdb->getTags(); ?>
                                            <? foreach ($tags as $tag) { ?>
                                                <option value="<?= $tag['tag_id'] ?>"><?= $tag['abbr'] ?> (<?= $tag['name'] ?>)</option>
                                            <? } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Online Sign-ups?</label>
                                        <input type="hidden" name="online_signups" value="0">
                                        <input type="checkbox" name="online_signups" class="online-signups-checkbox" value="1" checked>
                                    </div>
                                    <div class="form-group online-signups">
                                        <label>Online Sign-up End Date and Time</label>
                                        <input type="datetime-local" name="online_end_datetime" id="online-end-datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", strtotime('-1 day')); ?>" required>
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
                                <p>Name of the event.</p>
                                <label>Chair</label>
                                <p>Select the person that is chairing this event.</p> 
                                <label>Start Date and Time</label>
                                <p>Start date and time of the event.</p>
                                <label>End Date and Time</label>
                                <p>End date and time of the event.</p>
                                <label>Description</label>
                                <p>Event Description. Be as descriptive as you like!</p>
                                <label>Location</label>
                                <p>Actual location of the event.</p>
                                <label>Meeting Location</label>
                                <p>Location of where to meet up for the event.</p>
                                <label>Tags</label>
                                <p>Event tags.</p>
                                <label>Online Sign-ups?</label>
                                <p>Select whether or not people can sign-up online.</p>
                                <label>Online Sign-up End Date and Time</label>
                                <p>End date and time of online sign-ups.</p>
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
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
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
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Event Information <a href="events.php?view=list"><button class="btn btn-primary btn-back">Back to Events List</button></a></h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
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
                                <label>Online Sign-ups?</label>
                                <p><? if ($event['online_signups']) { ?> Yes 
                                <? } else { ?> No <? } ?></p>
                                <label>Online Sign-ups End Date and Time</label>
                                <p><?= date("F j, Y, g:i a", $event['online_end_datetime']); ?></p>
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
                        <? if ($event['status'] < 2) { ?>
                        <div class="panel panel-info">
                            <div class="panel-heading">Add Attendees</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_event_attendees">
                                    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                    <select name="user_ids[]" id="form-add-event-attendees" class="form-control" multiple required>
                                        <? $users = $userdb->getUsers("active"); ?>
                                        <? $eventAttendeeIDs = $eventdb->getEventAttendees($event['event_id'], true); ?>
                                        <? foreach ($users as $user) { ?>
                                            <? if (!in_array($user['user_id'], $eventAttendeeIDs)) { ?>
                                                <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                            <? } ?>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add Attendees</button>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">Delete Attendees</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="delete_event_attendees">
                                    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                    <select name="user_ids[]" id="form-delete-event-attendees" class="form-control" multiple required>
                                        <? foreach ($eventAttendees as $eventAttendee) { ?>
                                            <option value="<?= $eventAttendee['user_id'] ?>"><?= $eventAttendee['first_name'] ?> <?= $eventAttendee['last_name'] ?></option>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Delete Attendees</button>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Event Options</div>
                            <div class="panel-body">
                                <form action="events.php" method="get" enctype="multipart/form-data" style="display: inline;">
                                    <input type="hidden" name="view" value="edit">
                                    <input type="hidden" name="id" value="<?= $event['event_id'] ?>">
                                    <div class="form-group" style="display: inline;">
                                        <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Edit Event</button>
                                    </div>
                                </form>
                                <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline;">
                                    <input type="hidden" name="form_submit_type" value="delete_event">
                                    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                    <div class="form-group" style="display: inline;">
                                        <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Delete Event</button>
                                    </div>
                                </form>
                                <? if ($event['status'] == 1) { ?>
                                <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline;">
                                    <div class="form-group" style="display: inline;">
                                        <input type="hidden" name="form_submit_type" value="confirm_event">
                                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                                        <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Confirm Event</button>
                                    </div>
                                </form>
                                <form action="events.php" method="get" enctype="multipart/form-data" style="display: inline;">
                                    <input type="hidden" name="view" value="overridehours">
                                    <input type="hidden" name="id" value="<?= $event['event_id'] ?>">
                                    <div class="form-group" style="display: inline;">
                                        <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Override Event Hours</button>
                                    </div>
                                </form>
                                <form action="events.php" method="get" enctype="multipart/form-data" style="display: inline;">
                                    <input type="hidden" name="view" value="otherattendees">
                                    <input type="hidden" name="id" value="<?= $event['event_id'] ?>">
                                    <div class="form-group" style="display: inline;">
                                        <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Manage Outside Attendees</button>
                                    </div>
                                </form>
                                <? } ?>
                            </div>
                        </div>
                        <? } ?>
                    </div>
                </div>
            <? break; ?>
            <? case "edit": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edit Event</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
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
                                            <select name="chair_id" class="form-control" id="form-event-chair" required>
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
                                            <input type="datetime-local" name="start_datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", $event['start_datetime']); ?>" required>
                                        </div>    
                                        <div class="form-group">
                                            <label>End Date and Time</label>
                                            <input type="datetime-local" name="end_datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", $event['end_datetime']); ?>" required>
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
                                            <select name="tag_ids[]" class="form-control" id="form-event-tags" multiple required>
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
                                                <input type="checkbox" name="online_signups" class="online-signups-checkbox" value="1" checked>
                                            <? } else { ?>
                                                <input type="checkbox" name="online_signups" class="online-signups-checkbox" value="1">
                                            <? } ?>
                                        </div>
                                        <div class="form-group online-signups" <? if (!$event['online_signups']) { ?> style="display: none;" <? } ?>>
                                            <label>Online Sign-up End Date and Time</label>
                                            <input type="datetime-local" name="online_end_datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", $event['online_end_datetime']); ?>" required>
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
                                    <p>Name of the event.</p>
                                    <label>Chair</label>
                                    <p>Select the person that is chairing this event.</p> 
                                    <label>Start Date and Time</label>
                                    <p>Start date and time of the event.</p>
                                    <label>End Date and Time</label>
                                    <p>End date and time of the event.</p>
                                    <label>Description</label>
                                    <p>Event Description. Be as descriptive as you like!</p>
                                    <label>Location</label>
                                    <p>Actual location of the event.</p>
                                    <label>Meeting Location</label>
                                    <p>Location of where to meet up for the event.</p>
                                    <label>Tags</label>
                                    <p>Event tags.</p>
                                    <label>Online Sign-ups?</label>
                                    <p>Select whether or not people can sign-up online.</p>
                                    <label>Online Sign-up End Date and Time</label>
                                    <p>End date and time of online sign-ups.</p>
                                    <label>Pros of the Event</label>
                                    <p>What was good about the event?</p>
                                    <label>Cons of the Event</label>
                                    <p>What was bad about the event?</p>
                                    <label>Should we do this again?</label>
                                    <p>Talk about whether or not the event is worth doing again.</p>
                            </div>
                        </div>
                    <? } else { ?>
                        <h2>Can't edit a completed event! If you need to edit, ask the secretary to un-complete the event.</h2>
                    <? } ?>
                </div>
            <? break; ?>
            <? case "calendar": ?>
            <? break; ?>
            <? case "overridehours" ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Override Event Hours <a href="events.php?view=event&id=<?= $_GET['id'] ?>"><button class="btn btn-primary btn-back">Back to Event Info</button></a></h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No event ID specified.</h2>
                <? } else { $eventOverrideHours = $eventdb->getOverrideHours($_GET['id']); } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Manage Override Event Hours</div>
                            <div class="panel-body">
                                <? if ($eventOverrideHours) { ?>
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                <? } ?>
                                    <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th id="override-member-name">Name</th>
                                            <th id="override-member-service">Service Hours</th>
                                            <th id="override-member-admin">Admin Hours</th>
                                            <th id="override-member-social">Social Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <? if ($eventOverrideHours) { ?>
                                        <? $i = 0; ?>
                                        <? foreach ($eventOverrideHours as $overrideMember) { ?>
                                            <tr>
                                                <td id="override-member-name-cell"><?= $overrideMember['first_name'] ?> <?= $overrideMember['last_name'] ?></td>
                                                <input type="hidden" name="members_override[<?= $i ?>][user_id]" value="<?= $overrideMember['user_id'] ?>">
                                                <td><input type="number" min="0" name="members_override[<?= $i ?>][hours][service_hours]" class="form-control" value="<?= $overrideMember['service_hours'] ?>" required></td>
                                                <td><input type="number" min="0" name="members_override[<?= $i ?>][hours][admin_hours]" class="form-control" value="<?= $overrideMember['admin_hours'] ?>" required></td>
                                                <td><input type="number" min="0" name="members_override[<?= $i ?>][hours][social_hours]" class="form-control" value="<?= $overrideMember['social_hours'] ?>" required></td>
                                            </tr>
                                            <? $i++; ?>
                                        <? } ?>
                                    <? } else { ?>
                                        <tr>
                                            <td>No Overridden Members</td>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                        </tr>
                                    <? } ?>
                                    </tbody>
                                </table>
                                <? if ($eventOverrideHours) { ?>
                                <input type="hidden" name="form_submit_type" value="set_override_hours">
                                <input type="hidden" name="event_id" value="<?= $_GET['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-block">Set Override Hours</button>
                            </form>
                            <? } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Add Member to Override</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_override_hours">
                                    <input type="hidden" name="event_id" value="<?= $_GET['id'] ?>">
                                    <label>Select Members to Add</label>
                                    <select name="user_ids[]" class="form-control" id="form-add-override-hours" multiple required>
                                        <? $users = $userdb->getUsers("active"); ?>
                                        <? $overrideIDs = $eventdb->getOverrideHours($_GET['id'], true); ?>
                                        <? foreach ($users as $user) { ?>
                                            <? if (!in_array($user['user_id'], $overrideIDs)) { ?>
                                                <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                            <? } ?>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add Members</button>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">Delete Member from Override</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="delete_override_hours">
                                    <input type="hidden" name="event_id" value="<?= $_GET['id'] ?>">
                                    <label>Select Members to Delete</label>
                                    <select name="user_ids[]" class="form-control" id="form-delete-override-hours" multiple required>
                                        <? foreach ($eventOverrideHours as $overrideMember) { ?>
                                            <option value="<?= $overrideMember['user_id'] ?>"><?= $overrideMember['first_name'] ?> <?= $overrideMember['last_name'] ?></option>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Delete Members</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "otherattendees": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Manage Other Attendees <a href="events.php?view=event&id=<?= $_GET['id'] ?>"><button class="btn btn-primary btn-back">Back to Event Info</button></a></h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No event ID specified.</h2>
                <? } else { $eventOtherAttendees = $eventdb->getEventOtherAttendees($_GET['id']); } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Other Attendees List</div>
                            <div class="panel-body">
                                    <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th id="other-attendee-name">Name</th>
                                            <th id="other-attendee-club">Club</th>
                                            <th id="other-attendee-kiwanis-branch">Kiwanis Branch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <? if ($eventOtherAttendees) { ?>
                                        <? foreach ($eventOtherAttendees as $otherAttendee) { 
                                            switch ($otherAttendee['kiwanis_branch']) {
                                                case 0:
                                                    $otherAttendee['kiwanis_branch'] = "Kiwanis";
                                                    break;
                                                case 1:
                                                    $otherAttendee['kiwanis_branch'] = "Circle K";
                                                    break;
                                                case 2:
                                                    $otherAttendee['kiwanis_branch'] = "Key Club";
                                                    break;
                                                case 3:
                                                    $otherAttendee['kiwanis_branch'] = "Other";
                                            } ?>
                                            <tr>
                                                <td><?= $otherAttendee['first_name'] ?> <?= $otherAttendee['last_name'] ?></td>
                                                <td><?= $otherAttendee['club'] ?></td>
                                                <td><?= $otherAttendee['kiwanis_branch'] ?></td>
                                            </tr>
                                        <? } ?>
                                    <? } else { ?>
                                        <tr>
                                            <td>No Other Attendees</td>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                        </tr>
                                    <? } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Add Other Attendee</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_other_attendee">
                                    <input type="hidden" name="event_id" value="<?= $_GET['id'] ?>">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Club</label>
                                        <input type="text" name="club" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Kiwanis Branch</label>
                                        <select name="kiwanis_branch" class="form-control" required>
                                            <option value="0">Kiwanis</option>
                                            <option value="1">Circle K</option>
                                            <option value="2">Key Club</option>
                                            <option value="3">Other</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Attendee</button>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">Delete Other Attendees</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="delete_other_attendees">
                                    <input type="hidden" name="event_id" value="<?= $_GET['id'] ?>">
                                    <label>Select Other Attendees to Delete</label>
                                    <select name="ids[]" class="form-control" id="form-delete-other-attendees" multiple required>
                                        <? foreach ($eventOtherAttendees as $otherAttendee) { ?>
                                            <option value="<?= $otherAttendee['id'] ?>"><?= $otherAttendee['first_name'] ?> <?= $otherAttendee['last_name'] ?></option>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Delete Atendees</button>
                                </form>
                            </div>
                        </div>
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

    <? require_once("scripts.php"); ?>

</body>
</html>