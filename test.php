
<?php 
include 'lib/initialize.php';

// $posts = Post::posts_by_dates(2, NULL, 2011);
// $post_dates = Post::get_dates( NULL, 2011, array("Blog", "Event")) ;
$auth = Post::get_authors();
echo '<pre>' . print_r($auth, true) . '</pre>'; exit;

?>

