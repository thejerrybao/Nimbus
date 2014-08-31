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
                            <? $tags = $tagdb->getTags('all', 1); ?>
                            <? if ($tags) { ?>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th id="tag-name">Tag Name</th>
                                        <th id="tag-abbr">Abbreviation</th>
                                        <th id="mrp-tag ">MRP Tag?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <? foreach ($tags as $tag) { ?>
                                    <tr>
                                        <td><?= $tag['name'] ?></td>
                                        <td><?= $tag['abbr'] ?></td>
                                        <td><?= $tag['number'] ?></td>
                                    </tr>
                                <? } ?>
                                </tbody>
                            </table>
                            <? } else { ?>
                                <h2>No tags found.</h2>
                            <? } ?>
                            <div classs="panel panel-info">
                                <div class="panel-heading">Deactivate Tags</div>
                                <div class="panel-body">
                                    <form action="processdata.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="form_submit_type" value="deactivate_tag">
                                        <input type="hidden" name="tag_id" value="<?= $tag['tag_id'] ?>">
                                        <select name="tag_ids[]" class="form-control" id="form-delete-tag" multiple required>
                                            <? $tags = $tagdb->getTags('all'); ?>
                                            <?foreach ($tags as $tag) { ?>
                                                <? if(!in_array($tags['tag_id', $))
                                                <option value="<?= $tag ?>"><?= $tag ?> </option>
                                            <? } ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Deactivate Tag</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </dib>
                    <!--is this supposed to say dib?-->
                </div>
            <? break; ?>

            <? case "mrpadd": ?>
            <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add MRP Level</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Add New MRP</div>
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
                <? endswitch; ?>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <? require_once("scripts.php") ?>

</body>
</html>
