<?php 
ini_set('display_errors', 1);
require_once("../admin/dbfunc.php");
$blogdb = new BlogFunctions;
$posts = $blogdb->getRecentPosts(2,3);
foreach ($posts as $post) {
	echo $post['title'];
}
 ?>