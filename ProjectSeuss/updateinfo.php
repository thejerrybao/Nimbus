<!DOCTYPE html>
<html>
<head>
	<?php include('head.php'); 
		session_start();
		ini_set('display_errors', 1);
        include_once('admin/dbfunc.php');
        $userdb = new UserFunctions;
         ?>
<meta charset='utf-8' />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link href="jquery-ui/jquery-ui.css" rel="stylesheet">
<script src='lib/moment.min.js'></script>
<script src='lib/jquery-ui.custom.min.js'></script>
<script src="jquery-ui/jquery-ui.js"></script>
</head>
<body>
<div style="background-color:rgba(255,255,255,0.98); border-radius: 25px;">
<h1 style="text-indent: 50px;" >  Welcome <?= $_SESSION['nimbus_first_name']?></h1>
<form action="accountupdates.php" method="post" enctype="multipart/form-data">
<div class="form-group">
<p style="text-indent: 50px;">Please enter current password to validate your changes.</p>
    <label>Current Password</label>
    <input required="" name="current_password" class="form-control" type="password" class="input-medium">
                                  <? $userData = $userdb->getUserInfo($_SESSION['nimbus_user_id']); ?>
                                  <p style="text-indent: 50px;">Please only fill out fields that you would like to change.</p>
                                  <div class="form-group">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input name="password" class="form-control" type="password" class="input-medium">
                                    </div>
                                     <label>Email Address</label>
                                        <input type="email" name="email" value="<?= $userData['email'] ?>" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" value="<?= $userData['phone'] ?>" class="form-control phone" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-primary">Reset Fields</button>
                                </form>
                  </div>

</div>
<script src="js/maskedinput.jquery.min.js"></script>
<script src="js/head.js"></script>
</body>
</html>
