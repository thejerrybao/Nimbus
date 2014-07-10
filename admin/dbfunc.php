<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Database Functions (dbfunc.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/

// SQL Database Info
define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "917615_ckirfdb");
define("MYSQL_PASS", "ckirfdb123");
define("MYSQL_DB", "ucbckirfsystem_zymichost_ckirfsystem");

class DatabaseFunctions {

    private static $db;

    // construct to connect to database
    public function __construct() {
        try {
            $this->$db = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB, MYSQL_USER, MYSQL_PASS);
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // destruct to destroy conection to database at end
    public function __destruct() { $this->$db = null; }

    // get all dues paid member information
    public function getDuesPaidMembers() {

        $duesPaid = array();

        $query = $this->$db->prepare('SELECT * FROM `users` WHERE dues_paid=:dues_paid ORDER BY `last_name` ASC');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':dues_paid' => 1
            ));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $duesPaid[] = array(
                "user_id" => $row->user_id,
                "first_name" => $row->first_name,
                "last_name" => $row->last_name,
                "email" => $row->email,
                "phone" => $row->phone
            );
        }

        return $duesPaid;
    }

    // get event data with event ID
    private function getEventInfo($event_id) {
        $query = $this->$db->prepare('SELECT * FROM `events`
            WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id
            ));

        return $query->fetch();
    }

    // get ID of all events a user has attended
    private function getUserEventsID($user_id) {

        $userEventsID = array();

        $query = $this->$db->prepare('SELECT `event_id` FROM `event_attendees` WHERE user_id=:user_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':user_id' => $user_id
            ));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) { $userEventsID[] = $row->event_id; }
        return $userEventsID
    }

    // get user hours of an event; returns all hour types
    public function getUserHoursByEventID($user_id, $event_id) {

        $hours = array();

        $query = $this->$db->prepare('SELECT * FROM `event_override_hours` 
            WHERE event_id=:event_id AND user_id=:user_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id,
            ':user_id' => $user_id
            ));

        if ($query->rowCount() == 0) {

            $event = $this->getEventInfo($event_id);
            $hours["service_hours"] = $event->service_hours;
            $hours["admin_hours"] = $event->admin_hours;
            $hours["social_hours"] = $event->social_hours;
        }
        else { 

            $row = $query->fetch();
            $hours["service_hours"] = $row->service_hours; 
            $hours["admin_hours"] = $row->admin_hours;
            $hours["social_hours"] = $row->social_hours;  
        }

        return $hours
    }

    // get total hours of the club or a user
    // can specify what type of hours or all hours (service, admin, social, all)
    public function getTotal($typeHours, $user_id = null) {

        $totalHours = array();
        $totalHours["service_hours"] = $totalHours["admin_hours"] = $totalHours["social_hours"] = 0.0

        if (!$user_id) {

            $query = $this->$db->prepare('SELECT * FROM `events`');
            $query->setFetchMode(PDO::FETCH_OBJ);
            $query->execute();

            if ($query->rowCount() == 0) { return false; }
            while ($row = $query->fetch()) {

                $numNonOverride = $row->num_attendees - $row->num_override_hours;

                if ($row->num_override_hours > 0) {

                    $queryOverrideHours = $this->$db->prepare('SELECT SUM(service_hours) AS `service_hours`,
                        SUM(admin_hours) AS `admin_hours`,
                        SUM(social_hours) AS `social_hours` 
                        FROM `event_override_hours` WHERE event_id=:event_id');
                    $queryOverrideHours->setFetchMode(PDO::FETCH_OBJ);
                    $queryOverrideHours->execute(array(
                        ':event_id' => $row->event_id
                    ));

                    $rowOverrideHours = $queryOverrideHours->fetch();
                    $totalHours["service_hours"] += $rowOverrideHours->service_hours;
                    $totalHours["admin_hours"] += $rowOverrideHours->admin_hours;
                    $totalHours["social_hours"] += $rowOverrideHours->social_hours;
                }

                $totalHours["service_hours"] += $row->service_hours * $numNonOverride
                $totalHours["social_hours"] += $row->admin_hours * $numNonOverride
                $totalHours["admin_hours"] += $row->social_hours * $numNonOverride
            }
        }

        else {

            $userEventsID = $self->getUserEventsID($user_id);
            foreach ($userEventsID as $event_id) {
                
                $hours = $this->getUserHoursByEventID($user_id, $event_id)
                $totalHours["service_hours"] += $hours["service_hours"];
                $totalHours["admin_hours"] += $hours["admin_hours"];
                $totalHours["social_hours"] += $hours["social_hours"];
            }
        }

        switch ($typeHours) {
            case "service":
                return $totalHours["service_hours"];
            case "admin":
                return $totalHours["admin_hours"];
            case "social":
                return $totalHours["social_hours"];
            default:
                return "No type of hours specified.";
        }
    }
}

?>