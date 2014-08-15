<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Form Data Processing (processdata.php)
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
$committeedb = new CommitteeFunctions;
$eventdb = new EventFunctions;
$userdb = new UserFunctions;

switch ($_POST['form_submit_type']) {
    case "create_event":
        $eventData = array(
            "name" => $_POST['name'],
            "chair_id" => $_POST['chair_id'],
            "start_datetime" => strtotime($_POST['start_datetime']),
            "end_datetime" => strtotime($_POST['end_datetime']),
            "description" => $_POST['description'],
            "location" => $_POST['location'],
            "meeting_location" => $_POST['meeting_location'],
            "online_signups" => $_POST['online_signups'],
            "online_end_datetime" => strtotime($_POST['online_end_datetime']));
        if ($eventdb->createEvent($eventData, $_POST['tag_ids'])) { 
            $location = 'Location: events.php?view=list&month=' . idate('m') . '&year=' . date('Y');
            header($location);
            exit;
        } else { echo "Event failed to create. Try again."; }
        break;
    case "edit_event":
        $status = $_POST['status'];
        if ($status == 0) {
            $_POST['pros'] = $_POST['cons'] = $_POST['do_again'] = "Fill me in!";
            $_POST['funds_raised'] = 0.00;
            $_POST['service_hours'] = $_POST['admin_hours'] = $_POST['social_hours'] = 0;
        }
        if (strtotime($_POST['end_datetime']) <= time() && $_POST['status'] == 0) { $status = 1; }
        else if (strtotime($_POST['end_datetime']) >= time() && $_POST['status'] == 1) { $status = 0; }
        $eventData = array(
            "name" => $_POST['name'],
            "chair_id" => $_POST['chair_id'],
            "start_datetime" => strtotime($_POST['start_datetime']),
            "end_datetime" => strtotime($_POST['end_datetime']),
            "description" => $_POST['description'],
            "location" => $_POST['location'],
            "meeting_location" => $_POST['meeting_location'],
            "online_signups" => $_POST['online_signups'],
            "online_end_datetime" => strtotime($_POST['online_end_datetime']),
            "status" => $status,
            "pros" => $_POST['pros'],
            "cons" => $_POST['cons'],
            "do_again" => $_POST['do_again'],
            "funds_raised" => $_POST['funds_raised'],
            "service_hours" => $_POST['service_hours'],
            "admin_hours" => $_POST['admin_hours'],
            "social_hours" => $_POST['social_hours']);
        if ($eventdb->editEvent($_POST['event_id'], $eventData, $_POST['tag_ids'])) { 
            $location = 'Location: events.php?view=event&id=' . $_POST['event_id'];
            header($location);
            exit;
        } else { echo "Event failed to edit. Try again."; }
        break;
    case "delete_event":
        if ($eventdb->deleteEvent($_POST['event_id'])) {
            $location = 'Location: events.php?view=list';
            header($location);
            exit;
        } else { echo "Event failed to delete. Try again."; }
        break;
    case "add_user":
        if (!isset($_POST['username'])) {
            $_POST['username'] = $_POST['password'] = $_POST['phone'] = "";
        }
        $memberData = array(
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "username" => $_POST['username'],
            "password" => $_POST['password'],
            "email" => $_POST['email'],
            "phone" => $_POST['phone']);
        if ($userdb->addMember($memberData)) {
            $location = 'Location: roster.php?view=list';
            header($location);
            exit;
        } else { echo "Member failed to add. Try again."; }
        break;
    case "set_dues_paid":
        if ($userdb->setDuesPaidMembership($_POST['non_dues_paid'], 1)) {
            $location = 'Location: roster.php?view=dues&action=set';
            header($location);
            exit;
        } else { echo "Failed to set members as dues paid."; }
        break;
    case "unset_dues_paid":
        if ($userdb->setDuesPaidMembership($_POST['dues_paid'], 0)) {
            $location = 'Location: roster.php?view=dues&action=unset';
            header($location);
            exit;
        } else { echo "Failed to unset members as dues paid."; }
        break;
    case "activate_members":
        if ($userdb->setActiveMembership($_POST['non_active_users'], 1)) {
            $location = 'Location: roster.php?view=status&action=activate';
            header($location);
            exit;
        } else { echo "Failed to activate members."; }
        break;
    case "deactivate_members":
        if ($userdb->setActiveMembership($_POST['active_users'], 0)) {
            $location = 'Location: roster.php?view=status&action=deactivate';
            header($location);
            exit;
        } else { echo "Failed to deactivate members."; }
        break;
    case "add_committee":
        if ($committeedb->addCommittee($_POST['name'])) {
            $location = 'Location: committees.php?view=list';
            header($location);
            exit;
        } else { echo "Failed to add a committee."; }
        break;
    case "delete_committee":
        break;

    case "add_committee_member":
        if ($committeedb->addCommitteeMember($_POST['committee_id'], $_POST['user_id'])) {
            $location = 'Location: committees.php?view=committee&id=' . $_POST['committee_id'];
            header($location);
            exit;
        } else { echo "Failed to add a committee member."; }
        break;
    case "delete_committee_member":
        if ($committeedb->deleteCommitteeMember($_POST['committee_id'], $_POST['user_id'])) {
            $location = 'Location: committees.php?view=committee&id=' . $_POST['committee_id'];
            header($location);
            exit;
        } else { echo "Failed to delete a committee member."; }
        break;
    case "set_access":
        break;
    default:
        echo "No Form Submit Type Passed.";
}
?>

