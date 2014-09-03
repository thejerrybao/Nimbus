		<head> 
    <?php session_start(); ?>
		<title>Bootstrap 3</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="/ProjectSeuss/css/bootstrap.css" media="all">
        <link rel="stylesheet" type="text/css" href="/admin/font-awesome-4.1.0/css/font-awesome.css" media="all">
		</head>
	<body style="background-image: url('/ProjectSeuss/images/graphics/books.jpg');background-size: 50%; background-repeat: repeat;">
		      <img src="/ProjectSeuss/images/graphics/banner.jpg" style="width: 100%; height: 100%;">
				
		<div class = "navbar navbar-inverse navbar-static-top">
			<div class= "container" > 
				<div class = "navbar-header">
					<a href = "/ProjectSeuss/index.php" class = "navbar-brand"> UCBCKI</a>
						<button class = "navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
							<span class="icon-bar"></span>
        					<span class="icon-bar"></span>
        					<span class="icon-bar"></span>
						</button>
					</div>
					<div class = "collapse navbar-collapse navHeaderCollapse">
						<ul class = "nav navbar-nav navbar-right">
							<li><a href="/ProjectSeuss/index.php">Home</a></li>
							<li><a href="/ProjectSeuss/Calendar/Calendar.php">Calendar</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">About</a>
								<ul class="dropdown-menu">
									<li><a href="#">UCB CKI</a></li>
									<li><a href="http://cnhcirclek.org/index.php?option=com_content&view=article&id=132:golden-gate-division" target="_blank">Golden Gate Division</a></li>
									<li><a href="http://cnhcirclek.org/" target="_blank">CNH Circle K</a></li>
									<li><a href="http://www.circlek.org/home.aspx" target="_blank">Circle K International</a></li>
									<li><a href="http://www.kiwanis.org/">Kiwanis International</a></li>						
								</ul>
							</li>
							<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Service</a>
								
								<ul class="dropdown-menu">
									<li><a href="../ProjectSeuss/Service/contproj.php">Continuous Projects Spotlight</a></li>
									<li><a href="../ProjectSeuss/Service/singleserv.php">Single Service Spotlight</a></li>
									<li><a href="http://www.cnhcirclek.org/about/district-service-initiative">District Service Initiative</a></li>
									<li><a href="http://www.cnhcirclek.org/about/district-fundraising-initiatives">District Fundraising Initiatives</a></li>												
								</ul>

							</li>
							<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Leadership</a>
								<ul class="dropdown-menu">
									<li><a href="/ProjectSeuss/Leadership/board.php">Board</a></li>						
								</ul>

							</li>
							<li><a href="/ProjectSeuss/index.php">Fellowship</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Committees</a>
								<ul class="dropdown-menu">
									<li><a href="/ProjectSeuss/committees/fund.php">Fundraising</a></li>
									<li><a href="/ProjectSeuss/committees/hist.php">Historian</a></li>
									<li><a href="/ProjectSeuss/committees/kfam.php">Kiwanis Family</a></li>
									<li><a href="/ProjectSeuss/committees/mde.php">MD&E</a></li>
									<li><a href="/ProjectSeuss/committees/proj.php">Projects</a></li>
									<li><a href="/ProjectSeuss/committees/pr.php">Public Relations</a></li>
									<li><a href="/ProjectSeuss/committees/publ.php">Publications</a></li>
									<li><a href="/ProjectSeuss/committees/sserve.php">Single Service</a></li>
									<li><a href="/ProjectSeuss/committees/spirit.php">Spirit & Social</a></li>
									<li><a href="/ProjectSeuss/committees/tech.php">Technology</a></li>								
								</ul>
							</li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Social Media</a>
                <ul class="dropdown-menu">
                  <li><a href="https://www.facebook.com/groups/66850859271/"><i class="fa fa-facebook fa-fw"></i> Facebook Group</a></li>
                  <li><a href="http://instagram.com/ucbcki"><i class="fa fa-instagram fa-fw"></i> Instagram</a></li>
                  <li><a href="https://twitter.com/UCBCKI"><i class="fa fa-facebook fa-fw"></i> Twitter</a></li>
                  <li><a href="https://drive.google.com/a/berkeley.edu/folderview?id=0B1MfH83HOZRMWDNpUlhLUVBhVTQ&usp=drive_web#"><i class="fa fa-picture-o"></i> Image Gallery</a></li>
                </ul>
              </li>
                <?php              
                  if (isset($_SESSION['nimbus_user_id'])) {
                ?> <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $_SESSION['nimbus_first_name'] ?> <?= $_SESSION['nimbus_last_name'] ?> <i class="fa fa-user fa-fw"></i></a>
                <ul class="dropdown-menu">
                  <?php              
                  if ($_SESSION['nimbus_access'] > 0) {
                  ?>
                  <li><a href="../admin">Admin</a></li>
                  <? } ?>
                  <li><a href="#">My MRP</a></li>
                  <li><a href="/ProjectSeuss/logout.php?url=<? echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>" > Logout</a></li>
                </ul>
                </li>
							<? } else { ?>
                     <li><a class="btn" data-toggle="modal" href="#myModal" ><i class="fa fa-user fa-fw"></i> Login</a></li>
                     <?}?>
						</ul>	
					</div>

			</div>

		</div>
		
<div class="modal fade bs-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <br>
        <div class="bs-example bs-example-tabs">
            <ul id="myTab" class="nav nav-tabs">

              <li class="active"><a href="#signin" data-toggle="tab">Sign In</a></li>
              <li class=""><a href="#signup" data-toggle="tab">Register</a></li>
              <li class=""><a href="#help" data-toggle="tab">Need help?</a></li>
            </ul>
        </div>
      <div class="modal-body">
        <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in" id="help">
        <p></p>
        <p></p><br> Please contact <a mailto:href="thejerrybao@gmail.com"></a>thejerrybao@gmail.com</a> for any other inquiries.</p>
        </div>
        <div class="tab-pane fade active in" id="signin">
            <form action="/ProjectSeuss/login.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="form_submit_type" value="login">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" name="username" autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password">
                            </div>
                            <input type="hidden" name="url" value="<? echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>">
                            <button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>
            </form>
        </div>
        <div class="tab-pane fade" id="signup">
             <form action="../admin/processdata.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="form_submit_type" value="create_account">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input required="" name="password" class="form-control" type="password" placeholder="********" class="input-medium">
                                    </div>

                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <button type="reset" class="btn btn-primary">Reset Fields</button>
                                </form>
                  </div>
    </div>
	</div>
    </div>
    </div>
    </div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="../js/bootstrap.js"></script>
		</body>
