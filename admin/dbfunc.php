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

    function __construct() {
        try {
            $this->$db = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB, MYSQL_USER, MYSQL_PASS);
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

?>