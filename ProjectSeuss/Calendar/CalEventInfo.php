<?php

         ini_set('display_errors', 1);
          define('__ROOT__', dirname(dirname(dirname(__FILE__))));
          require_once(__ROOT__.'/admin/dbfunc.php'); 
          $eventdb = new EventFunctions;
          $userdb = new UserFunctions;
          $id = $_GET["id"];
          $event = $eventdb->getEventInfo($id);
          echo $event['name']
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
      </div>
      <div class="modal-footer">
        <form action="/admin/processdata.php" method="post" enctype="multipart/form-data">
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
  </div>