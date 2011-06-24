<?php
	global $page;
	$blog_posts = Post::posts_by_category_title('Blog');
?>
<div id="column_left">
	<?php
		echo "<h1>".$page->title."</h1>";
		echo $page->body;
		
	 	if(!empty($blog_posts)):
	 	foreach ($blog_posts as $post):
	?>
			<div class="blog_tease">
				<h2><?php echo $post->title ?></h2>
				<p class="snippet"><em>by </em><?php echo $post->author()->full_name(); ?><em> on </em><?php echo date('F jS, Y', strtotime($post->created));  ?></p>
				<p> <?php // echo $post->excerpt()?> </p>
				<div class="blog_tease_foot">
					<a class="read_more" href="<?php echo $post->get_link(); ?>">Read More</a>
				</div>
			</div>
	<?php
		endforeach;
		else:
	?>
		<h2>There are no blog posts</h2>
	<?php
		endif;
	?>
</div>
<?php include_layout('column_right.php'); ?>
