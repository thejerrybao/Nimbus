<?php
        ini_set('display_errors', 1);
        $path = $_SERVER['DOCUMENT_ROOT'];
        $path .= "/admin/dbfunc.php";
        include_once($path);
        $db = new EventFunctions;
        $m = (integer) date('n');
        $events = $db->getEventsByMonth(date(mktime(1,1,1,$m,1,date('Y'))));
        $out = array();
        foreach($events as $event) {
        $out[] = array(
            'id' => $event["event_id"],
            'title' => $event["name"],
            'url' => "http://www.example.com/",
            'class' => "event-warning",
            'start' => $event["start_datetime"] * 1000,
            'end' => $event["end_datetime"] * 1000
        );
    }
    echo json_encode(array('success' => 1, 'result' => $out));
    exit;
    ?>