<?php

         ini_set('display_errors', 1);
          require_once('dbfunc.php'); 
          $eventdb = new EventFunctions;
          $userdb = new UserFunctions;
          $tagdb = new TagFunctions;
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
                                <? if ($event['online_signups']) { ?>
                                <label>Online Sign-ups End Date and Time</label>
                                <p><?= date("F j, Y, g:i a", $event['online_end_datetime']); ?></p>
                                <? } ?>
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
      <div class="modal-footer">
      
      </div>
    </div>
  </div>