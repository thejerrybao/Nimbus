
<!DOCTYPE html>
<html>
  <head> 

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    
   <?php
    session_start();
    ini_set('display_errors', 1);
    include("head.php"); 
    require_once("dbfunc.php");
    $eventdb = new EventFunctions;
    $userdb = new UserFunctions;
    $user = $userdb->getUserInfo($_SESSION['nimbus_user_id'], true);

    ?>
  </head>
  <body >
                                    <? function dateOrder($a, $b){
                                          return (int) $b['start_datetime'] -  (int) $a['start_datetime'] ;
                                        }
                                        $event_ids = $userdb->getUserEvents($_SESSION['nimbus_user_id']);
                                        $homeclub = array();
                                        $dist = array();
                                        $div = array();
                                        $otherck = array();
                                        $kfam    = array();

                                        foreach ($event_ids as $event_id) { 
                                            $event = $eventdb->getEventInfo($event_id); 
                                            if($event['status'] > 1){
                                                $tags = $event['tag_ids'];
                                                if(!(in_array(11, $tags) or in_array(15, $tags) or in_array(9, $tags))){
                                                    $homeclub[] = $event;   
                                                }
                                                if(in_array(11, $tags)){
                                                    $div[] = $event
                                                }
                                                if(in_array(9, $tags)){
                                                    $dist[] = $event;
                                                }
                                                if(in_array(5, $tags)){
                                                    $otherck[] = $event;
                                                }
                                                if(in_array(17, $tags)){
                                                    $kfam[] = $event;
                                                }
                                            }
                                        }
                                        usort($events, 'dateOrder')
                                    ?>
                                
                                <div  class="col-sm-8 col-sm-offset-2"style="background-color:rgba(255,255,255,0.98); border-radius: 25px;">
                                <table class="table" >
                                    <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Service Hours</th>
                                        <th>Admin Hours</th>
                                        <th>Social Hours</th>
                                    </tr>
                                    </thead>
                                    <h1>All events</h1>
                                    <tbody>
                                    <? foreach ($homeclub as $event) { ?>
                                        
                                        <tr>
                                            <td>
                                            <? 

                                            $hours = $userdb->getUserHoursByEvent($_SESSION['nimbus_user_id'],$event['event_id']); 
                                                
                                            ?>
                                            <a onClick="javascript:usermodalOpen(<?= $event['event_id'] ?>);"> <? echo "[",date("M d", $event['start_datetime']), "] ", $event['name']; ?> </a>
                                            <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
                                            </td>
                                            <td><? echo  $hours['service_hours']?></td>
                                            <td><? echo  $hours['admin_hours']?></td>
                                            <td><? echo  $hours['social_hours']?></td>

                                            
                                            </tr>
                                    <? } ?>

                                    </tbody>
                                </table>
                                <label>Service Hours:</label>
                                    <p style="display: inline; margin-right: 50px;"><?= $user['hours']['service_hours'] ?></p>
                                    <label>Admin Hours:</label>
                                    <p style="display: inline; margin-right: 50px;"><?= $user['hours']['admin_hours'] ?></p>
                                    <label>Social Hours:</label>
                                    <p style="display: inline; margin-right: 50px;"><?= $user['hours']['social_hours'] ?></p>
            
                                </div>
        <div class="modal fade" id="usereventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<script type="text/javascript">
      function usermodalOpen(eventid) {
        $("#usereventModal").load("/~circlek/selfmodal.php?id="+eventid);
        $('#usereventModal').modal('show');
      }
    </script>
  </body>
    </html>