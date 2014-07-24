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
define("MYSQL_USER", "root");
define("MYSQL_PASS", "root");
define("MYSQL_DB", "dev_ckirfsystem");

class DatabaseFunctions {

    private $db;

    // construct to connect to database
    public function __construct() {

        try {
            $this->db = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB, MYSQL_USER, MYSQL_PASS);
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // destruct to destroy conection to database at end
    public function __destruct() { $this->db = null; }

    // get member information
    public function getMembers($dues_paid = false, $ordering = "last_name") {

        $members = array();
        if ($dues_paid) {
            $query = $this->db->prepare('SELECT * FROM `users`
                WHERE dues_paid=:dues_paid ORDER BY :ordering ASC');
            $query->setFetchMode(PDO::FETCH_OBJ);
            $query->execute(array(
                ':dues_paid' => 1,
                ':ordering' => $ordering));
        } else {
            $query = $this->db->prepare('SELECT * FROM `users`
                ORDER BY :ordering ASC');
            $query->setFetchMode(PDO::FETCH_OBJ);
            $query->execute(array(
                ':ordering' => $ordering));
        }

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $members[] = array(
                "user_id" => $row->user_id,
                "first_name" => $row->first_name,
                "last_name" => $row->last_name,
                "email" => $row->email,
                "phone" => $row->phone);
        }

        return $members;
    }

    public function getTags($tag_type = "mrf", $active = 1) {

        $tags = array();
        switch ($tag_type) {
            case "mrf":
                $query = $this->db->prepare('SELECT * FROM `tags`
                    WHERE mrp_tag=0 AND active=:active ORDER BY `name` ASC');
                break;
            case "mrp":
                $query = $this->db->prepare('SELECT * FROM `tags`
                    WHERE mrp_tag=1 AND active=:active ORDER BY `name` ASC');
                break;
            default:
                $query = $this->db->prepare('SELECT * FROM `tags`
                    WHERE active=:active ORDER BY `name` ASC');
        }
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':active' => $active));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {

            $tags[] = array(
                "tag_id" => $row->tag_id,
                "name" => $row->name,
                "abbr" => $row->abbr);
        }

        return $tags;
    }

    public function getTag($tag_id) {

        $tag = array();
        $query = $this->db->prepare('SELECT * FROM `tags`
           WHERE tag_id=:tag_id ORDER BY `name` ASC');
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

    // get user data with user ID
    public function getUserInfo($user_id) {

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
        return $userInfo;
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

        return $hours;
    }

    // get total hours of the club or a user
    // can specify what type of hours or all hours (service, admin, social, all)
    public function getTotal($typeHours, $user_id = null) {

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

            $userEventsID = $self->getUserEventsID($user_id);
            foreach ($userEventsID as $event_id) {
                $hours = $this->getUserHoursByEventID($user_id, $event_id);
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

        $query = $this->db->prepare('INSERT INTO `events`
            VALUES ("", :name, :chair_id, :start_datetime, :end_datetime, :description, :location, :meeting_location,
                :online_signups, :online_end_datetime, :status, 0, 0, "", "", "", 0, 0, 0, 0, 0)');
        if ($query->execute(array(
            ':name' => $eventData['name'],
            ':chair_id' => $eventData['chair_id'],
            ':start_datetime' => date("Y-m-d H:i:s", $eventData['start_datetime']),
            ':end_datetime' => date("Y-m-d H:i:s", $eventData['end_datetime']),
            ':description' => $eventData['description'],
            ':location' => $eventData['location'],
            ':meeting_location' => $eventData['meeting_location'],
            ':online_signups' => $eventData['online_signups'],
            ':online_end_datetime' => date("Y-m-d H:i:s", $eventData['online_end_datetime']),
            ':status' => $eventData['status']))) { return "Event " . $eventData['name'] . " was successfully created!"; }
        else { return "An Error has Occurred! Error: " . $db->errorInfo(); }
    }

    // get today's events
    // Assumes the date given is in UnixDateTime and is at the Date at 00:00:00
    // Finds events that start at the date at 00:00:00 to the next day at 00:00:00 
    public function getEventsByDate($date) {

        $events = array();
        $dateBegin = $date;
        $dateEnd = strtotime('+1 day', $date);

        $query = $this->db->prepare('SELECT * FROM `events`
            WHERE start_datetime >= FROM_UNIXTIME(:dateBegin) AND FROM_UNIXTIME(:dateEnd) <= end_datetime' );
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
            WHERE start_datetime >= FROM_UNIXTIME(:dateBegin) AND FROM_UNIXTIME(:dateEnd) <= end_datetime' );
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

    // verify event
    public function setEventStatus($event_id, $status) {

        $eventInfo = $this->getEventInfo($event_id);
        switch ($status) {
            case 1:
                if ($eventInfo['access'] != 0) {
                    return "This event is not a Pre-Event. Cannot change to Post-Event.";
                }
                break;
            case 2:
                if ($eventInfo['access'] != 1) {
                    return "This event is not a Post-Event. Cannot change to Completed.";
                }
                break;
            case 3:
                if ($eventInfo['access'] != 2) {
                    return "This event is not Completed. Cannot change to Verified.";
                }
                break;
            default:
                return "No status given to change to.";
        }
        $query = $this->db->prepare('UPDATE events
            SET status=:status
            WHERE event_id=:event_id');
        if ($query->execute(array(
            ':event_id' => $event_id,
            ':status' => $status))) {
                return "Event status successfully changed.";
        } else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // get event attendees
    public function getEventAttendees($event_id) {

        $eventAttendees = array();

        $query = $this->db->prepare('SELECT * FROM `event_attendees`
            WHERE event_id=:event_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':event_id' => $event_id));

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
            $query = $this->db->prepare('INSERT INTO `event_override_hours`
                VALUES ("", :event_id, :user_id, :service_hours, :admin_hours, :social_hours)');

            if ($query->execute(array(
                ':event_id' => $event_id,
                ':user_id' => $user['user_id'],
                ':service_hours' => $user['service_hours'],
                ':admin_hours' => $user['admin_hours'],
                ':social_hours' => $user['social_hours']))) { 
                continue; 
            } else { return "An error has occurred! Error: " . $db->errorInfo(); }
        }
    }

    // register user
    public function registerUser($userData) {

        $query = $this->db->prepare('INSERT INTO `users`
            VALUES ("", :user_id, :first_name, :last_name, :username, :password, :email, 0, :phone, 0, 0, 1)');

        if ($query->execute(array(
            ':user_id' => $userData['user_id'],
            ':first_name' => $userData['first_name'],
            ':last_name' => $userData['last_name'],
            ':username' => $userData['username'],
            ':password' => password_hash($userData['password'], PASSWORD_BCRYPT),
            ':email' => $userData['email'],
            ':phone' => $userData['phone']
            ))) { return "Welcome to UCBCKI " . $userData['first_name'] ."! Your user profile was successfully created!"; }
            else { return "An error has occurred! Error: " . $db->errorInfo(); } 
    }

    // change user access
    public function changeUserAccess($user_id, $access) {

        if ($access == 0) {
            $accessValue = 'General Member';
        } else if ($access == 1) {
            $accessValue = 'Board Member';
        } else if ($access == 2) {
            $accessValue = 'Secretary';
        } else { 
            $accessValue = 'Technology Chair';
        }

        $userInfo = $this->getUserInfo($user_id);

        $query = $this->db->prepare('UPDATE users
            SET access=:access
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':access' => $access))) { return "Successfully changed access for " . $userInfo['first_name'] . " " . $userInfo['last_name'] . "to " . $accessValue; }
        else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // change first/last name 
    public function changeName($user_id, $first_name, $last_name) {

        $oldName = $this->db->getUserInfo($user_id);

        $query = $this->db->prepare('UPDATE users
            SET first_name=:first_name, last_name=:last_name
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':first_name' => $first_name,
            ':last_name' => $last_name))) { return "Sucessfully changed name from " . $oldName['first_name'] . " " . $oldName['last_name'] . "to " . $first_name . " " . $last_name; }
        else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // change email
    public function changeEmail($user_id, $email) {

        $oldEmail = $this->db->getUserInfo($user_id);

        $query = $this->db->prepare('UPDATE users
            SET email=:email
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':email' => $email))) { return "Successfully changed email from " . $oldEmail['email'] . "to " . $email; }
        else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // change phone number
    public function changePhone($user_id, $phone) {

        $oldPhone = $this->db->getUserInfo($user_id);

        $query = $this->db->prepare('UPDATE users
            SET phone=:phone
            WHERE user_id=:user_id');
        if ($query->execute(array(
            ':user_id' => $user_id,
            ':phone' => $phone))) { return "Successfully changed phone from " . $oldPhone['phone'] . "to " . $phone; }
        else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // change password
    public function changePassword($user_id, $password) {

        $query = $this->db->prepare('UPDATE users
            SET password=:password
            WHERE user_id=:user_id');
        if ($query->execute(array(
            'user_id' => $user_id,
            'password' => password_hash($password, PASSWORD_BCRYPT)))) { return "Successfully changed password!"; }
        else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // change membership from active to non-active and vice-versa
    public function changeActiveMembership($user_ids) {

        foreach ($user_ids as $user_id) {
            $userInfo = $this->getUserInfo($user_id);
            if ($userInfo['active'] == 0) {
                $query = $this->db->prepare('UPDATE users
                    SET active = 1
                    WHERE user_id=:user_id');   
            } else if ($userInfo['active'] == 1) {
                $query = $this->db->prepare('UPDATE users
                    SET active = 0
                    WHERE user_id=:user_id');   
            }
        }
        if ($query->execute(array(
            ':user_id' => $user_id))) { return "Successfully changed active statuses."; }
    }

    // change status from dues-paid to non-dues-paid and vice-versa
    public function changeDuesPaidMembership($user_ids) {

        foreach ($user_id as $user_id) {
            $userInfo = $this->getUserInfo($user_id);
            if ($userInfo['dues_paid'] == 0) {
                $query = $this->db->prepare('UPDATE users
                    SET dues_paid = 1
                    WHERE user_id=:user_id');   
            } else if ($userInfo['dues_paid'] == 1) {
                $query = $this->db->prepare('UPDATE users
                    SET dues_paid = 0
                    WHERE user_id=:user_id');   
            }
        }
        if ($query->execute(array(
            ':user_id' => $user_id))) { return "Successfully changed dues-paid statuses."; }
    }

    // add a committee
    public function addCommittee($name) {

        $query = $this->db->prepare('INSERT INTO `committees`
            VALUES ("", :name)');
        if ($query->execute(array(
            ':name' => $name))) { return "Successfully added " . $name . " committee!"; }
        else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // delete a committee
    public function deleteCommittee($committee_id) {

        $query = $this->db->prepare('SELECT * FROM `committee_members`
            WHERE committee_id=:committee_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':committee_id' => $committee_id));

        if ($query->rowCount() == 0) {
            $query = $this->db->prepare('DELETE FROM `committees`
                WHERE :committee_id = $committee_id');
            if ($query->execute(array(
                ':committee_id' => $committee_id))) { return "Successfully deleted " . $name . " committee!";
            } else { return "An error has occurred! Error: " . $db->errorInfo(); }
        } else { return "Committee member still exist in this committee! Delete members from that committee first."; }
    }

    // add a committee member to a committee
    public function addCommitteeMembers($committee_id, $user_ids) {

        foreach ($user_ids as $user_id) {
            $query = $this->db->prepare('INSERT INTO `committee_members`
                VALUES ("", :committee_id, :user_id)');

            if ($query->execute(array(
                ':committee_id' => $committee_id,
                ':user_id' => $user_id))) { continue;
            } else { return "An error has occurred! Error: " . $db->errorInfo(); }
        }
    }

    // remove a committee member to a committee
    public function deleteCommitteeMembers($committee_id, $user_ids) {
        
        foreach ($user_ids as $user_id) {
            $query = $this->db->prepare('DELETE FROM `committee_members`
                WHERE committee_id=:committee_id AND user_id=:user_id');
            if ($query->execute(array(
                ':committee_id' => $committee_id,
                ':user_id' => $user_id))) { continue;
            } else { return "An error has occurred! Error: " . $db->errorInfo(); }
        }
    }

    // get members
    public function getCommitteeMembers($committee_id) {

        $committeeMembers = array();

        $query = $this->db->prepare('SELECT * FROM `committee_members`
            WHERE committee_id=:committee_id');
        $query->setFetchMode(PDO::FETCH_OBJ);
        $query->execute(array(
            ':committee_id' => $committee_id));

        if ($query->rowCount() == 0) { return false; }
        while ($row = $query->fetch()) {
            $userInfo = $this->getUserInfo($row->user_id);
            $committeeMembers[] = array(
                'first_name' => $userInfo['first_name'],
                'last_name' => $userInfo['last_name'],
                'email' => $userInfo['email']);
        }

        return $committeeMembers;
    }

    // changes users override hours
    public function changeOverrideHours($event_id, $overrideUsers) {

        foreach ($overrideUsers as $user) {
            $query = $this->db->prepare('UPDATE `event_override_hours`
                SET service_hours=:service_hours, admin_hours=:admin_hours, social_hours=:social_hours
                WHERE user_id=:user_id');

            if ($query->execute(array(
                ':user_id' => $user['user_id'],
                ':service_hours' => $user['service_hours'],
                ':admin_hours' => $user['admin_hours'],
                ':social_hours' => $user['social_hours']))) { 
                continue; 
            } else { return "An error has Occurred! Error: " . $db->errorInfo(); }
        }
    }

    // delete users override hours
    public function deleteOverrideHours($event_id, $user_ids) {

        foreach ($overrideUsers as $user) {
            $query = $this->db->prepare('DELETE FROM `event_override_hours`
                WHERE event_id=:event_id AND user_id=:user_id');

            if ($query->execute(array(
                ':event_id' => $event_id,
                ':user_id' => $user_id))) { 
                continue; 
            } else { return "An error has Occurred! Error: " . $db->errorInfo(); }
        }
    }

    // change event information
    public function changeEvent($event_id, $eventData) {

        $query = $this->db->prepare('UPDATE `events`
            SET name=:name, chair_id=:chair_id, start_datetime=:start_datetime, end_datetime=:end_datetime,
            description=:description, location=:location, meeting_location=:meeting_location,
            online_signups=:online_signups, online_end_datetime=:online_end_datetime, status=:status
            WHERE event_id=:event_id');
        if ($query->execute(array(
            ':name' => $eventData['name'],
            ':chair_id' => $eventData['chair_id'],
            ':start_datetime' => date("Y-m-d H:m:s", $eventData['start_datetime']),
            ':end_datetime' => date("Y-m-d H:m:s", $eventData['end_datetime']),
            ':description' => $eventData['description'],
            ':location' => $eventData['location'],
            ':meeting_location' => $eventData['meeting_location'],
            ':online_signups' => $eventData['online_signups'],
            ':online_end_datetime' => date("Y-m-d H:m:s", $eventData['online_end_datetime']),
            ':status' => $eventData['status']))) { return "Event " . $eventData['name'] . " was successfully changed!"; }
        else { return "An Error has Occurred! Error: " . $db->errorInfo(); }
    }

    // add mrp or mrf tag
    public function addTag($tagData) {

        $query = $this->db->prepare('INSERT INTO `tags`
            VALUES ("", :name, :abbr, :auto_manage, :mrp_tag, :number, :active)');

        if ($query->execute(array(
            ':name' => $tagData['name'],
            ':abbr' => $tagData['abbr'],
            ':auto_manage' => $tagData['auto_manage'],
            ':mrp_tag' => $tagData['mrp_tag'],
            ':number' => $tagData['number'],
            ':active' => $tagData['active']))) { return "Tag " . $tagData['name'] . " was successfully added!"; }
        else { return "An error has occurred! Error: " . $dp->errorInfo(); }
    }

    // delete mrp or mrf tags; requires that no events have
    public function deleteTag($tag_ids) {

        foreach ($tag_ids as $tag_id) {
            $query = $this->db->prepare('DELETE FROM `tags`
                WHERE tag_id=:tag_id');
            if ($query->execute(array(
                ':tag_id' => $tag_id))) { continue;
            } else { return "An error has occurred! Error: " . $db->errorInfo(); }
        }
    }

    // change whether a tag is active or deactive
    public function changeActiveTag($tag_ids) {

        foreach ($tag_ids as $tag) {

            $query = $this->db->prepare('SELECT `active` FROM `tags`
                WHERE tag_id=:tag_id');
            $query->setFetchMode(PDO::FETCH_OBJ);
            $query->execute(array(
                ':tag_id' => $tag_id));
            if ($query->rowCount() == 0) { return false; }
            $row = $query->fetch();
            $activeness = $row->active;

            $query = $this->db->prepare('UPDATE `tags`
                SET active=:active
                WHERE tag_id=:tag_id');
            if ($activeness == 1) { 
                $query->execute(array(
                    ':active' => 0,
                    ':tag_id' => $tag_id));
            } else {
                $query->execute(array(
                    ':active' => 1,
                    ':tag_id' => $tag_id));
            }
        }
    }

        
    // add mrp level
    public function addMRPLevel($mrpdata) {

        $query = $this->db->prepare('INSERT INTO `mrp_levels`
            VALUES("", :level_id, :name, :hours, :num_required)');

            if ($query->execute(array(
                ':level_id' => $mrpdata['level_id'],
                ':name' => $mrpdata['name'],
                ':hours' => $mrpdata['hours'],
                ':num_required' => $mrpdata['num_required'])) { return "MRP Level " . $mrpdata['name'] . " was successfully added!"; }
            else { return "An error has occurred! Error: " . $db->errorInfo(); }
    }

    // delete mrp level
    public function deleteMRPlevel($level_ids) {

        foreach ($level_ids as $level_id) {
            $query = $this->db->prepare('DELETE FROM `mrp_levels`
                WHERE level_id=:level_id');

            if ($query->execute(array(
                ':level_id' => $level_id))) { continue;
            } else { return "An error has occurred! Error: " . $db->errorInfo(); }
        }
    }

}

?>