
 <div class="col-sm-7 blog-main" style="background-color:rgba(255,255,255,0.98); border-radius: 25px; height=100%">
 		<?php ini_set('display_errors', 1);
			require_once("../admin/dbfunc.php");
			$blogdb = new BlogFunctions;
			$userdb = new UserFunctions;
			$posts = $blogdb->getRecentPosts($start,$numposts);
			foreach ($posts as $post) { ?>
         <div class="blog-post" >
            <h2 class="blog-post-title"><?= $post['title'];?></h2>
            <p class="blog-post-meta">Posted <?= date("F d, Y, h:i A",$post['publish_datetime']);?> by <a href="#"><? $author=$userdb->getUserInfo($post['author_id']); echo $author['first_name']; echo " "; echo $author['last_name']; ?></a></p>
            <p><?= $post['story'];?></p>
          </div><!-- /.blog-post -->
<? }?>

          <ul class="pager">
            <!-- <li><a href="#">Previous</a></li> -->
            <!-- <li><a href="#">Next</a></li> -->
          </ul>

</div>