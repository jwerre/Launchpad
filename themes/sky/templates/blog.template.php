<?php
	global $page;
	$posts = Post::posts_except_in_categories( array(2), NULL,  "created DESC");
?>
<div id="column_left">
	<?php
		echo "<h1>".$page->title."</h1>";
		echo $page->body;
		
	 	if(!empty($posts)):
	 	foreach ($posts as $post):
			$author = $post->author();
	?>
			<div class="blog_tease">
				<h2><?php echo $post->title ?></h2>
				<p class="snippet"><em>by </em><?php echo $author->full_name(); ?><em> on </em><?php echo date('F jS, Y', strtotime($post->created));  ?></p>
				<?php echo $post->excerpt()?>
				<div class="blog_tease_foot">
					<a class="read_more" href="<?php echo BASE_URL.'/post/'.$post->id ?>">Read More</a>
				</div>g
			</div>
	<?php
		endforeach;
		else:
	?>
		<h2>There are no sermons</h2>
	<?php
		endif;
	?>
</div>
<?php include_layout_template('column_right_blog.php'); ?>
