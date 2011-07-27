<?php
	global $category;
	$posts = Post::post_by_category_id($category->id);
?>
<div id="column_left">
	<?php
		echo "<h1>".$category->title."</h1>";
		echo $category->body;
		
	 	if(!empty($posts)):
	 	foreach ($posts as $post):
			$author = $post->author();
	?>
	<div class="blog_tease">
		<h2><?php echo $post->title ?></h2>
		<p class="meta"><em>by </em><?php echo $author->full_name(); ?><em> on </em><?php echo date('F jS, Y', strtotime($post->created));  ?></p>
		<?php echo $post->excerpt()?>
		<div class="blog_tease_foot">
			<a class="read_more" href=<?phpBASE_URL?>"/post/<?php echo $post->id ?>">Read More</a>
		</div>
	</div>
	<?php
		endforeach;
		else:
	?>
		<h2>There are no posts of that category</h2>
	<?php
		endif;
	?>
</div>
<?php include_layout_template('column_right_blog.php'); ?>
