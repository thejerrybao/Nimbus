<?php?>
		<head> 
		<title>Bootstrap 3</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="/ProjectSeuss/css/bootstrap.css" media="all">
		</head>
	<body>
		<div class="jumbotron" align="middle">
  			<h1>UC Berkeley Circle K</h1>
  			<p>Oh the places you'll go with service!</p>
		</div>		
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
									<li><a href="http://cnhcirclek.org/index.php?option=com_content&view=article&id=132:golden-gate-division">Golden Gate Division</a></li>
									<li><a href="http://cnhcirclek.org/">CNH Circle K</a></li>
									<li><a href="http://www.circlek.org/home.aspx">Circle K International</a></li>
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
									<li><a href="../ProjectSeuss/Leadership/board.php">Board</a></li>						
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
							<li><a class="btn" data-toggle="modal" href="#myModal" >Login</a>
							
        					</li>
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
            <form id="login">
            <fieldset>
            <!-- Sign In Form -->
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="username">Username:</label>
              <div class="controls">
                <input required="" name="username" type="text" class="form-control" placeholder="Jerry Bao" class="input-medium" required="">
              </div>
            </div>

            <!-- Password input-->
            <div class="control-group">
              <label class="control-label" for="password">Password:</label>
              <div class="controls">
                <input required="" name="password" class="form-control" type="password" placeholder="********" class="input-medium">
              </div>
            </div>

            <!-- Button -->
            <div class="control-group">
              <label class="control-label" for="signin"></label>
              <div class="controls">
                <button id="signin" name="signin" class="btn btn-success">Sign In</button>
              </div>
            </div>
            </fieldset>
            </form>
        </div>
        <div class="tab-pane fade" id="signup">
            <form >
            <fieldset>
            <!-- Sign Up Form -->
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="Email">Email:</label>
              <div class="controls">
                <input id="Email" name="Email" class="form-control" type="text" placeholder="thejerrybao@.com" class="input-large" required="">
              </div>
            </div>
            
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="userid">Username:</label>
              <div class="controls">
                <input id="userid" name="userid" class="form-control" type="text" placeholder="Jerry Bao" class="input-large" required="">
              </div>
            </div>
            
            <!-- Password input-->
            <div class="control-group">
              <label class="control-label" for="password">Password:</label>
              <div class="controls">
                <input id="password" name="password" class="form-control" type="jerryspassword" placeholder="********" class="input-large" required="">
                <em>1-8 Characters</em>
              </div>
            </div>
            
            <!-- Text input-->
            <div class="control-group">
              <label class="control-label" for="reenterpassword">Re-Enter Password:</label>
              <div class="controls">
                <input id="reenterpassword" class="form-control" name="reenterpassword" type="password" placeholder="********" class="input-large" required="">
              </div>
            </div>
            
            <!-- Multiple Radios (inline) -->
            <br>

            <!-- Button -->
            <div class="control-group">
              <label class="control-label" for="confirmsignup"></label>
              <div class="controls">
                <button id="confirmsignup" name="confirmsignup" class="btn btn-success">Sign Up</button>
              </div>
            </div>
            </fieldset>
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
