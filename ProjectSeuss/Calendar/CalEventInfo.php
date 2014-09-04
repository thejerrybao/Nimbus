<?php

         ini_set('display_errors', 1);
          define('__ROOT__', dirname(dirname(dirname(__FILE__))));
          require_once(__ROOT__.'/admin/dbfunc.php'); 
          $eventdb = new EventFunctions;
          $userdb = new UserFunctions;
          $id = $_GET["id"];
          $event = $eventdb->getEventInfo($id);
          session_start();
?>
<div class="modal-dialog" style="width: 35%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo $event['name'] ?>
        </h4>
      </div>
      <div class="modal-body">
          <p style="margin: 0;">
            <label>Chair</label>
            <span style="float: right;"><?php $chair = $userdb->getUserInfo($event['chair_id']); echo $chair['first_name']; echo " "; echo $chair['last_name']; ?></span>
          </p>
          <p style="margin: 0;">
            <label>Start Date & Time</label><span style="float: right;"><?php echo date("F d, Y, h:i A",$event['start_datetime']); ?></span>
          </p>
          <p style="margin: 0;">
            <label>End Date & Time</label><span style="float: right;"><?php echo date("F d, Y, h:i A",$event['end_datetime']); ?></span>
          </p>
          <p style="margin: 0;">
            <label>Location</label><span style="float: right;"><?php echo $event['location']; ?></span>
          </p>
          <p style="margin: 0;">
            <label>Meeting Location</label><span style="float: right;"><?php echo $event['meeting_location']; ?></span>
          </p>
          <p style="margin: 0;">
            <label>Description</label><span style="display: block;"><?php echo $event['description']; ?></span>
          </p>
          <div style="margin: 0;">
            <label style="margin-top: 5px;">Attendees</label>
            <ul>
            <? if (isset($_SESSION['nimbus_user_id'])) { $loggedin = true; }
              else { $loggedin = false; } 
              $signedup = false;
              if ($eventAttendees = $eventdb->getEventAttendees($event['event_id'])) {
                foreach ($eventAttendees as $eventAttendee) { ?>
                  <li><?= $eventAttendee['first_name']?> <?= $eventAttendee['last_name']; ?></li>
                  <? if ($loggedin && ($eventAttendee['user_id'] == $_SESSION["nimbus_user_id"])) { $signedup = true; }
                }
              } else { ?>
                <li>No Attendees</li>
              <? } ?>
            </ul>
          </div>
      </div>
      <div class="modal-footer">
      <? if($loggedin){
         if(! $signedup){ 
          date_default_timezone_set('America/Los_Angeles');
          if($event['online_end_datetime'] < time()){ ?>
            <label style="margin: 0;">Sign-ups are closed!</label>
            <label style="margin: 0;">Contact <?php $chair = $userdb->getUserInfo($event['chair_id']); echo $chair['first_name']; echo " "; echo $chair['last_name']; ?> if you still want to attend.</label>
          <? } else{?>
        <form action="eventsignup.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
              <button type="submit" class="btn btn-primary">Sign Up</button>
          </form>
         <? } } else { ?>
          <form action="notattending.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
              <button type="submit" class="btn btn-primary">Remove</button>
          </form>
          <? }
          } else { ?> 
        <label>Please Login to signup for this event.</label>
        <? } ?>
      </div>
    </div>
  </div>