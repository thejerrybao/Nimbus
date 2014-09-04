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
if (!isset($_SESSION['nimbus_user_id'])) { header('Location: login.php'); }
else if ($_SESSION['nimbus_access'] < 2) { echo "You don't have access to this page."; exit; }
require_once("dbfunc.php");
$tagdb = new TagFunctions;
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
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Add New Tag</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_tag">
                                    <div class="form-group">
                                        <label>Tag Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tag Abbr</label>
                                        <input type="text" name="abbr" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>MRP Tag?</label>
                                        <input type="hidden" name="mrp_tag" value="0">
                                        <input type="checkbox" name="mrp_tag" value="1" id="mrptag_checked">
                                    </div>
                                    <div class="mrp-options">
                                        <div class="form-group">
                                            <label>Auto-Manage Tag?</label>
                                            <input type="hidden" name="auto_manage" value="0">
                                            <input type="checkbox" name="auto_manage" value="1" checked>
                                        </div>
                                        <div class="form-group">
                                            <label>Number?</label>
                                            <input type="hidden" name="number" value="0">
                                            <input type="checkbox" name="number" value="1" checked>
                                        </div>
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
                                <label>Tag Name</label>
                                <p>Name of the Tag</p>
                                <label>Tag Abbr</label>
                                <p>Abbreviation of the tag</p>
                                <label>MRP Tag?</label>
                                <p>Is the tag an MRP Tag?</p>
                                <label>Auto-Manage tag</label>
                                <p>If it is an MRP tag, is it managed automatically?</p>
                                <label>Number?</label>
                                <p>Is there a minimum number that must be completed?</p>
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
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <? $tags = $tagdb->getTags('all'); ?>
                            <? if ($tags) { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th id="tag-name">Tag Name</th>
                                        <th id="tag-abbr">Abbreviation</th>
                                        <th id="tag-mrp">MRP Tag?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <? foreach ($tags as $tag) { ?>
                                    <tr>
                                        <td><?= $tag['name'] ?></td>
                                        <td><?= $tag['abbr'] ?></td>
                                        <td>
                                            <? if ($tag['mrp_tag']) { ?>
                                                <i class="fa fa-check fa-fw"></i>
                                            <? } else { ?>
                                                <i class="fa fa-times fa-fw"></i>
                                            <? } ?>
                                        </td>
                                    </tr>
                                <? } ?>
                                </tbody>
                            </table>
                            <? } else { ?>
                                <h2>No tags found.</h2>
                            <? } ?>
                            <div class="panel panel-info">
                                <div class="panel-heading">Delete Tags</div>
                                <div class="panel-body">
                                    <form action="processdata.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="form_submit_type" value="delete_tag">
                                        <select name="tag_ids[]" class="form-control" id="form-delete-tags" multiple required>
                                            <? $tags = $tagdb->getTags('all'); ?>
                                            <? foreach ($tags as $tag) { ?>
                                                <option value="<?= $tag['tag_id'] ?>"><?= $tag['abbr'] ?> (<?= $tag['name'] ?>)</option>
                                            <? } ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Delete Tags</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "mrpadd": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add MRP Level</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Add New MRP</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="add_mrp">
                                    <div class="form-group">
                                        <label>MRP Level</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Hours</label>
                                        <input type="number" name="hours" class="form-control" min='0' required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Level</button>
                                    <button type="reset" class="btn btn-primary">Reset Fields</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Help Panel</div>
                            <div class="panel-body">
                                 <label>MRP Level</label>
                                <p>Name of the MRP Level</p>
                                <label>Hours</label>
                                <p>How many Service Hours are needed</p>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "mrplist": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Manage MRP Levels</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <? $levels = $tagdb->getMRPLevels(); ?>
                            <? if ($levels) { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>   
                                        <th id="level-name">Level</th>
                                        <th id="level-hours">Hours Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? foreach ($levels as $level) { ?>
                                        <tr>
                                            <td><a href="tags.php?view=level&id=<?= $level['level_id'] ?>"><?= $level['name'] ?></a></td>
                                            <td><?= $level['hours'] ?></td>
                                        </tr>
                                    <? } ?>
                                </tbody>
                            </table>
                        <? } ?>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "level": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">MRP Level Information <a href="tags.php?view=mrplist"><button class="btn btn-primary btn-back">Back to List of Levels</button></a></h1>
                    </div>
                </div>
            <? break; ?>
            <? default: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1>No view was selected.</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No event ID specified.</h2>
                <? } else { $mrpLevelRequirements = $tagsdb->getOverrideHours($_GET['id']); } ?>
            <? endswitch; ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <? require_once("scripts.php") ?>

</body>
</html>
