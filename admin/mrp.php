<?php
/** Project Name: Nimbus (Circle K Club Management)
 ** Roster Administration (roster.php)
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
if (!isset($_SESSION['nimbus_user_id'])) { header('Location: login.php'); }
require_once("dbfunc.php");
$userdb = new UserFunctions;
$eventdb = new EventFunctions;
$committeedb = new CommitteeFunctions;

$page = "roster";
$pageTitle = "Roster Administration";
$customCSS = true;
$customJS = true;
?>

<!DOCTYPE html>
<html lang="en">

<? require_once("header.php"); ?>

<body>
<?php
    $socials = 0;
    $mde = 0;
    $fund = 0;
    $ck = 0;
    $kfam = 0;
    $in = 0;
    $divison = 0;
    $dist = 0;
    $inter = 0;
    $chair = 0;
    $commiteemem = 0;
    $user = $userdb->getUserInfo($_SESSION['nimbus_user_id'], true);
    $event_ids = $userdb->getUserEvents($_SESSION['nimbus_user_id']);
    $events = array();
    foreach ($event_ids as $event_id) { 
        $event = $eventdb->getEventInfo($event_id); 
        if($event['status'] >1){
            $events[] = $event;
        }
    }
    foreach ($events as $event) {
        $tags = $event['tag_ids'];
        if(in_array(19, $tags)){
            $socials += 1;
        } 
        if(in_array(18, $tags)){
            $mde += 1;
        } 
        if(in_array(12, $tags)){

            $fund += 1;
        } 
        if(in_array(5, $tags)){
            $ck += 1;
        } 
        if(in_array(17, $tags)){
            $kfam += 1;
        } 
        if(in_array(14, $tags)){
            $in += 1;
        } 
        if(in_array(11, $tags)){
            $division += 1;
        }
        if(in_array(9, $tags)){
            $dist += 1;
        }
        if(in_array(15, $tags)){
            $inter += 1;
        } 
        if($event['chair_id'] == $_SESSION['nimbus_user_id']){
            $chair += 1;
        }

    }
    $committees = $committeedb->getCommittees();
    foreach ($committees as $com ) {
        foreach ($com['members'] as $mem) {
            if ($_SESSION['nimbus_user_id'] == $mem['user_id']){
                $commiteemem += 1;
            }
        }
    }



?>


<div class="row">
                <div class="panel panel-primary">    
                                <div class="panel-heading">MRP Information</div>
                                <div class="panel-body">
                                           <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Requirement</th>
                                    <th>Status</th>
                                    <th>Bronze</th>
                                    <th>Silver</th>
                                    <th>Gold</th>
                                    <th>Platinum</th>
                                </tr>
                            </thead>
                            <tbody>


                                <tr>
                                    <td style="font-size:25px">Service Hours</td>
                                    <td style="font-size:25px"><?= $user['hours']['service_hours']?></td>
                                    <td style="font-size:25px">50</td>
                                    <td style="font-size:25px">100</td>
                                    <td style="font-size:25px">150</td>
                                    <td style="font-size:25px">200</td>
                                </tr>
                                <tr>
                                    <td>DUES PAID</td>
                                    <td><?if($user['dues_paid']){?>
                                        <span class="glyphicon glyphicon-ok"></span>
                                    <?}?></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                </tr>
                                <tr>
                                    <td>ADDITIONAL REQUIREMENTS</td>
                                    <td></td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>12</td>

                                </tr>
                                <tr>
                                    <td># SOCIALS (SE)</td>
                                    <td><?= $socials?></td>
                                    <td>4</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                </tr>
                                <tr>
                                    <td># MD&E EVENTS (MD)</td>
                                    <td><?= $mde ?></td>
                                    <td>2</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td># FUNDRAISERS (FR)</td>
                                    <td><?= $fund ?></td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td># CIRCLE K HOSTED EVENTS (CK)</td>
                                    <td><?= $ck ?></td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td># KIWANIS FAMILY</td>
                                    <td><?= $kfam ?></td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td># INTERCLUB (IN)</td>
                                    <td><?= $in ?></td>
                                    <td>4</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                </tr>
                                <tr>
                                    <td># DIVISIONAL EVENTS (DV)</td>
                                    <td><?= $division ?></td>
                                    <td>4</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                </tr>
                                <tr>
                                    <td># DISTRICT EVENTS</td>
                                    <td><?= $dist ?></td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td># INTERNATIONAL EVENTS (INT)</td>
                                    <td><?= $inter ?></td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>ARTICLES SUBMITTED</td>
                                    <td></td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>WEBINARS ATTENDED</td>
                                    <td></td>
                                    <td>2</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>CHAIRED EVENTS</td>
                                    <td><?= $chair ?></td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>HOST DISTRICT WORKSHOP OR WEBINAR</td>
                                    <td></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                </tr>
                                <tr>
                                    <td>CLUB COMMITTEE MEMBER</td>
                                    <td><?if($commiteemem){?>
                                        <span class="glyphicon glyphicon-ok"></span>
                                    <?}?></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                    <td><span class="glyphicon glyphicon-ok"></span></td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
                                </div>
                        </div>
                    </div>
                </div>

            </body>