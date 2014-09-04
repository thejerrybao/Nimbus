<?php
ini_set('display_errors', 1);
        include_once("admin/dbfunc.php");
        $db = new UserFunctions;
        $userData = $db->login($_POST['username'], $_POST['password']);
        if ($userData) {
            session_start();
            $_SESSION['nimbus_user_id'] = $userData['user_id'];
            $_SESSION['nimbus_access'] = $userData['access'];
            $_SESSION['nimbus_first_name'] = $userData['first_name'];
            $_SESSION['nimbus_last_name'] = $userData['last_name'];
            $location = "Location: ".$_POST['url'];
        } else { $location = "Location: ".$_POST['url']; } 
        header($location);
?>