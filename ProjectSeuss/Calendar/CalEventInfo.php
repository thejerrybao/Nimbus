<?php

         ini_set('display_errors', 1);
          define('__ROOT__', dirname(dirname(dirname(__FILE__))));
          require_once(__ROOT__.'/admin/dbfunc.php'); 
          $eventdb = new EventFunctions;
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
            Description
          </label>
            <p> <?php echo $event['description'] ?> <p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>