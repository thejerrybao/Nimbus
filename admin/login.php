<?php 
/** Project Name: Nimbus (Circle K Club Management)
 ** Login (login.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/
ini_set('display_errors', 1);
session_start();
require_once("dbfunc.php");
$userdb = new UserFunctions;
if (isset($_SESSION['nimbus_user_id'])) {
    header("Location: index.php");
}

$page = "login";
$pageTitle = "Administration Login";
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
                        <h3 class="panel-title">Administration Login</h3>
                    </div>
                    <div class="panel-body">
                        <form action="processdata.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="form_submit_type" value="login">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" name="username" autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password">
                            </div>
                            <button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <? require_once("scripts.php"); ?>

</body>

</html>
