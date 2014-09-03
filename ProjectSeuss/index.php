<!DOCTYPE html>
<html>
	<head> 
		<title>Bootstrap 3</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	 <?php
    include("../ProjectSeuss/head.php")
    ?>
  </head>
	<body>
    
    <div id="jumbotron" align="middle">
		<div id="myCarousel" class="carousel slide" >
      <div class="carousel-inner">
        <div class="item active">
          <img src="Images/banner_ICON.jpg" alt="" width = 100%>
          <div class="container">
          </div>
        </div>
        <div class="item">
          <img src="Images/banner_ICON.jpg" alt="" width = 100% >
   
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div><!-- /.carousel -->
    </div>
		<div class="container">

      <div class="row" >
        <div class="col-sm-1"></div>
        <?php
        $start = 0;
        $numposts = 3;
    include("../ProjectSeuss/blogview.php");
    ?>
        

        <div class="col-sm-3 col-sm-offset-1 blog-sidebar" style="background-color:rgba(255,255,255,0.98); border-radius: 25px;">
          <div class="sidebar-module">
            <h4>Today's Events</h4>
            

          </div>
          <div class="sidebar-module sidebar-module-inset">
            <h4>Follow us on instagram @UCBCKI</h4>
          </div>
          <div class="sidebar-module">
			<iframe src="http://widget.websta.me/in/ucbcki/?s=250&w=1&h=8&b=1&p=5" allowtransparency="true" frameborder="0" scrolling="no" style="border:none;overflow:hidden;width:330px; height: 1320px" ></iframe> <!-- websta - web.stagram.com -->
          </div>
        </div><!-- /.blog-sidebar -->

      </div><!-- /.row -->

    </div><!-- /.container -->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="/ProjectSeuss/js/bootstrap.js"></script>
	</body>
    </html>