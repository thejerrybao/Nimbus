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
$valid_roster_manage_views = ["list", "dues", "member", "status"]
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
                <li <? if ($page == "index") { ?> class="active" <? } ?>><a href="index.php">Dashboard</a></li>
                <li <? if ($page == "events") { ?> class="active" <? } ?>>
                    <a href="#">Event Management<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a <? if ($page == "events" && $_GET["view"] == "create") { ?> class="active" <? } ?> href="events.php?view=create">Create Event</a></li>
                        <li <? if ($page == "events" && (in_array($_GET["view"], $valid_event_manage_views))) { ?> class="active" <? } ?> >
                            <a href="#">Manage Events <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li><a <? if ($page == "events" && $_GET["view"] == "list") { ?> class="active" <? } ?> href="events.php?view=list">Events List</a></li>
                                <li><a <? if ($page == "events" && $_GET["view"] == "calendar") { ?> class="active" <? } ?> href="events.php?view=calendar">Events Calendar</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li <? if ($page == "roster") { ?> class="active" <? } ?>>
                    <a href="#">Club Roster<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a <? if ($page == "roster" && $_GET["view"] == "add") { ?> class="active" <? } ?> href="roster.php?view=add">Add Member</a></li>
                        <li <? if ($page == "roster" && (in_array($_GET["view"], $valid_roster_manage_views))) { ?> class="active" <? } ?> >
                            <a href="#">Manage Members <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li><a <? if ($page == "roster" && $_GET["view"] == "list") { ?> class="active" <? } ?> href="roster.php?view=list">Members List</a></li>
                                <li><a <? if ($page == "roster" && $_GET["view"] == "dues") { ?> class="active" <? } ?> href="roster.php?view=dues&action=set">Manage Dues Paid</a></li>
                                <li><a <? if ($page == "roster" && $_GET["view"] == "status") { ?> class="active" <? } ?> href="roster.php?view=status&action=deactivate">Manage Members Status</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li <? if ($page == "committees") { ?> class="active" <? } ?>>
                    <a href="#">Club Committees<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a <? if ($page == "committees" && $_GET["view"] == "add") { ?> class="active" <? } ?> href="committees.php?view=add">Add Committee</a></li>
                        <li><a <? if ($page == "committees" && $_GET["view"] == "list") { ?> class="active" <? } ?> href="committees.php?view=list">Manage Committees</a></li>
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
                <li <? if ($page == "admin") { ?> class="active" <? } ?>>
                    <a href="#">Administrative Tasks<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a <? if ($page == "admin" && $_GET["view"] == "boardmembers") { ?> class="active" <? } ?> href="admin.php?view=boardmembers">Manage Board Members</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>
