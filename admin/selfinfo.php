
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
    require_once("admin/dbfunc.php");
    $eventdb = new EventFunctions;
    $userdb = new UserFunctions;

    ?>
  </head>
  <body>
                                    <? function dateOrder($a, $b){
                                          return (int) $b['start_datetime'] -  (int) $a['start_datetime'] ;
                                        }
                                        $event_ids = $userdb->getUserEvents($_SESSION['nimbus_user_id']);
                                        $events = array();
                                        foreach ($event_ids as $event_id) { 
                                            $event = $eventdb->getEventInfo($event_id); 
                                            if($event['status'] >0){
                                                $events[] = $event;
                                            }
                                        }
                                        usort($events, 'dateOrder')
                                    ?>
                                

                                <table class="table" >
                                    <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Service Hours</th>
                                        <th>Admin Hours</th>
                                        <th>Social Hours</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? foreach ($events as $event) { ?>
                                        
                                        <tr>
                                            <td>
                                            <? $hours = $userdb->getUserHoursByEvent($_GET['id'],$event['event_id']) 
                                                
                                            ?>
                                            <a onClick="javascript:modalOpen(<?= $event['event_id'] ?>);"> <? echo "[",date("M d", $event['start_datetime']), "] ", $event['name']; ?> </a>
                                            <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
                                            </td>
                                            <td><? echo  $hours['service_hours']?></td>
                                            <td><? echo  $hours['admin_hours']?></td>
                                            <td><? echo  $hours['social_hours']?></td>

                                            
                                            </tr>
                                    <? } ?>
                                    </tbody>
                                </table>
        

  </body>
    </html>