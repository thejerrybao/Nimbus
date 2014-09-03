<?php
/** Project Name: Nimbus (Circle K Club Management)
 ** Blog Administration (blog.php)
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
else if ($_SESSION['nimbus_access'] == 0) { echo "You don't have access to this page."; exit; }
date_default_timezone_set('America/Los_Angeles');
if (empty($_GET['month']) && empty($_GET['year']) && $_GET['view'] == "manage") {
    $location = 'Location: blog.php?view=manage&month=' . idate('m') . '&year=' . date('Y');
    header($location); 
    exit;
}
$months = ["", "January", "February", "March", "April", "May",
"June", "July", "August", "September", "October", "November", "December"];
require_once("dbfunc.php");
$userdb = new UserFunctions;
$blogdb = new BlogFunctions;

$page = "blog";
$pageTitle = "Blog Administration";
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
                case "create": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Create Post</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Create New Post</div>
                            <div class="panel-body">
                                <form action="processdata.php" method="post" enctype="multipart/form-data" id="create_post">
                                    <input type="hidden" name="form_submit_type" value="create_post">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                    <div class="form-group">        
                                        <label>Author</label>
                                        <select name="author_id" id="form-author" class="form-control" required>
                                            <? $users = $userdb->getUsers("active"); ?>
                                            <? foreach ($users as $user) { ?>
                                                <? if ($user['user_id'] == $_SESSION['cki_rf_user_id']) { ?>
                                                    <option value="<?= $user['user_id'] ?>" selected><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                                <? } else { ?>
                                                    <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                                <? } ?>
                                            <? } ?>
                                        </select>                                    </div>
                                    <div class="form-group">
                                        <label>Publish Date and Time</label>
                                        <input type="datetime-local" name="publish_datetime" id="publish-datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", time()); ?>" required>
                                    </div>
                
                                    <div class="form-group">
                                        <label>Story</label>
                                        <textarea name="story" form="create_post" rows="3" id="story" class="form-control" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Post</button>
                                    <button type="reset" class="btn btn-primary">Reset Fields</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">Help Panel</div>
                            <div class="panel-body">
                                <label>Title</label>
                                <p>Title of the story.</p>
                                <label>Author</label>
                                <p>Select the person who wrote this post.</p> 
                                <label>Publish</label>
                                <p>Date and time story was published</p>
                                <label>Story</label>
                                <p>Write the story in this field! If you would like to add a picture to this story please paste:"&lt;img src='link to image' &gt;" to the front of the story.</p>
                                
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "manage": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Blog Post List</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="blog.php" method="get" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="view" value="manage">
                                <select name="month" class="form-control" style="width: 20%; display: inline;">
                                    <? for ($i = 1; $i <= 12; $i++) { ?>
                                        <? if ($_GET['month'] == $i) { ?>
                                            <option value="<?= $i ?>" selected><?= $months[$i] ?></option>
                                        <? } else { ?>
                                            <option value="<?= $i ?>"><?= $months[$i] ?></option>
                                        <? } ?> 
                                    <? } ?>
                                </select>
                                <select name="year" class="form-control" style="width: 10%; display: inline;">
                                    <? for ($i = idate("Y"); $i >= 2006; $i--) { ?>
                                        <? if ($_GET['year'] == $i) { ?>
                                            <option value="<?= $i ?>" selected><?= $i ?></option>
                                        <? } else { ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <? } ?> 
                                    <? } ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-xs">Get Posts</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <? $posts = $blogdb->getPostsByMonth(mktime(0, 0, 0, $_GET['month'], 0, $_GET['year'])); ?>
                        <? if ($posts) { ?>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th id="title">Title</th>
                                    <th id="author">Author</th>
                                    <th id="publish-datetime">Publish Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <? foreach ($posts as $post) {
                                $author = $blogdb->getPostInfo($post['author_id']); 
                            ?>
                                <tr><td><a href="blog.php?view=post&id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a></td>
                                <td><?= $author['first_name'] ?> <?= $author['last_name'] ?></td>
                                <td><?= date("F j, Y, g:i a", $post['publish_datetime']) ?></td>
                                
                            <? } ?>
                            </tbody>
                        </table>
                        <? } else { ?>
                            <h2>No posts found for the specified month and year.</h2>
                        <? } ?>
                    </div>
                </div>
            <? break; ?>
            <? case "post": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Post Information <a href="blog.php?view=manage"><button class="btn btn-primary btn-back">Back to Manage Posts</button></a></h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No post ID specified.</h2> 
                <? } else { 
                    $post = $blogdb->getPostInfo($_GET['id']);   
                } ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">All Post Data</div>
                            <div class="panel-body">
                                <label>Title</label>
                                <p><?= $post['title'] ?></p>
                                <label>Author</label>
                                <p><? $author = $userdb->getUserInfo($post['author_id']) ?>
                                    <?= $author['first_name'] ?> <?= $author['last_name'] ?></p>
                                <label>Publish Date and Time</label>
                                <p><?= date("F j, Y, g:i a", $post['publish_datetime']); ?></p>
                                <label>Story</label>
                                <p><?= $post['story'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel panel-default">
                            <div class="panel-heading">Post Options</div>
                            <div class="panel-body">
                                <form action="blog.php" method="get" enctype="multipart/form-data" style="display: inline;">
                                    <input type="hidden" name="view" value="edit">
                                    <input type="hidden" name="id" value="<?= $post['post_id'] ?>">
                                    <div class="form-group" style="display: inline;">
                                        <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Edit Post</button>
                                    </div>
                                </form>
                                <form action="processdata.php" method="post" enctype="multipart/form-data" style="display: inline;">
                                    <input type="hidden" name="form_submit_type" value="delete_post">
                                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                    <div class="form-group" style="display: inline;">
                                        <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Delete Post</button>
                                    </div>
                                </form>
                        
                            </div>
                        </div>
                    </div>
                </div>
            <? break; ?>
            <? case "edit": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edit Post</h1>
                    </div>
                </div>
                <? if (isset($_COOKIE['successmsg'])) { ?><div class="alert alert-success"><i class="fa fa-check fa-fw"></i> <?= $_COOKIE['successmsg'] ?></div><? } ?>
                <? if (isset($_COOKIE['errormsg'])) { ?><div class="alert alert-danger"><i class="fa fa-ban fa-fw"></i> <?= $_COOKIE['errormsg'] ?></div><? } ?>
                <? if (empty($_GET['id'])) { ?>
                    <h2>No post ID specified.</h1>
                <? } else { 
                    $post = $blogdb->getPostInfo($_GET['id']);
                } ?>
                <div class="row">
                        <div class="col-lg-8">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Edit Current Post</div>
                                <div class="panel-body">
                                    <form action="processdata.php" method="post" enctype="multipart/form-data" id="edit_post">
                                        <input type="hidden" name="form_submit_type" value="edit_post">
                                        <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control" value="<?= $post['title']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Author</label>
                                            <select name="author_id" class="form-control" id="form-author" required>
                                                <? $users = $userdb->getUsers("active"); ?>
                                                <? foreach ($users as $user) { ?>
                                                    <? if ($post['author_id'] == $user['user_id']) { ?>
                                                        <option value="<?= $user['user_id'] ?>" selected><?= $user['first_name'] ?> <?= $user['last_name'] ?></option> 
                                                    <? } else { ?>
                                                        <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>
                                                    <? } ?>
                                                <? } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Publish Date and Time</label>
                                            <input type="datetime-local" name="publish_datetime" class="form-control" value="<?= date("Y-m-d\TH:i:00", $post['publish_datetime']); ?>" required>
                                        </div>    
                                        <div class="form-group">
                                            <label>Story</label>
                                            <textarea name="story" class="form-control" form="edit_post" rows="3" required><?= $post['story']; ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Edit Post</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="panel-heading">Help Panel</div>
                            <div class="panel-body">
                                <label>Title</label>
                                <p>Title of the story.</p>
                                <label>Author</label>
                                <p>Select the person who wrote this post.</p> 
                                <label>Publish</label>
                                <p>Date and time story was published</p>
                                <label>Story</label>
                                <p>Write the story in this field! If you would like to add a picture to this story please paste:"&lt;img src='link to image' &gt;" to the front of the story.</p>
                            </div>  
                        </div>
                    </div>
                </div>
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

    <? require_once("scripts.php"); ?>

</body>
</html>