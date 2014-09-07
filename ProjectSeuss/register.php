<?php 
        ini_set('display_errors', 1);
        
        require_once("admin/dbfunc.php");
        
        $userdb = new UserFunctions;
        $userData = array(
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "username" => $_POST['username'],
            "password" => password_hash($_POST['password'], PASSWORD_BCRYPT),
            "email" => $_POST['email'],
            "phone" => $_POST['phone']);
        if ($userdb->addUser($userData)) {
            $message = "SUCCESS: " . $userData['first_name'] . " " . $userData['last_name'] . " was added to the database!";
            setcookie("successmsg", $message, time()+3);
        } else {
            $message = "DATABASE ERROR: User could not be added!";
            setcookie("errormsg", $message, time()+3);
        }
        $location = 'Location: /~circlek/index.php'; 
        header($location);

        ?>