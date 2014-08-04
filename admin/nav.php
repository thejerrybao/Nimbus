<?php 
/** Project Name: Nimbus (Circle K Report Form System)
 ** Administration Navigation (nav.php)
 **
 ** Author: Jerry Bao (jbao@berkeley.edu)
 ** Author: Robert Rodriguez (rob.rodriguez@berkeley.edu)
 ** Author: Diyar Aniwar (diyaraniwar@berkeley.edu)
 ** 
 ** CIRCLE K INTERNATIONAL
 ** COPYRIGHT 2014-2015 - ALL RIGHTS RESERVED
 **/

$valid_event_manage_views = ["list", "calendar", "event", "edit"];
$valid_roster_manage_views = ["list", "dues", "member"]
?>
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
                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
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
                <li <?php if ($page == "index") { ?> class="active" <?php } ?>><a href="index.php">Dashboard</a></li>
                <li <?php if ($page == "events") { ?> class="active" <?php } ?>>
                    <a href="#">Event Management<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a <?php if ($page == "events" && $_GET["view"] == "create") { ?> class="active" <?php } ?> href="events.php?view=create">Create Event</a></li>
                        <li <?php if ($page == "events" && (in_array($_GET["view"], $valid_event_manage_views))) { ?> class="active" <?php } ?> >
                            <a href="#">Manage Events <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li><a <?php if ($page == "events" && $_GET["view"] == "list") { ?> class="active" <?php } ?> href="events.php?view=list">List View</a></li>
                                <li><a <?php if ($page == "events" && $_GET["view"] == "calendar") { ?> class="active" <?php } ?> href="events.php?view=calendar">Calendar View</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li <?php if ($page == "roster") { ?> class="active" <?php } ?>>
                    <a href="#">Club Roster<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a <?php if ($page == "roster" && $_GET["view"] == "add") { ?> class="active" <?php } ?> href="roster.php?view=add">Add Member</a></li>
                        <li <?php if ($page == "roster" && (in_array($_GET["view"], $valid_roster_manage_views))) { ?> class="active" <?php } ?> >
                            <a href="#">Manage Members <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li><a <?php if ($page == "roster" && $_GET["view"] == "list") { ?> class="active" <?php } ?> href="roster.php?view=list">List View</a></li>
                                <li><a <?php if ($page == "roster" && $_GET["view"] == "dues") { ?> class="active" <?php } ?> href="roster.php?view=dues">Set Dues Paid Members</a></li>
                            </ul>
                        </li>
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
