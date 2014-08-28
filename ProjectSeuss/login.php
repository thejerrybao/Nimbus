<?php
ini_set('display_errors', 1);
        $path = $_SERVER['DOCUMENT_ROOT'];
        $path .= "/admin/dbfunc.php";
        include_once($path);
        $db = new UserFunctions;
        $userData = $db->login($_POST['username'], $_POST['password']);
        if ($userData) {
            session_start();
            $_SESSION['cki_rf_user_id'] = $userData['user_id'];
            $_SESSION['cki_rf_access'] = $userData['access'];
            $_SESSION['cki_rf_first_name'] = $userData['first_name'];
            $_SESSION['cki_rf_last_name'] = $userData['last_name'];
            $location = 'Location: index.php';
            
        } else { echo "Incorrect username/password."; } 
?>