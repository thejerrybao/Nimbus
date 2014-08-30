<?php
        ini_set('display_errors', 1);
        $path = $_SERVER['DOCUMENT_ROOT'];
        $path .= "/admin/dbfunc.php";
        include_once($path);
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
            'end' => date("c",$event["end_datetime"]),
            'description' => $event["name"], 
        );
    }
    echo json_encode($out);
    exit;
    ?>