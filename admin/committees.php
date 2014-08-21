<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Committee Administration (committees.php)
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
$committeedb = new CommitteeFunctions;
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

    <title>Project Nimbus - Committee Administration</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- committees.php CSS -->
    <link href="css/committees.css" rel="stylesheet" type="text/css">

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
        <? $page = "committees"; 
            require_once("nav.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <? switch ($_GET['view']):
                case "add": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Committee</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Add New Committee</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_committee">
                                    <div class="form-group">
                                        <label>Committee Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Committee</button>
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
                        <h1 class="page-header">Manage Committees</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <? $committees = $committeedb->getCommittees(); ?>
                            <? if ($committees) { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th id="committee-name">Committee Name</th>
                                        <th id="committee-num-members"># Members</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <? foreach ($committees as $committee) { ?>
                                    <tr>
                                        <td><a href="committees.php?view=committee&id=<?= $committee['committee_id'] ?>"><?= $committee['name'] ?></td>
                                        <td><?= count($committee['members']) ?></td>
                                    </tr>
                                <? } ?>
                                </tbody>
                            </table>
                            <? } else { ?>
                                <h2>No committees found.</h2>
                            <? } ?>
                        </div>
                    </dib>
                </div>
            <? break; ?>
            <? case "committee": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Committee Information</h1>
                    </div>
                </div>
<<<<<<< HEAD
=======
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
>>>>>>> FETCH_HEAD
                <? if (empty($_GET['id'])) { ?>
                    <h2>No committee ID specified.</h2>
                <? } else { $committee = $committeedb->getCommittee($_GET['id']); } ?>
                <? if ($committee) { ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Committee Members</div>
                            <div class="panel-body">
                                <label>Committee Name:</label> <?= $committee['name'] ?><br />
                                <label># Committee Members:</label> <?= count($committee['members']) ?>
                                <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th id="committee-member-name">Name</th>
                                        <th id="committee-member-email">E-mail</th>
                                        <th id="committee-member-delete">Delete?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <? $committeeEmails = "" ?>
                                <? foreach ($committee['members'] as $committeeMember) { ?>
                                    <? $committeeEmails .= $committeeMember['email'] . "; " ?>
                                    <tr>
                                        <td><?= $committeeMember['first_name'] ?> <?= $committeeMember['last_name'] ?></td>
                                        <td><?= $committeeMember['email'] ?></td>
                                        <td>
                                            <form action="processdata.php" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="form_submit_type" value="delete_committee_user">
                                                <input type="hidden" name="committee_id" value="<?= $committee['committee_id'] ?>">
                                                <input type="hidden" name="user_id" value="<?= $committeeMember['user_id'] ?>">
                                                <button type="submit" class="btn btn-primary btn-xs">Delete Member</button>
                                            </form>
                                        </td>
                                    </tr>
                                <? } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Committee Management</div>
                            <div class="panel-body">
                                <label>Committee Member Emails</label>
                                <textarea rows="3" class="form-control" style="margin-bottom: 20px;"><?= $committeeEmails ?></textarea>
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_committee_user">
                                    <input type="hidden" name="committee_id" value="<?= $committee['committee_id'] ?>">
                                    <label>Select Member to Add</label>
                                    <select name="user_id" class="form-control" required>
                                        <? $users = $userdb->getUsers("active"); ?>
                                        <? foreach ($users as $user) { ?>
                                            <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add Member</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <? } else { ?>
                    <h2>Committee ID not found.</h2>
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

</body>
</html>
?>