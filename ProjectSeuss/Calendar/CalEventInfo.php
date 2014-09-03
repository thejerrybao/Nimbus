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
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo $event['name'] ?>
        </h4>
      </div>
      <div class="modal-body">
          <label>
            Chair
          </label>
            <p><?php $chair = $userdb->getUserInfo($event['chair_id']); echo $chair['first_name']; echo " "; echo $chair['last_name']; ?>  </p>
          <label>
            Start Time
          </label>
            <p> <?php echo date("F d, Y, h:i A",$event['start_datetime']); ?> <p>
          <label>
            End Time
          </label>
            <p> <?php echo date("F d, Y, h:i A",$event['end_datetime']); ?> <p>
          <label>
            Description
          </label>
            <p> <?php echo $event['description'] ?> <p>
          <label>
            Location
          </label>
            <p> <?php echo $event['location'] ?> <p>
          <label>
            Meeting Location
          </label>
            <p> <?php echo $event['meeting_location'] ?> <p>
          <label>
            Attendees
          </label>
            <p>
             <?if (isset($_SESSION['nimbus_user_id'])) { 
              $loggedin = true;
              } else {
                $loggedin = false;
              } 
              $signedup = false;
              if ($eventAttendees = $eventdb->getEventAttendees($event['event_id'])) { ?>
                  <ul>
                  <? foreach ($eventAttendees as $eventAttendee) { ?>
                    <li>
                    <?= $eventAttendee['first_name']?> <?= $eventAttendee['last_name']; ?>
                  </li>
                    <? if($loggedin){
                    if($eventAttendee['user_id'] == $_SESSION["nimbus_user_id"]){
                      $signedup = true;
                      }
                    }
                  }
                }?>
              </ul>
            </p>
      </div>
      <div class="modal-footer">
      <? if($loggedin){
         if(! $signedup){ 
          if($event['online_end_datetime'] < date('U')){ ?>
            <p>Sign-ups are closed, if you would still like to attend please contact <?php $chair = $userdb->getUserInfo($event['chair_id']); echo $chair['first_name']; echo " "; echo $chair['last_name']; ?></p>
          <? } else{?>
        <form action="eventsignup.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
              <button type="submit" class="btn btn-primary">Sign Up</button>
          </form>
         <? }} else { ?>
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