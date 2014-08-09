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
require_once("dbfunc.php");
$db = new DatabaseFunctions;
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
        <?php $page = "committees"; 
            require_once("nav.php"); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <?php switch ($_GET["view"]):
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
            <?php break; ?>
            <?php case "delete": ?>
            <?php break; ?>
            <?php case "list": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Members List</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                        <?php $activeMembers = $db->getMembers("active"); ?>
                        <?php if ($activeMembers) { ?>
                        <table class="table table-striped table-hover events-table">
                            <thead>
                                <tr>
                                    <th id="member-name">Name</th>
                                    <th id="member-email">E-mail</th>
                                    <th id="member-phone">Phone</th>
                                    <th id="member-dues-paid">Dues Paid?</th>
                                    <th id="member-email-confirmed">Email Confirmed?</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($activeMembers as $member) {
                                    $member["dues_paid"] = $member["dues_paid"] ? "Yes" : "No";
                                    $member["email_confirmed"] = $member["email_confirmed"] ? "Yes" : "No";
                                    echo "<tr><td><a href=\"roster.php?view=member&id=" . $member["user_id"] . "\">" 
                                    . $member["first_name"] . " " . $member["last_name"] . "</a></td>";
                                    echo "<td>" . $member["email"] . "</td>";
                                    echo "<td>" . $member["phone"] . "</td>";
                                    echo "<td>" . $member["dues_paid"] . "</td>";
                                    echo "<td>" . $member["email_confirmed"] . "</td>";
                                } ?>
                            </tbody>
                        </table>
                        <?php } else { ?>
                        <h2>No active memebrs found.</h2>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            <?php break; ?>
            <?php case "committee": ?>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Member Information</h1>
                    </div>
                </div>
                <?php if (empty($_GET["id"])) { echo "<h2>No member ID specified.</h2>"; }
                    else { 
                        $member = $db->getUserInfo($_GET["id"], true);
                        $member["dues_paid"] = $member["dues_paid"] ? "Yes" : "No";
                        $member["email_confirmed"] = $member["email_confirmed"] ? "Yes" : "No"; 
                        switch ($member["access"]) {
                            case "0":
                                $member["access"] = 'General Member';
                                break;
                            case "1":
                                $member["access"] = 'Board Member';
                                break;
                            case "2":
                                $member["access"] = 'Secretary';
                                break;
                            case "3":  
                                $member["access"] = 'Technology Chair/Administrator';
                                break;
                            default:
                                $member["access"] = "Access Value Invalid";
                        } }
                    if ($member) { ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">MRP Information</div>
                            <div class="panel-body">
                                <label>Service Hours:</label>
                                <p style="display: inline; margin-right: 50px;"><?= $member["hours"]["service_hours"] ?></p>
                                <label>Admin Hours:</label>
                                <p style="display: inline; margin-right: 50px;"><?= $member["hours"]["admin_hours"] ?></p>
                                <label>Social Hours:</label>
                                <p style="display: inline; margin-right: 50px;"><?= $member["hours"]["social_hours"] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">All Member Data</div>
                            <div class="panel-body">
                                <label>Name</label>
                                <p><?= $member["first_name"] ?> <?= $member["last_name"] ?></p>
                                <label>E-mail</label>
                                <p><?= $member["email"] ?></p>
                                <label>Phone</label>
                                <p><?= $member["phone"] ?></p>
                                <label>Dues Paid?</label>
                                <p><?= $member["dues_paid"] ?></p>
                                <label>Email Confirmed?</label>
                                <p><?= $member["email_confirmed"] ?></p>
                                <label>Access Level</label>
                                <p><?= $member["access"] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } else { echo "<h2>Member ID not found.</h2>"; } ?>
            <?php break; ?>
            <?php endswitch; ?>
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