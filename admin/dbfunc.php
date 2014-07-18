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

        $query = $this->$db->prepare('SELECT * FROM `users`
            WHERE dues_paid=:dues_paid ORDER BY `last_name` ASC');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':dues_paid' => 1));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $duesPaid[] = array(
                "user_id" => $row->user_id,
                "first_name" => $row->first_name,
                "last_name" => $row->last_name,
                "email" => $row->email,
                "phone" => $row->phone);
        }

        return $duesPaid;
    }

    // get user data with user ID
    public function getUserInfo($user_id) {

        $userInfo = array();

        $query = $this->$db->prepare('SELECT * FROM `users`
            WHERE user_id=:user_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':user_id' => $user_id));     
        if ($query->rowCount() == 0) { return false; }
        $row = $query->fetch();
        $userInfo['user_id'] = $row->user_id;
        $userInfo['first_name'] = $row->first_name;
        $userInfo['last_name'] = $row->last_name;
        $userInfo['username'] = $row->username;
        $userInfo['password'] = $row->password;
        $userInfo['email'] = $row->email;
        $userInfo['phone'] = $row->phone;
        $userInfo['dues_paid'] = $row->dues_paid;
        $userInfo['access'] = $row->access;
        $userInfo['active'] = $row->active;
        return $userInfo;
    }

    // get event data with event ID
    private function getEventInfo($event_id) {

        $eventInfo = array();

        $query = $this->$db->prepare('SELECT * FROM `events`
            WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id));

        if ($query->rowCount() == 0) { return false; }
        $row = $query->fetch();
        $eventInfo['event_id'] = $row->event_id;
        $eventInfo['name'] = $row->name;
        $eventInfo['chair_id'] = $row->chair_id;
        $eventInfo['start_datetime'] = strtotime($row->start_datetime);
        $eventInfo['end_datetime'] = strtotime($row->end_datetime);
        $eventInfo['description'] = $row->description;
        $eventInfo['location'] = $row->location;
        $eventInfo['meeting_location'] = $row->meeting_location;
        $eventInfo['all_day'] = $row->all_day;
        $eventInfo['online_signups'] = $row->online_signups;
        $eventInfo['online_end_datetime'] = strtotime($row->online_end_datetime);
        $eventInfo['status'] = $row->status;
        $eventInfo['num_attendees'] = $row->num_attendees;
        $eventInfo['num_outside_attendees'] = $row->num_outside_attendees;
        $eventInfo['pros'] = $row->pros;
        $eventInfo['cons'] = $row->cons;
        $eventInfo['do_again'] = $row->do_again;
        $eventInfo['funds_raised'] = $row->funds_raised;
        $eventInfo['service_hours'] = $row->service_hours;
        $eventInfo['admin_hours'] = $row->admin_hours;
        $eventInfo['social_hours'] = $row->social_hours;
        $eventInfo['num_override_hours'] = $row->num_override_hours;
        return $eventInfo;
    }

    // get ID of all events a user has attended
    private function getUserEventsID($user_id) {

        $userEventsID = array();

        $query = $this->$db->prepare('SELECT `event_id` FROM `event_attendees`
            WHERE user_id=:user_id');
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
            $hours['service_hours'] = $event['service_hours'];
            $hours['admin_hours'] = $event['admin_hours'];
            $hours['social_hours'] = $event['social_hours'];
        } else { 

            $row = $query->fetch();
            $hours['service_hours'] = $row->service_hours; 
            $hours['admin_hours'] = $row->admin_hours;
            $hours['social_hours'] = $row->social_hours;  
        }

        return $hours
    }

    // get total hours of the club or a user
    // can specify what type of hours or all hours (service, admin, social, all)
    public function getTotal($typeHours, $user_id = null) {

        $totalHours = array();
        $totalHours['service_hours'] = $totalHours['admin_hours'] = $totalHours['social_hours'] = 0.0

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
                    $totalHours['service_hours'] += $rowOverrideHours->service_hours;
                    $totalHours['admin_hours'] += $rowOverrideHours->admin_hours;
                    $totalHours['social_hours'] += $rowOverrideHours->social_hours;
                }

                $totalHours['service_hours'] += $row->service_hours * $numNonOverride
                $totalHours['social_hours'] += $row->admin_hours * $numNonOverride
                $totalHours['admin_hours'] += $row->social_hours * $numNonOverride
            }
        } else {

            $userEventsID = $self->getUserEventsID($user_id);
            foreach ($userEventsID as $event_id) {
                
                $hours = $this->getUserHoursByEventID($user_id, $event_id)
                $totalHours['service_hours'] += $hours['service_hours'];
                $totalHours['admin_hours'] += $hours['admin_hours'];
                $totalHours['social_hours'] += $hours['social_hours'];
            }
        }

        switch ($typeHours) {
            case "service":
                return $totalHours['service_hours'];
            case "admin":
                return $totalHours['admin_hours'];
            case "social":
                return $totalHours['social_hours'];
            default:
                return "No type of hours specified.";
        }
    }

    // creates an event
    public function createEvent($eventData) {

        $query = $this->$db->prepare('INSERT INTO `events` 
            VALUES ("", :name, :chair_id, :start_datetime, :end_datetime, :description, :location, :meeting_location,
                :all_day, :online_signups, :online_end_datetime, :status, 0, 0, "", "", "", 0, 0, 0, 0, 0)');
        if ($query->execute(array(
            ':name' => $eventData['name'],
            ':chair_id' => $eventData['chair_id'],
            ':start_datetime' => $eventData['start_datetime'],
            ':end_datetime' => $eventData['end_datetime'],
            ':description' => $eventData['description'],
            ':location' => $eventData['location'],
            ':meeting_location' => $eventData['meeting_location'],
            ':all_day' => $eventData['all_day'],
            ':online_signups' => $eventData['online_signups'],
            ':online_end_datetime' => $eventData['online_end_datetime'],
            ':status' => $eventData['status']
            ))) { return "Event " . $eventData['name'] . " was successfully created!"; }
        else { return "An Error has Occurred! Error: " . $db->errorInfo(); }
    }

    // get today's events
    // Assumes the date given is in UnixDateTime and is at  the Date  at 00:00:00
    // Finds events that start at the date at 00:00:00 to the next day at 00:00:00 
    public function getEventsByDate($date) {

        $events = array();
        $dateBegin = $date;
        $dateEnd = strtotime('+1 day', $date);

        $query = $this->$db->prepare('SELECT * FROM `events`
            WHERE start_datetime >= FROM_UNIXTIME($dateBegin) AND FROM_UNIXTIME($dateEnd) <= $dateEnd' );
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id,
            ':name' => $name,
            ':start_datetime' => $start_datetime,
            ':end_datetime' => $end_datetime,
            ':meeting_location' => $meeting_location,
            ':location' => $location,
            ':status' => $status
            ));
        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $events[] = array(
                'event_id' => $row->event_id,
                'name' => $row->name,
                'start_datetime' => $row->$start_datetime,
                'end_datetime' => $row->$end_datetime,
                'meeting_location' => $row->$meeting_location,
                'location' => $row->$location,
                'status' => $row->$status);
        }

        return $events;
    }

    // get month's events
    // Assumes the date given is in UnixDateTime and is at the first day of the month at 00:00:00
    // Finds events that start first day of the month at 00:00:00 to the first day of the next month at 00:00:00
    public function getEventsByMonth($month) {

        $events = array();
        $dateBegin = $date;
        $dateEnd = strtotime('+1 month', $date);

        $query = $this->$db->prepare('SELECT * FROM `events`
            WHERE start_datetime >= FROM_UNIXTIME($dateBegin) AND FROM_UNIXTIME($dateEnd) <= $dateEnd' );
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id,
            ':name' => $name,
            ':start_datetime' => $start_datetime,
            ':end_datetime' => $end_datetime,
            ':meeting_location' => $meeting_location,
            ':location' => $location,
            ':status' => $status
            ));
        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $events[] = array(
                'event_id' => $row->event_id,
                'name' => $row->name,
                'start_datetime' => $row->$start_datetime,
                'end_datetime' => $row->$end_datetime,
                'meeting_location' => $row->$meeting_location,
                'location' => $row->$location,
                'status' => $row->$status);
        }

        return $events;
    }

    // verify event
    public function verifyEvent($event_id) {

        $eventInfo = $this->getEventInfo($event_id);
        if ($eventInfo['access'] != 2){
            return "This event is not completed.";
        } else {
            $query = $this->$db->prepare('UPDATE events SET status = 3
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))){
                return "event was successfully verified";
            } else { return "An error has Occurred! Error: " . $db->errorInfo(); }
        }
    }

    // complete event
    public function completeEvent($event_id) {

        $eventInfo = $this->getEventInfo($event_id);
        if ($eventInfo['access'] != 1){
            return "This event is not in post-event status.";
        } else {
            $query = $this->$db->prepare('UPDATE events SET status = 2
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))){
                return "event was successfully completed";
            } else { return "An error has Occurred! Error: " . $db->errorInfo(); }
        }
    }

    // get event attendees
    public function getEventAttendees($event_id) {

        $eventAttendees = array();

        $query = $this->$db->prepare('SELECT * FROM `event_attendees` WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id
            ));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $userInfo = $this->getUserInfo($row->user_id);
            $eventAttendees[] = array(
                'first_name' => $userInfo['first_name'],
                'last_name' => $userInfo['last_name'],
                'email' => $userInfo['email'],
                'phone' => $userInfo['phone']);
        }

        return $eventAttendees;
    }

    // add override hours for users
    public function addOverrideHours($event_id, $overrideUsers) {

        foreach ($overrideUsers as $user) {
            $query = $this->$db->prepare('INSERT INTO `event_override_hours` 
                VALUES ("", :event_id, :user_id, :service_hours, :admin_hours, :social_hours)');

            if ($query->execute(array(
                ':event_id' => $event_id,
                ':user_id' => $user['user_id'],
                ':service_hours' => $user['service_hours'],
                ':admin_hours' => $user['admin_hours'],
                ':social_hours' => $user['social_hours']
                ))) { 
                continue; 
            } else { return "An error has Occurred! Error: " . $db->errorInfo(); }
        }
    }

    // register user
    public function registerUser($userData) {

        $query = $this->$db->prepare('INSERT INTO `users` 
            VALUES ("", :user_id, :first_name, :last_name, :username, :password, :email, :phone, 0, 0, 1)');

        if ($query->execute(array(
            ':user_id' => $userData['user_id'],
            ':first_name' => $userData['first_name'],
            ':last_name' => $userData['last_name'],
            ':username' => $userData['username'],
            ':password' => $userData['password'],
            ':email' => $userData['email'],
            ':phone' => $userData['phone']
            ))) { return "Welcome to UCBCKI " . $userData['first_name'] ."! Your user profile was successfully created!"; }
            else { return "An error has occurred! Error: " . $db->errorInfo(); } 
    }

    // change user access
    public function changeUserAccess($user_id, $access) {

        if ($access == 0) {
            $accessValue = 'General Member',
            else if ($access == 1) {
                $accessValue = 'Board Member',
            } else if ($access == 2) {
                $accessValue = 'Secretary',
            } else { 
                $accessValue = 'Technology Chair'
            }
        }

        $userInfo = $this->getUserInfo($user_id);

        $query = $this->$db->prepare('UPDATE users SET access=:access
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':access' => $access))) { return "Successfully changed access for " . $userInfo[first_name] . $userInfo[last_name] . "to " . $accessValue; }
        else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }
}

?>