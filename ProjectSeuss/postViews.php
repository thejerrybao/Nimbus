<?php 
	ini_set('display_errors', 1);
          define('__ROOT__', dirname(dirname(__FILE__)));
          require_once(__ROOT__.'/~circlek/admin/dbfunc.php'); 
          $blogdb = new BlogFunctions;
          $numPosts = $_GET['numPosts'];
          $posts = $blogdb->getRecentPosts($numPosts);
          foreach ($posts as $post) {
          	echo $post['title'];
          }
          
?>