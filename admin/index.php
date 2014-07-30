<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Administration Home (index.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Project Nimbus - Administration</title>

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
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Project Nimbus</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="logout.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search for event...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li class="active"><a class="active" href="index.html">Dashboard</a></li>
                        <li>
                            <a href="#">Event Management<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="events.php?view=create">Create Event</a></li>
                                <li>
                                    <a href="#">Manage Events <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li><a href="events.php?view=list">List View</a></li>
                                        <li><a href="events.php?view=calendar">Calendar View</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Club Roster<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="roster.php">Add Member</a></li>
                                <li><a href="roster.php">Delete/Deactivate Members</a></li>
                                <li><a href="roster.php">Manage Members</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Club Committees<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="committees.php">Add Committee</a></li>
                                <li><a href="committees.php">Delete/Deactivate Committees</a></li>
                                <li><a href="committees.php">Manage Committees</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">MRP Management<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">MRP Tags<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li><a href="mrp.php">Add Tag</a></li>
                                        <li><a href="mrp.php">Delete/Deactivate Tags</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">MRP Levels<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li><a href="mrp.php">Add Level</a></li>
                                        <li><a href="mrp.php">Delete/Deactivate Levels</a></li>
                                        <li><a href="mrp.php">Manage Levels</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Manage MRP Requirements</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Administrative Tasks<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="admin.php">Manage Board Members</a></li>
                                <li><a href="admin.php">Change Member Information</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Blank</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
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
