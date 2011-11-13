<?php
	header("Content-Type: application/xml; charset=ISO-8859-1");
	include("lib/initialize.php");
	$rss = new RSS();
	echo $rss->get_posts_by_category( array('blog', 'work') );
?>
