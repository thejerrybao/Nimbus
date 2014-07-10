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

    public function __destruct() {
        $this->$db = null;
    }

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

    public function getServiceHours($user_id = 0) {

    }
}

?>