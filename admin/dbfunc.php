<?php 
/** Project Name: Nimbus (Circle K Club Management)
 ** Database Functions (dbfunc.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/
ini_set('display_errors', 1);

// SQL Database Info
define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "root");
define("MYSQL_DB", "dev_ckirfsystem");

function array_orderby() {
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

class Database {

    protected $db;

    // construct to connect to database
    public function __construct() {

        try { $this->db = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB, MYSQL_USER, MYSQL_PASS); }
        catch (PDOException $e) { die($e->getMessage()); }

        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // destruct to destroy conection to database at end
    public function __destruct() { $this->db = null; }
}

class UserFunctions extends Database {

    public function login($username, $password) {

        $userData = array();

        $query = $this->db->prepare('SELECT * FROM `users`
            WHERE username=:username LIMIT 1');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':username' => $username));

        if ($query->rowCount() == 0) { return false; }
        $row = $query->fetch();
        if (password_verify($password, $row->password)) {

            $userData['user_id'] = $row->user_id;
            $userData['access'] = $row->access;
            $userData['first_name'] = $row->first_name;
            $userData['last_name'] = $row->last_name;
            return $userData;
        } else { return false; }
    }

    public function logout() {

        session_start();
        unset($_SESSION['nimbus_user_id']);
        unset($_SESSION['nimbus_access']);
        unset($_SESSION['nimbus_first_name']);
        unset($_SESSION['nimbus_last_name']);
    }

    // register user
    public function addUser($userData) {

        $query = $this->db->prepare('SELECT * FROM `users`
            WHERE email=:email');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':email' => $userData['email']));

        if ($query->rowCount() == 0) {
            $query = $this->db->prepare('INSERT INTO `users`
                VALUES ("", :first_name, :last_name, :username, :password, :email, 0, :phone, 0, 0, 1)');

            if ($query->execute(array(
                ':first_name' => $userData['first_name'],
                ':last_name' => $userData['last_name'],
                ':username' => $userData['username'],
                ':password' => $userData['password'],
                ':email' => $userData['email'],
                ':phone' => $userData['phone']
                ))) { return true; }
                else { return false; } 
        } else {
            $row = $query->fetch();
            if (password_verify("", $row->password)) {
                $query = $this->db->prepare('UPDATE `users`
                    SET first_name=:first_name, last_name=:last_name, username=:username, password=:password,
                        phone=:phone
                    WHERE email=:email');

                if ($query->execute(array(
                    ':first_name' => $userData['first_name'],
                    ':last_name' => $userData['last_name'],
                    ':username' => $userData['username'],
                    ':password' => $userData['password'],
                    ':email' => $userData['email'],
                    ':phone' => $userData['phone']
                    ))) { return true; }
                    else { return false; } 
            } else { return false; }
        }
    }

    public function deleteUser($user_id) {

        $query = $this->db->prepare('SELECT * FROM `event_attendees`
            WHERE user_id=:user_id LIMIT 1');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':user_id' => $user_id));

        if ($query->rowCount() == 0) {
            $query = $this->db->prepare('DELETE FROM `users`
                WHERE :user_id = $user_id');
            if ($query->execute(array(
                ':user_id' => $user_id))) { return true;
            } else { return false; }
        } else { return false; }
    }

    // get total hours of the club or a user
    // can specify what type of hours or all hours (service, admin, social, all)
    public function getTotalHours($typeHours, $user_id = null) {

        $totalHours = array();
        $totalHours['service_hours'] = $totalHours['admin_hours'] = $totalHours['social_hours'] = 0.0;

        if (!$user_id) {

            $query = $this->db->prepare('SELECT * FROM `events`');
            $query->setFetchMode(PDO::FETCH_OBJ);
            $query->execute();

            if ($query->rowCount() == 0) { return false; }
            while ($row = $query->fetch()) {

                $numNonOverride = $row->num_attendees - $row->num_override_hours;
                if ($row->num_override_hours > 0) {
                    $queryOverrideHours = $this->db->prepare('SELECT SUM(service_hours) AS `service_hours`,
                        SUM(admin_hours) AS `admin_hours`,
                        SUM(social_hours) AS `social_hours` 
                        FROM `event_override_hours` WHERE event_id=:event_id');
                    $queryOverrideHours->setFetchMode(PDO::FETCH_OBJ);
                    $queryOverrideHours->execute(array(
                        ':event_id' => $row->event_id));

                    $rowOverrideHours = $queryOverrideHours->fetch();
                    $totalHours['service_hours'] += $rowOverrideHours->service_hours;
                    $totalHours['admin_hours'] += $rowOverrideHours->admin_hours;
                    $totalHours['social_hours'] += $rowOverrideHours->social_hours;
                }

                $totalHours['service_hours'] += $row->service_hours * $numNonOverride;
                $totalHours['admin_hours'] += $row->admin_hours * $numNonOverride;
                $totalHours['social_hours'] += $row->social_hours * $numNonOverride;
            }
        } else {
            $userEventsID = $this->getUserEvents($user_id);
            if ($userEventsID) {
                foreach ($userEventsID as $event_id) {
                    $hours = $this->getUserHoursByEvent($user_id, $event_id);
                    $totalHours['service_hours'] += $hours['service_hours'];
                    $totalHours['admin_hours'] += $hours['admin_hours'];
                    $totalHours['social_hours'] += $hours['social_hours'];
                }
            }
        }

        switch ($typeHours) {
            case "service":
                return $totalHours['service_hours'];
            case "admin":
                return $totalHours['admin_hours'];
            case "social":
                return $totalHours['social_hours'];
            case "all":
                return $totalHours;
            default:
                return false;
        }
    }

    // get all member information based on parameters
    public function getUsers($type = "all", $ordering = "first_name") {

        $users = array();
        switch ($type) {
            case "dues_paid":
                $query = $this->db->prepare('SELECT * FROM `users`
                    WHERE dues_paid=:dues_paid AND active=:active ORDER BY ' . $ordering . ' ASC');
                $query->setFetchMode(PDO::FETCH_OBJ);
                $query->execute(array(
                    ':dues_paid' => 1,
                    ':active' => 1));
                break;
            case "non_dues_paid":
                $query = $this->db->prepare('SELECT * FROM `users`
                    WHERE dues_paid=:dues_paid AND active=:active ORDER BY ' . $ordering . ' ASC');
                $query->setFetchMode(PDO::FETCH_OBJ);
                $query->execute(array(
                    ':dues_paid' => 0,
                    ':active' => 1));
                break;
            case "active":
                $query = $this->db->prepare('SELECT * FROM `users`
                    WHERE active=:active ORDER BY ' . $ordering . ' ASC');
                $query->setFetchMode(PDO::FETCH_OBJ);
                $query->execute(array(
                    ':active' => 1));
                break;
            case "non_active":
                $query = $this->db->prepare('SELECT * FROM `users`
                    WHERE active=:active ORDER BY ' . $ordering . ' ASC');
                $query->setFetchMode(PDO::FETCH_OBJ);
                $query->execute(array(
                    ':active' => 0));
                break;
            default: 
                $query = $this->db->prepare('SELECT * FROM `users`
                    ORDER BY ' . $ordering . ' ASC');
                $query->setFetchMode(PDO::FETCH_OBJ);
                $query->execute(array(
                    ':ordering' => $ordering));
        }

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $users[] = array(
                "user_id" => $row->user_id,
                "first_name" => $row->first_name,
                "last_name" => $row->last_name,
                "email" => $row->email,
                "phone" => $row->phone,
                "email_confirmed" => $row->email_confirmed,
                "dues_paid" => $row->dues_paid);
        }

        return $users;
    }

    // get ID of all events a user has attended
    public function getUserEvents($user_id) {

        $userEventsID = array();

        $query = $this->db->prepare('SELECT `event_id` FROM `event_attendees`
            WHERE user_id=:user_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':user_id' => $user_id));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) { $userEventsID[] = $row->event_id; }
        return $userEventsID;
    }

    // get user hours of an event; returns all hour types
    public function getUserHoursByEvent($user_id, $event_id) {

        $hours = array();

        $query = $this->db->prepare('SELECT * FROM `event_override_hours`
            WHERE event_id=:event_id AND user_id=:user_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id,
            ':user_id' => $user_id));

        if ($query->rowCount() == 0) {
            $event = (new EventFunctions)->getEventInfo($event_id);
            $hours['service_hours'] = $event['service_hours'];
            $hours['admin_hours'] = $event['admin_hours'];
            $hours['social_hours'] = $event['social_hours'];
        } else { 
            $row = $query->fetch();
            $hours['service_hours'] = $row->service_hours; 
            $hours['admin_hours'] = $row->admin_hours;
            $hours['social_hours'] = $row->social_hours;  
        }

        return $hours;
    }

    // get user data with user ID; option to include hours or not
    public function getUserInfo($user_id, $hours = false) {

        $userInfo = array();

        $query = $this->db->prepare('SELECT * FROM `users`
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
        $userInfo['email_confirmed'] = $row->email_confirmed;
        $userInfo['phone'] = $row->phone;
        $userInfo['dues_paid'] = $row->dues_paid;
        $userInfo['access'] = $row->access;
        $userInfo['active'] = $row->active;
        if ($hours) { $userInfo['hours'] = (new UserFunctions)->getTotalHours("all", $userInfo['user_id']); }
        return $userInfo;
    }

    // edit membership from active to non-active and vice-versa
    public function setActiveUser($user_ids, $active) {

        foreach ($user_ids as $user_id) {
            $query = $this->db->prepare('UPDATE users
                SET active=:active
                WHERE user_id=:user_id');
            if (!$query->execute(array(
                ':active' => $active,
                ':user_id' => $user_id))) { return false; }
        }
        return true;
    }

    // edit status from dues-paid to non-dues-paid and vice-versa
    public function setDuesPaidUser($user_ids, $dues_paid) {

        foreach ($user_ids as $user_id) {
            $query = $this->db->prepare('UPDATE users
                SET dues_paid=:dues_paid
                WHERE user_id=:user_id');
            if (!$query->execute(array(
                ':dues_paid' => $dues_paid,
                ':user_id' => $user_id))) { return false; }
        }
        return true;
    }


    // edit email
    public function setEmail($user_id, $email) {

        $query = $this->db->prepare('UPDATE users
            SET email=:email
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':email' => $email))) { return true; }
        else { return false; }
    }

    // edit user access
    public function setUserAccess($user_ids, $access) {

        foreach ($user_ids as $user_id) {
            $query = $this->db->prepare('UPDATE users
                SET access=:access
                WHERE user_id=:user_id');
            if (!$query->execute(array(
                ':user_id' => $user_id,
                ':access' => $access))) { return false; }
        }
        return true;
    }

    // edit first/last name 
    public function setName($user_id, $first_name, $last_name) {

        $query = $this->db->prepare('UPDATE users
            SET first_name=:first_name, last_name=:last_name
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':first_name' => $first_name,
            ':last_name' => $last_name))) { return true; }
        else { return false; }
    }

    // edit password
    public function setPassword($user_id, $password) {

        $query = $this->db->prepare('UPDATE users
            SET password=:password
            WHERE user_id=:user_id');
        if ($query->execute(array(
            'user_id' => $user_id,
            'password' => $password))) { return true; }
        else { return false; }
    }

    // edit phone number
    public function setPhone($user_id, $phone) {

        $query = $this->db->prepare('UPDATE users
            SET phone=:phone
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':phone' => $phone))) { return true; }
        else { return false; }
    }

    // given a string, separate the words and search users that contain those words
    public function searchUsers($searchWords, $searchType) {

        $users = array();
        $searchWords = explode(" ", $searchWords);
        $searchWords = array_unique($searchWords);
        $searchQuery = "SELECT * FROM `users` WHERE active=1 AND ";

        switch ($searchType) {
            case "name":
                foreach ($searchWords as $searchWord) {
                    if ($searchWord == end($searchWords)) {
                        $searchQuery .= "((first_name LIKE '%" . addslashes($searchWord) . "%') OR (last_name LIKE '%" . addslashes($searchWord) . "%')) ORDER BY `first_name` ASC";
                    } else {
                        $searchQuery .= "((first_name LIKE '%" . addslashes($searchWord) . "%') OR (last_name LIKE '%" . addslashes($searchWord) . "%')) AND";
                    }
                }
                break;
            case "email":
                foreach ($searchWords as $searchWord) {
                    if ($searchWord == end($searchWords)) {
                        $searchQuery .= "email LIKE '%" . addslashes($searchWord) . "%' ORDER BY `email` ASC";
                    } else {
                        $searchQuery .= "email LIKE '%" . addslashes($searchWord) . "%' AND";
                    }
                }
                break;
            case "phone":
                foreach ($searchWords as $searchWord) {
                    if ($searchWord == end($searchWords)) {
                        $searchQuery .= "phone LIKE '%" . addslashes($searchWord) . "%'";
                    } else {
                        $searchQuery .= "phone LIKE '%" . addslashes($searchWord) . "%' AND";
                    }
                }
                break;
        }

        $query = $this->db->prepare($searchQuery);
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute();

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $users[] = array(
                "user_id" => $row->user_id,
                "first_name" => $row->first_name,
                "last_name" => $row->last_name,
                "email" => $row->email,
                "phone" => $row->phone,
                "email_confirmed" => $row->email_confirmed,
                "dues_paid" => $row->dues_paid);
        }

        return $users;
    }
}

class EventFunctions extends Database {

    public function addEventAttendee($event_id, $user_id) {

        $query = $this->db->prepare('INSERT INTO `event_attendees`
            VALUES ("", :event_id, :user_id)');

        if ($query->execute(array(
            ':event_id' => $event_id,
            ':user_id' => $user_id))) {
            $query = $this->db->prepare('UPDATE `events`
                SET num_attendees = num_attendees + 1
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    public function addEventOtherAttendee($event_id, $data) {

        $query = $this->db->prepare('INSERT INTO `event_other_attendees`
            VALUES ("", :event_id, :club, :first_name, :last_name, :kiwanis_branch)');

        if ($query->execute(array(
            ':event_id' => $event_id,
            ':club' => $data['club'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':kiwanis_branch' => $data['kiwanis_branch']))) {
            $query = $this->db->prepare('UPDATE `events`
                SET num_other_attendees = num_other_attendees + 1
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    // add override hours for users
    public function addOverrideHours($event_id, $user_id) {

        $query = $this->db->prepare('INSERT INTO `event_override_hours`
            VALUES ("", :event_id, :user_id, 0, 0, 0)');

        if ($query->execute(array(
            ':event_id' => $event_id,
            ':user_id' => $user_id))) { 
            $query = $this->db->prepare('UPDATE `events`
                SET num_override_hours = num_override_hours + 1
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    // creates an event
    public function createEvent($eventData, $tag_ids) {

        $query = $this->db->prepare('INSERT INTO `events`
            VALUES ("", :name, :chair_id, :start_datetime, :end_datetime, :description, :location, :meeting_location,
                :online_signups, :online_end_datetime, 0, 0, 0, "", "", "", 0, 0, 0, 0, 0)');
        if ($query->execute(array(
            ':name' => $eventData['name'],
            ':chair_id' => $eventData['chair_id'],
            ':start_datetime' => date("Y-m-d H:i:s", $eventData['start_datetime']),
            ':end_datetime' => date("Y-m-d H:i:s", $eventData['end_datetime']),
            ':description' => $eventData['description'],
            ':location' => $eventData['location'],
            ':meeting_location' => $eventData['meeting_location'],
            ':online_signups' => $eventData['online_signups'],
            ':online_end_datetime' => date("Y-m-d H:i:s", $eventData['online_end_datetime'])))) {
            return $this->setEventTags($this->db->lastInsertId(), $tag_ids);
        } else { return false; }
    }

    // deletes an event
    public function deleteEvent($event_id) {

        $eventInfo = $this->getEventInfo($event_id);
        if ($eventInfo['status'] < 2) {
            $this->setEventTags($event_id, array());
            $query = $this->db->prepare('DELETE FROM `event_attendees`
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))) {
                $query = $this->db->prepare('DELETE FROM `events`
                    WHERE event_id=:event_id');
                if ($query->execute(array(
                    ':event_id' => $event_id))) { return true; }
                else { return false; }
            } else { return false; }
        } else { return false; }
    }

    public function deleteEventAttendee($event_id, $user_id) {

        $query = $this->db->prepare('DELETE FROM `event_attendees`
            WHERE event_id=:event_id AND user_id=:user_id');

        if ($query->execute(array(
            ':event_id' => $event_id,
            ':user_id' => $user_id))) {
            $query = $this->db->prepare('UPDATE `events`
                SET num_attendees = num_attendees - 1
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    public function deleteEventOtherAttendee($event_id, $id) {

        $query = $this->db->prepare('DELETE FROM `event_other_attendees`
            WHERE event_id=:event_id AND id=:id');

        if ($query->execute(array(
            ':event_id' => $event_id,
            ':id' => $id))) {
            $query = $this->db->prepare('UPDATE `events`
                SET num_other_attendees = num_other_attendees - 1
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    // delete users override hours
    public function deleteOverrideHours($event_id, $user_id) {

        $query = $this->db->prepare('DELETE FROM `event_override_hours`
            WHERE event_id=:event_id AND user_id=:user_id');

        if ($query->execute(array(
            ':event_id' => $event_id,
            ':user_id' => $user_id))) {
            $query = $this->db->prepare('UPDATE `events`
                SET num_override_hours = num_override_hours - 1
                WHERE event_id=:event_id');
            if ($query->execute(array(
                ':event_id' => $event_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    public function getConfirmedEvents() {

        $events = array();

        $query = $this->db->prepare('SELECT * FROM `events`
            WHERE status=2');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute();

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $events[] = array(
                'event_id' => $row->event_id,
                'name' => $row->name,
                'chair_id' => $row->chair_id,
                'start_datetime' => strtotime($row->start_datetime),
                'end_datetime' => strtotime($row->end_datetime),
                'meeting_location' => $row->meeting_location,
                'location' => $row->location,
                'num_attendees' => $row->num_attendees,
                'status' => $row->status);
        }

        return $events;
    }

    // get event attendees
    public function getEventAttendees($event_id, $id_only = false) {

        $eventAttendees = array();

        $query = $this->db->prepare('SELECT * FROM `event_attendees`
            WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id));
        
        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            if ($id_only) { $eventAttendees[] = $row->user_id; }
            else {
                $userInfo = (new UserFunctions)->getUserInfo($row->user_id);
                $eventAttendees[] = array(
                    'user_id' => $userInfo['user_id'],
                    'first_name' => $userInfo['first_name'],
                    'last_name' => $userInfo['last_name'],
                    'email' => $userInfo['email'],
                    'phone' => $userInfo['phone']);
            }
        }

        return array_orderby($eventAttendees, 'first_name', SORT_ASC);
    }

    // get event data with event ID
    public function getEventInfo($event_id) {

        $eventInfo = array();

        $query = $this->db->prepare('SELECT * FROM `events`
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
        $eventInfo['online_signups'] = $row->online_signups;
        $eventInfo['online_end_datetime'] = strtotime($row->online_end_datetime);
        $eventInfo['status'] = $row->status;
        $eventInfo['num_attendees'] = $row->num_attendees;
        $eventInfo['num_other_attendees'] = $row->num_other_attendees;
        $eventInfo['pros'] = $row->pros;
        $eventInfo['cons'] = $row->cons;
        $eventInfo['do_again'] = $row->do_again;
        $eventInfo['funds_raised'] = $row->funds_raised;
        $eventInfo['service_hours'] = $row->service_hours;
        $eventInfo['admin_hours'] = $row->admin_hours;
        $eventInfo['social_hours'] = $row->social_hours;
        $eventInfo['num_override_hours'] = $row->num_override_hours;
        $eventInfo['tag_ids'] = $this->getEventTags($row->event_id);
        return $eventInfo;
    }

    public function getEventOtherAttendees($event_id) {

        $eventOtherAttendees = array();

        $query = $this->db->prepare('SELECT * FROM `event_other_attendees`
            WHERE event_id=:event_id ORDER BY `first_name` ASC');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $eventOtherAttendees[] = array(
                'id' => $row->id,
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'club' => $row->club,
                'kiwanis_branch' => $row->kiwanis_branch);
        }

        return $eventOtherAttendees;
    }

    public function getEventTags($event_id) {

        $tag_ids = array();
        $query = $this->db->prepare('SELECT * FROM `event_tags`
            WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id)); 
        
        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) { $tag_ids[] = $row->tag_id; }

        return $tag_ids;
    }

    // get today's events
    // Assumes the date given is in UnixDateTime and is at the Date at 00:00:00
    // Finds events that start at the date at 00:00:00 to the next day at 00:00:00 
    public function getEventsByDate($date) {

        $events = array();
        $dateBegin = $date;
        $dateEnd = strtotime('+1 day', $date);

        $query = $this->db->prepare('SELECT * FROM `events`
            WHERE start_datetime >= FROM_UNIXTIME(:dateBegin) AND start_datetime < FROM_UNIXTIME(:dateEnd)' );
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':dateBegin' => $dateBegin,
            ':dateEnd' => $dateEnd));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $events[] = array(
                'event_id' => $row->event_id,
                'name' => $row->name,
                'chair_id' => $row->chair_id,
                'start_datetime' => strtotime($row->start_datetime),
                'end_datetime' => strtotime($row->end_datetime),
                'meeting_location' => $row->meeting_location,
                'location' => $row->location,
                'num_attendees' => $row->num_attendees,
                'status' => $row->status);
        }

        return $events;
    }

    // get month's events
    // Assumes the date given is in UnixDateTime and is at the first day of the month at 00:00:00
    // Finds events that start first day of the month at 00:00:00 to the first day of the next month at 00:00:00
    public function getEventsByMonth($month) {

        $events = array();
        $dateBegin = $month;
        $dateEnd = strtotime('+1 month', $month);

        $query = $this->db->prepare('SELECT * FROM `events`
            WHERE start_datetime >= FROM_UNIXTIME(:dateBegin) AND start_datetime < FROM_UNIXTIME(:dateEnd) ORDER BY `status` ASC' );
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':dateBegin' => $dateBegin,
            ':dateEnd' => $dateEnd));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $events[] = array(
                'event_id' => $row->event_id,
                'name' => $row->name,
                'chair_id' => $row->chair_id,
                'start_datetime' => strtotime($row->start_datetime),
                'end_datetime' => strtotime($row->end_datetime),
                'meeting_location' => $row->meeting_location,
                'location' => $row->location,
                'num_attendees' => $row->num_attendees,
                'status' => $row->status);
        }

        return $events;
    }
    public function getEventsInterval($start, $end) {

        $events = array();
        $dateBegin = $start;
        $dateEnd = $end;

        $query = $this->db->prepare('SELECT * FROM `events`
            WHERE start_datetime >= FROM_UNIXTIME(:dateBegin) AND start_datetime < FROM_UNIXTIME(:dateEnd) ORDER BY `status` ASC' );
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':dateBegin' => $dateBegin,
            ':dateEnd' => $dateEnd));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $events[] = array(
                'event_id' => $row->event_id,
                'name' => $row->name,
                'chair_id' => $row->chair_id,
                'start_datetime' => strtotime($row->start_datetime),
                'end_datetime' => strtotime($row->end_datetime),
                'meeting_location' => $row->meeting_location,
                'location' => $row->location,
                'num_attendees' => $row->num_attendees,
                'description' => $row->description,
                'status' => $row->status);
        }

        return $events;
    }

    public function getOverrideHours($event_id, $id_only = false) {

        $eventOverrideHours = array();

        $query = $this->db->prepare('SELECT * FROM `event_override_hours`
            WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            if ($id_only) { $eventOverrideHours[] = $row->user_id; }
            else {
                $userInfo = (new UserFunctions)->getUserInfo($row->user_id);
                $eventOverrideHours[] = array(
                    'user_id' => $row->user_id,
                    'first_name' => $userInfo['first_name'],
                    'last_name' => $userInfo['last_name'],
                    'service_hours' => $row->service_hours,
                    'admin_hours' => $row->admin_hours,
                    'social_hours' => $row->social_hours);
            }
        }

        return array_orderby($eventOverrideHours, 'first_name', SORT_ASC);
    }

    // edit event information
    public function setEvent($event_id, $eventData, $tag_ids) {

        $query = $this->db->prepare('UPDATE `events`
            SET name=:name, chair_id=:chair_id, start_datetime=:start_datetime, end_datetime=:end_datetime,
            description=:description, location=:location, meeting_location=:meeting_location,
            online_signups=:online_signups, online_end_datetime=:online_end_datetime, status=:status,
            pros=:pros, cons=:cons, do_again=:do_again, funds_raised=:funds_raised,
            service_hours=:service_hours, admin_hours=:admin_hours, social_hours=:social_hours
            WHERE event_id=:event_id');
        if ($query->execute(array(
            ':event_id' => $event_id,
            ':name' => $eventData['name'],
            ':chair_id' => $eventData['chair_id'],
            ':start_datetime' => date("Y-m-d H:m:s", $eventData['start_datetime']),
            ':end_datetime' => date("Y-m-d H:m:s", $eventData['end_datetime']),
            ':description' => $eventData['description'],
            ':location' => $eventData['location'],
            ':meeting_location' => $eventData['meeting_location'],
            ':online_signups' => $eventData['online_signups'],
            ':online_end_datetime' => date("Y-m-d H:m:s", $eventData['online_end_datetime']),
            ':status' => $eventData['status'],
            ':pros' => $eventData['pros'],
            ':cons' => $eventData['cons'],
            ':do_again' => $eventData['do_again'],
            ':funds_raised' => $eventData['funds_raised'],
            ':service_hours' => $eventData['service_hours'],
            ':admin_hours' => $eventData['admin_hours'],
            ':social_hours' => $eventData['social_hours']))) { return $this->setEventTags($event_id, $tag_ids); }
        else { return false; }
    }

    public function setEventTags($event_id, $tag_ids) {

        $query = $this->db->prepare('SELECT * FROM `event_tags`
            WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id));

        if ($query->rowCount() == 0) {
            foreach ($tag_ids as $tag_id) {
                $queryInsertAll = $this->db->prepare('INSERT INTO `event_tags`
                    VALUES ("", :event_id, :tag_id)');
                if (!$queryInsertAll->execute(array(
                    ':event_id' => $event_id,
                    ':tag_id' => $tag_id))) { return false; }
            }
        } else {
            while ($row = $query->fetch()) {
                if (!in_array($row->tag_id, $tag_ids)) {
                    $queryDeleteTag = $this->db->prepare('DELETE FROM `event_tags`
                        WHERE event_id=:event_id AND tag_id=:tag_id');
                    if (!$queryDeleteTag->execute(array(
                        ':event_id' => $event_id,
                        ':tag_id' => $row->tag_id))) { return false; }
                } else {
                    if (($key = array_search($row->tag_id, $tag_ids)) !== false) {
                        unset($tag_ids[$key]);
                    }
                }
            }
            foreach ($tag_ids as $tag_id) {
                $queryInsertAll = $this->db->prepare('INSERT INTO `event_tags`
                    VALUES ("", :event_id, :tag_id)');
                if (!$queryInsertAll->execute(array(
                    ':event_id' => $event_id,
                    ':tag_id' => $tag_id))) { return false; }
            }
        }
        return true;
    }

    // verify event
    public function setEventStatus($event_id, $status) {

        $eventInfo = $this->getEventInfo($event_id);
        switch ($status) {
            case 1:
                if ($eventInfo['status'] < 0 && $eventInfo['status'] > 2) { return false; }
                break;
            case 2:
                if ($eventInfo['status'] != 1) { return false; }
                break;
            case 3:
                if ($eventInfo['status'] != 2) { return false; }
                break;
            default:
                return false;
        }
        $query = $this->db->prepare('UPDATE events
            SET status=:status
            WHERE event_id=:event_id');
        if ($query->execute(array(
            ':event_id' => $event_id,
            ':status' => $status))) {
                return true;
        } else { return false; }
    }

    // edits users override hours
    public function setOverrideHours($event_id, $user_id, $userHours) {

        $query = $this->db->prepare('UPDATE `event_override_hours`
            SET service_hours=:service_hours, admin_hours=:admin_hours, social_hours=:social_hours
            WHERE event_id=:event_id AND user_id=:user_id');

        if ($query->execute(array(
            ':event_id' => $event_id,
            ':user_id' => $user_id,
            ':service_hours' => $userHours['service_hours'],
            ':admin_hours' => $userHours['admin_hours'],
            ':social_hours' => $userHours['social_hours']))) { return true; } 
        else { return false; }
    }
}

class GeneralFunctions extends Database {

}

class TagFunctions extends Database {

    // add mrp or mrf tag
    public function addTag($tagData) {

        $query = $this->db->prepare('INSERT INTO `tags`
            VALUES ("", :name, :abbr, :auto_manage, :mrp_tag, :number, 1)');

        if ($query->execute(array(
            ':name' => $tagData['name'],
            ':abbr' => $tagData['abbr'],
            ':auto_manage' => $tagData['auto_manage'],
            ':mrp_tag' => $tagData['mrp_tag'],
            ':number' => $tagData['number']))) { return true; }
        else { return false; }
    }

    // add mrp level
    public function addMRPLevel($mrpdata) {

        $query = $this->db->prepare('INSERT INTO `mrp_levels`
            VALUES("", :name, :hours, 0)');

            if ($query->execute(array(
                ':name' => $mrpdata['name'],
                ':hours' => $mrpdata['hours']))) { return true; }
            else { return false; }
    }

    // delete mrp or mrf tags; requires that no events have
    public function deleteTag($tag_id) {

        $query = $this->db->prepare('SELECT * FROM `event_tags`
            WHERE tag_id=:tag_id LIMIT 1');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':tag_id' => $tag_id)); 

        if ($query->rowCount() == 0) {
            $query = $this->db->prepare('DELETE FROM `tags`
                WHERE tag_id=:tag_id');
            if ($query->execute(array(
                ':tag_id' => $tag_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    //Get MRP Levels
    public function getMRPLevels() {
        $mrpLevels = array();
        $query = $this->db->prepare('SELECT * FROM `mrp_levels`
        ORDER BY `hours` ASC');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute();
        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $mrpLevels[] = array(
                "level_id" => $row->level_id,
                "name" => $row->name,
                "hours" => $row->hours,
                "num_required" => $row->num_required);
        }
        return $mrpLevels;
    }

    //Get MRP Info
    public function getMRPInfo($level_id) {
        $level = array();

        $query = $this->db->prepare('SELECT * FROM `mrp_levels`
           WHERE level_id=:level_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':level_id' => $level_id));

        if ($query->rowCount() == 0) {return false; }
        $row = $query->fetch();
        $level['level_id'] = $row->level_id;
        $level['name'] = $row->name;
        $level['hours'] = $row->hours;
        $level['num_required'] = $row->num_required;
        return $level;
    }

    // delete mrp level
    public function deleteMRPlevel($level_id) {

        $query = $this->db->prepare('DELETE FROM `mrp_levels`
            WHERE level_id=:level_id');

        if ($query->execute(array(
            ':level_id' => $level_id))) { return true; }
        else { return false; }
    }

    public function getTag($tag_id) {

        $tag = array();

        $query = $this->db->prepare('SELECT * FROM `tags`
           WHERE tag_id=:tag_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':tag_id' => $tag_id));

        if ($query->rowCount() == 0) { return false; }
        $row = $query->fetch();
        $tag['tag_id'] = $row->tag_id;
        $tag['name'] = $row->name;
        $tag['abbr'] = $row->abbr;
        $tag['mrp_tag'] = $row->mrp_tag;
        $tag['auto_manage'] = $row->auto_manage;
        $tag['number'] = $row->number;
        $tag['active'] = $row->active;
        return $tag;
    }

    public function getTags($tag_type = "mrf", $active = 1) {

        $tags = array();
        switch ($tag_type) {
            case "mrf":
                $query = $this->db->prepare('SELECT * FROM `tags`
                    WHERE mrp_tag=0 AND active=:active ORDER BY `abbr` ASC');
                break;
            case "mrp":
                $query = $this->db->prepare('SELECT * FROM `tags`
                    WHERE mrp_tag=1 AND active=:active ORDER BY `abbr` ASC');
                break;
            case "all":
                $query = $this->db->prepare('SELECT * FROM `tags`
                    WHERE active=:active ORDER BY `abbr` ASC');
            default:
                $query = $this->db->prepare('SELECT * FROM `tags`
                    WHERE active=:active ORDER BY `abbr` ASC');
        }
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':active' => $active));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $tags[] = array(
                "tag_id" => $row->tag_id,
                "name" => $row->name,
                "abbr" => $row->abbr,
                "mrp_tag" => $row->mrp_tag,
                "auto_manage" => $row->auto_manage,
                "number" => $row->number,
                "active" => $row->active);
        }

        return $tags;
    }

    // change whether a tag is active or deactive
    public function setActiveTag($tag_id, $active) {

        $query = $this->db->prepare('UPDATE `tags`
            SET active=:active
            WHERE tag_id=:tag_id');
        if ($query->execute(array(
            ':tag_id' => $tag_id))) { return true; }
        else { return true; }
    }
}

class CommitteeFunctions extends Database {

    // add a committee
    public function addCommittee($name) {

        $query = $this->db->prepare('INSERT INTO `committees`
            VALUES ("", :name)');
        if ($query->execute(array(
            ':name' => $name))) { return true; }
        else { return false; }
    }

    // delete a committee
    public function deleteCommittee($committee_id) {

        $query = $this->db->prepare('DELETE FROM `committee_members`
            WHERE committee_id=:committee_id');
        if ($query->execute(array(
            ':committee_id' => $committee_id))) {
            $query = $this->db->prepare('DELETE FROM `committees`
                WHERE committee_id=:committee_id');
            if ($query->execute(array(
                ':committee_id' => $committee_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    // add a committee member to a committee
    public function addCommitteeMember($committee_id, $user_id) {

        $query = $this->db->prepare('INSERT INTO `committee_members`
            VALUES ("", :committee_id, :user_id)');

        if ($query->execute(array(
            ':committee_id' => $committee_id,
            ':user_id' => $user_id))) { return true;
        } else { return false; }
    }

    // remove a committee member to a committee
    public function deleteCommitteeMember($committee_id, $user_id) {
        
        $query = $this->db->prepare('DELETE FROM `committee_members`
            WHERE committee_id=:committee_id AND user_id=:user_id');
        if ($query->execute(array(
            ':committee_id' => $committee_id,
            ':user_id' => $user_id))) { return true;
        } else { return false; }
    }

    public function getCommittee($committee_id) {

        $query = $this->db->prepare('SELECT * FROM `committees`
            WHERE committee_id=:committee_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':committee_id' => $committee_id));

        if ($query->rowCount() == 0) { return false; }
        $row = $query->fetch();
        $committee = array(
            'committee_id' => $row->committee_id,
            'name' => $row->name,
            'members' => $this->getCommitteeMembers($row->committee_id));

        return $committee;
    }

    public function getCommittees() {

        $committees = array();

        $query = $this->db->prepare('SELECT * FROM `committees` ORDER BY `name` ASC');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute();

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $committees[] = array(
                'committee_id' => $row->committee_id,
                'name' => $row->name,
                'members' => $this->getCommitteeMembers($row->committee_id));
        }

        return $committees;
    }

    // get members
    public function getCommitteeMembers($committee_id, $id_only = false) {

        $committeeMembers = array();

        $query = $this->db->prepare('SELECT * FROM `committee_members`
            WHERE committee_id=:committee_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':committee_id' => $committee_id));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            if ($id_only) { $committeeMembers[] = $row->user_id; }
            else {
                $userInfo = (new UserFunctions)->getUserInfo($row->user_id);
                $committeeMembers[] = array(
                    'user_id' => $userInfo['user_id'],
                    'first_name' => $userInfo['first_name'],
                    'last_name' => $userInfo['last_name'],
                    'email' => $userInfo['email']);
            }
        }

        return array_orderby($committeeMembers, 'first_name', SORT_ASC);
    }
}

class BlogFunctions extends Database {

    // creates an Blog Post
    public function createBlogPost($postData) {

        $query = $this->db->prepare('INSERT INTO `blog`
            VALUES ("", :title, :story, :author_id, :publish_datetime  )');
        if ($query->execute(array(
            ':title' => $postData['title'],
            ':story' => $postData['story'],
            ':author_id' => $postData['author_id'],
            ':publish_datetime' => date("Y-m-d H:i:s", $postData['publish_datetime'])))) {
            return true;
        } else { return false; }
    }

    // deletes an event
    public function deletePost($post_id) {

        $query = $this->db->prepare('DELETE FROM `blog`
            WHERE post_id=:post_id');
        if ($query->execute(array(
            ':post_id' => $post_id))) {
            $query = $this->db->prepare('DELETE FROM `blog`
                WHERE post_id=:post_id');
            if ($query->execute(array(
                ':post_id' => $post_id))) { return true; }
            else { return false; }
        } else { return false; }
    }

    // get posts with post ID
    public function getPostInfo($post_id) {

        $postInfo = array();

        $query = $this->db->prepare('SELECT * FROM `blog`
            WHERE post_id=:post_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':post_id' => $post_id));

        if ($query->rowCount() == 0) { return false; }
        $row = $query->fetch();
        $postInfo['post_id'] = $row->post_id;
        $postInfo['title'] = $row->title;
        $postInfo['author_id'] = $row->author_id;
        $postInfo['publish_datetime'] = strtotime($row->publish_datetime);
        $postInfo['story'] = $row->story;
        return $postInfo;
    }

    // get month's posts
    // Assumes the date given is in UnixDateTime and is at the first day of the month at 00:00:00
    // Finds events that start first day of the month at 00:00:00 to the first day of the next month at 00:00:00
    // get month's posts
    // Assumes the date given is in UnixDateTime and is at the first day of the month at 00:00:00
    // Finds events that start first day of the month at 00:00:00 to the first day of the next month at 00:00:00
    public function getPostsByMonth($month) {
        
        $events = array();
        $dateBegin = $month;
        $dateEnd = strtotime('+1 month', $month);
        $query = $this->db->prepare('SELECT * FROM `blog`
            WHERE publish_datetime >= FROM_UNIXTIME(:dateBegin) AND publish_datetime < FROM_UNIXTIME(:dateEnd) ORDER BY `publish_datetime` DESC' );
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':dateBegin' => $dateBegin,
            ':dateEnd' => $dateEnd));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $posts[] = array(
                'post_id' => $row->post_id,
                'title' => $row->title,
                'author_id' => $row->author_id,
                'publish_datetime' => strtotime($row->publish_datetime),
                'story' => $row->story);
        }

        return $posts;
    }

    // edit event information
    public function setPost($post_id, $postData) {

        $query = $this->db->prepare('UPDATE `blog`
            SET title=:title, author_id=:author_id, publish_datetime=:publish_datetime
            WHERE post_id=:post_id');
        if ($query->execute(array(
            ':post_id' => $post_id,
            ':title' => $postData['title'],
            ':author_id' => $postData['author_id'],
            ':publish_datetime' => date("Y-m-d H:m:s", $postData['publish_datetime']),
            ':story' => $postData['story']))) { return true; }
        else { return false; }
    }
}
  





?>