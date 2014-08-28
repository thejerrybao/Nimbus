<?php 
/** Project Name: Nimbus (Circle K Club Management)
 ** Tag Administration (tags.php)
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

$page = "tags";
$pageTitle = "Tags Administration";
$customCSS = true;
$customJS = true;
?>

<!DOCTYPE html>
<html lang="en">

<? require_once("header.php"); ?>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <? require_once("nav.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <? switch ($_GET['view']):
                case "add": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Tags</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Add New Tag</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_committee">
                                    <div class="form-group">
                                        <label>Tag Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Tag</button>
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
                        <h1 class="page-header">Manage Tags</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <? $tags = $tagdb->getTags(); ?>
                            <? if ($ctags) { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th id="tag-name">Tag Name</th>
                                        <th id="tag-abbr">Abbreviation</th>
                                        <th id="mrp-tag ">Abbreviation</th>
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
                                    </tr>
                                </thead>
                                <tbody>
                                <? $committeeEmails = "" ?>
                                <? foreach ($committee['members'] as $committeeMember) { ?>
                                    <? $committeeEmails .= $committeeMember['email'] . "; " ?>
                                    <tr>
                                        <td><?= $committeeMember['first_name'] ?> <?= $committeeMember['last_name'] ?></td>
                                        <td><?= $committeeMember['email'] ?></td>
                                    </tr>
                                <? } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Committee E-mails</div>
                            <div class="panel-body">
                                <label>Committee Member Emails</label>
                                <textarea rows="3" class="form-control" style="margin-bottom: 20px;"><?= $committeeEmails ?></textarea>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">Add Committee Members</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_committee_members">
                                    <input type="hidden" name="committee_id" value="<?= $committee['committee_id'] ?>">
                                    <label>Select Members to Add</label>
                                    <select name="user_ids[]" class="form-control" id="form-add-committee-members" multiple required>
                                        <? $users = $userdb->getUsers("active"); ?>
                                        <? $committeeMemberIDs = $committeedb->getCommitteeMembers($_GET['id']); ?>
                                        <? foreach ($users as $user) { ?>
                                            <? if (!in_array($user['user_id'], $committeeMemberIDs)) { ?>
                                                <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                            <? } ?>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add Members</button>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">Delete Committee Members</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="delete_committee_members">
                                    <input type="hidden" name="committee_id" value="<?= $committee['committee_id'] ?>">
                                    <label>Select Members to Delete</label>
                                    <select name="user_ids[]" class="form-control" id="form-delete-committee-members" multiple required>
                                        <? foreach ($committee['members'] as $committeeMember) { ?>
                                            <option value="<?= $committeeMember['user_id'] ?>"><?= $committeeMember['first_name'] ?> <?= $committeeMember['last_name'] ?></option>
                                        <? } ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Delete Members</button>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Committee Options</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="delete_committee">
                                    <input type="hidden" name="committee_id" value="<?= $committee['committee_id'] ?>">
                                    <button type="submit" class="btn btn-primary">Delete Committee</button>
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

    <? require_once("scripts.php") ?>

</body>
</html>
?>
?>