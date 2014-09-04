<?php
        ini_set('display_errors', 1);
        include_once("../admin/dbfunc.php");
        $db = new EventFunctions;
        $m = (integer) date('n');
        $start = date("U",strtotime($_GET['start']));
        $end = date("U",strtotime($_GET['end']));
        $events = $db->getEventsInterval($start,$end);
        $out = array();
        foreach ($events as $event) {
        $out[] = array(
            'id' => $event["event_id"],
            'title' => $event["name"],
            'start' => date("c",$event["start_datetime"]),
            'end' => date("c",$event["end_datetime"]) 
        );
    }
    echo json_encode($out);
    exit;
    ?>