<?php
/** Project Name: Nimbus (Circle K Report Form System)
 ** Roster Administration (roster.php)
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
if (!isset($_SESSION['cki_rf_user_id'])) { header('Location: ../login.php'); }
else if ($_SESSION['cki_rf_access'] == 0) { echo "You don't have access to this page."; exit; }
require_once("dbfunc.php");
$userdb = new UserFunctions;
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Project Nimbus - Roster Administration</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- roster.php CSS -->
    <link href="css/roster.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <? $page = "roster"; 
            require_once("nav.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <? switch ($_GET['view']):
                case "add": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Member</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Add New Member</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_user">
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
                                        <label>Dues Paid?</label>
                                        <input type="hidden" name="dues_paid" value="0">
                                        <input type="checkbox" name="dues_paid" value="1">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Member</button>
                                    <button type="reset" class="btn btn-primary">Reset Fields</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Help Panel</div>
                            <div class="panel-body">
                            </div>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "list": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Members List</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <form id="search-roster-form">
                            <input type="text" name="search_words" class="form-control" id="search-words" style="width: 40%; display: inline;" />
                            <select name="search_category" class="form-control" id="search-category" style="width: 20%; display: inline;">
                                <option value="name" selected>Search By Name</option>
                                <option value="email">Search By Email</option>
                                <option value="phone">Search By Phone</option>
                            </select>
                        </form>
                        <div class="table-responsive">
                            <? $activeUser = $userdb->getUsers("active"); ?>
                            <? if ($activeUser) { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th id="user-name">Name</th>
                                        <th id="user-email">E-mail</th>
                                        <th id="user-phone">Phone</th>
                                        <th id="user-dues-paid">Dues Paid?</th>
                                        <th id="user-email-confirmed">Email Confirmed?</th>
                                    </tr>
                                </thead>
                                <tbody id="search-roster-result">
                                    <? foreach ($activeUser as $user) { ?>
                                        <? $user['dues_paid'] = $user['dues_paid'] ? "Yes" : "No"; ?>
                                        <? $user['email_confirmed'] = $user['email_confirmed'] ? "Yes" : "No"; ?>
                                        <tr><td><a href="roster.php?view=user&id=<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></a></td>
                                        <td><?= $user['email'] ?></td>
                                        <td><?= $user['phone'] ?></td>
                                        <td><?= $user['dues_paid'] ?></td>
                                        <td><?= $user['email_confirmed'] ?></td>
                                    <? } ?>
                                </tbody>
                            </table>
                            <? } else { ?>
                                <h2>No active memebrs found.</h2>
                            <? } ?>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "user": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Member Information</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No member ID specified.</h2>
                <? } else { 
                    $user = $userdb->getUserInfo($_GET['id'], true);
                    $user['dues_paid'] = $user['dues_paid'] ? "Yes" : "No";
                    $user['email_confirmed'] = $user['email_confirmed'] ? "Yes" : "No"; 
                    switch ($user['access']) {
                        case "0":
                            $user['access'] = 'General Member';
                            break;
                        case "1":
                            $user['access'] = 'Board Member';
                            break;
                        case "2":
                            $user['access'] = 'Secretary';
                            break;
                        case "3":  
                            $user['access'] = 'Technology Chair/Administrator';
                            break;
                        default:
                            $user['access'] = "Access Value Invalid";
                    }
                } ?>
                <? if ($user) { ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">MRP Information</div>
                            <div class="panel-body">
                                <label>Service Hours:</label>
                                <p style="display: inline; margin-right: 50px;"><?= $user['hours']['service_hours'] ?></p>
                                <label>Admin Hours:</label>
                                <p style="display: inline; margin-right: 50px;"><?= $user['hours']['admin_hours'] ?></p>
                                <label>Social Hours:</label>
                                <p style="display: inline; margin-right: 50px;"><?= $user['hours']['social_hours'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">All Member Data</div>
                            <div class="panel-body">
                                <label>Name</label>
                                <p><?= $user['first_name'] ?> <?= $user['last_name'] ?></p>
                                <label>E-mail</label>
                                <p><?= $user['email'] ?></p>
                                <label>Phone</label>
                                <p><?= $user['phone'] ?></p>
                                <label>Dues Paid?</label>
                                <p><?= $user['dues_paid'] ?></p>
                                <label>Email Confirmed?</label>
                                <p><?= $user['email_confirmed'] ?></p>
                                <label>Access Level</label>
                                <p><?= $user['access'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <? } else { ?>
                    <h2>Member ID not found.</h2>
                <? } ?>
            <? break; ?>
            <? case "dues": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Manage Dues Paid Members</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['action'])) { ?>
                    <h2>No action specified.</h2>
                <? } else { ?>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="roster.php" method="get" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="view" value="dues">
                                <select name="action" class="form-control" style="width: 20%; display: inline;">
                                    <option value="set" <? if ($_GET['action'] == "set") { ?> selected <? } ?>>Set Dues Paid Members</option>
                                    <option value="unset" <? if ($_GET['action'] == "unset") { ?> selected <? } ?>>Unset Dues Paid Members</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-xs">Select Action</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                        <? if ($_GET['action'] == "set") { 
                            $nonDuesPaidMembers = $userdb->getUsers("non_dues_paid"); ?>
                            <div class="panel-heading">Set Dues Paid Members</div>
                            <div class="panel-body">
                                <h4>Select members below to set as dues paid.</h4>
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="set_dues_paid">
                                    <div class="form-group">
                                    <? if ($nonDuesPaidMembers) { ?>
                                        <? $i = 0; ?>
                                        <table>
                                        <? foreach ($nonDuesPaidMembers as $nonDuesPaidMember) { ?>
                                            <? if ($i % 4 == 0) { ?>
                                                <tr>
                                            <? } ?>
                                            <td class="checkbox-series-name"><?= $nonDuesPaidMember['first_name'] ?> <?= $nonDuesPaidMember['last_name'] ?></td>
                                            <td class="checkbox-series-checkbox"><input type="checkbox" name="non_dues_paid[]" value="<?= $nonDuesPaidMember['user_id'] ?>" class="checkbox_series"></td>
                                            <? if ($i % 4 == 3) { ?>
                                                </tr>
                                            <? } ?>
                                            <? $i++; ?>
                                        <? } ?>
                                        </table>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Set Members as Dues Paid</button>
                                    <? } else { ?>
                                        <h2>No Non-Dues Paid members exist.</h2>
                                    <? } ?>
                                    </div>
                                </form>
                            </div>
                        <? } else if ($_GET['action'] == "unset") {
                            $duesPaidMembers = $userdb->getUsers("dues_paid"); ?>
                            <div class="panel-heading">Unset Dues Paid Members</div>
                            <div class="panel-body">
                                <h4>Select members below to unset as dues paid.</h4>
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="unset_dues_paid">
                                    <div class="form-group">    
                                    <? if ($duesPaidMembers) {
                                        $i = 0; ?>
                                        <table>
                                        <? foreach ($nonDuesPaidMembers as $nonDuesPaidMember) { ?>
                                            <? if ($i % 4 == 0) { ?>
                                                <tr>
                                            <? } ?>
                                            <td class="checkbox-series-name"><?= $duesPaidMember['first_name'] ?> <?= $duesPaidMember['last_name'] ?></td>
                                            <td class="checkbox-series-checkbox"><input type="checkbox" name="dues_paid[]" value="<?= $duesPaidMember['user_id'] ?>"></td>
                                            <? if ($i % 4 == 3) { ?>
                                                </tr>
                                            <? } ?>
                                            <? $i++; ?>
                                        <? } ?>
                                        </table>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Unset Members as Dues Paid</button>
                                    <? } else { ?>
                                        <h2>No Dues Paid members exist.</h2>
                                    <? } ?>
                                    </div>
                                </form>
                            </div>
                        <? } else { ?>
                            <h2>Incorrect action specified.</h2>
                        <? } ?>
                        </div>
                    </div>
                </div>
                <? } ?>
            <? break; ?>
            <? case "status": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Manage Members Status</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['action'])) { ?>
                    <h2>No action specified.</h2>
                <? } else { ?>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="roster.php" method="get" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="view" value="status">
                                <select name="action" class="form-control" style="width: 20%; display: inline;">
                                    <option value="activate" <? if ($_GET['action'] == "activate") { ?> selected <? } ?>>Activate Members</option>
                                    <option value="deactivate" <? if ($_GET['action'] == "deactivate") { ?> selected <? } ?>>Deactivate Members</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-xs">Select Action</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                        <? if ($_GET['action'] == "activate") { 
                            $nonActiveMembers = $userdb->getUsers("non_active"); ?>
                            <div class="panel-heading">Activate Members</div>
                            <div class="panel-body">
                                <h4>Select members below to activate.</h4>
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="activate_users">
                                    <div class="form-group">
                                    <? if ($nonActiveMembers) { ?>
                                        <? $i = 0; ?>
                                        <table>
                                        <? foreach ($nonActiveMembers as $nonActiveMember) {  ?>
                                            <? if ($i % 4 == 0) { ?>
                                                <tr>
                                            <? } ?>
                                            <td class="checkbox-series-name"><?= $nonActiveMember['first_name'] ?> <?= $nonActiveMember['last_name'] ?></td>
                                            <td class="checkbox-series-checkbox"><input type="checkbox" name="non_active_users[]" value="<?= $nonActiveMember['user_id'] ?>" class="checkbox_series"></td>
                                            <? if ($i % 4 == 3) { ?>
                                            </tr>
                                            <? }
                                            $i++; ?>
                                        <? } ?>
                                        </table>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Activate Members</button>
                                    <? } else { ?>
                                        <h2>No active members exist.</h2>
                                    <? } ?>
                                    </div>
                                </form>
                            </div>
                        <? } else if ($_GET['action'] == "deactivate") {
                            $activeMembers = $userdb->getUsers("active"); ?>
                            <div class="panel-heading">Deactivate Members</div>
                            <div class="panel-body">
                                <h4>Select members below to deactivate.</h4>
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="deactivate_users">
                                    <div class="form-group">    
                                    <? if ($activeMembers) { ?>
                                        <? $i = 0; ?>
                                        <table>
                                        <? foreach ($activeMembers as $activeMember) { ?>
                                            <? if ($i % 4 == 0) { ?>
                                                <tr>
                                            <? } ?>
                                            <td class="checkbox-series-name"><?= $activeMember['first_name'] ?> <?= $activeMember['last_name'] ?></td>
                                            <td class="checkbox-series-checkbox"><input type="checkbox" name="active_users[]" value="<?= $activeMember['user_id'] ?>"></td>
                                            <? if ($i % 4 == 3) { ?>
                                            </tr>
                                            <? }
                                            $i++; ?>
                                        <? } ?>
                                        </table>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Deactivate Members</button>
                                    <? } else { ?>
                                        <h2>No non-active members exist.</h2>
                                    <? } ?>
                                    </div>
                                </form>
                            </div>
                        <? } else { ?>
                            <h2>Incorrect action specified.</h2>
                        <? } ?>
                        </div>
                    </div>
                </div>
                <? } ?>
            <? break; ?>
            <? default: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>No view was selected.</h1>
                    </div>
                </div>
            <? endswitch; ?>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    <!-- roster.php JS -->
    <script src="js/roster.js"></script>
</body>
</html>
?>