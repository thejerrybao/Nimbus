<?php 
ini_set('display_errors', 1);
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/admin/dbfunc.php";
include_once($path);

if (isset($_POST['first_name'])) {
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
    $location = 'Location: login.php'; 
    header($location);
}

$page = "register";
$pageTitle = "Register";
$customCSS = false;
$customJS = false;
?>

<!DOCTYPE html>
<html lang="en">

<? require_once("header.php"); ?>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Register</h3>
                    </div>
                    <div class="panel-body">
                        <form action="register.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input required="" name="password" class="form-control" type="password" class="input-medium">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control phone" required>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="reset" class="btn btn-primary">Reset Fields</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <? require_once("scripts.php"); ?>

    <script type="text/javascript">
        $(".phone").mask("(999) 999-9999");
    </script>

</body>

</html>
