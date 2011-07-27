<?php
	global $page;
	$pagination;
    $limit = isset( $_GET['limit']) ? $_GET['limit'] : ITEMS_PER_PAGE;
	$group = isset( $_GET['group']) ? $_GET['group'] : 1;

	if(isset($_GET['tag'])){
		$total = Post::find_by_tag( $_GET['tag'], NULL, NULL, NULL, true);
		$pagination = new Pagination($group, $limit, $total);
		$offset = $pagination->offset();
		$posts = Post::find_by_tag( $_GET['tag'], 'created DESC', $limit, $offset, false);
	}elseif(isset($_GET['month'])){
		$total = Post::find_by_dates($_GET['month'], NULL, 2011, 'Blog', NULL, NULL, NULL, true);
		$pagination = new Pagination($group, $limit, $total);
		$offset = $pagination->offset();
		$posts = Post::find_by_dates($_GET['month'], NULL, 2011, 'Blog', 'created DESC', $limit, $offset, false);
	}else{
		$total = Post::find_by_category_title( 'Blog', NULL, NULL, NULL, true );
		$pagination = new Pagination($group, $limit, $total);
		$offset = $pagination->offset();
		$posts = Post::find_by_category_title( 'Blog', 'created DESC', $limit, $offset, false );
	}
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
				<p class="meta"><em>by </em><?php echo $author->full_name(); ?><em> on </em><?php echo date('F jS, Y', strtotime($post->created));  ?></p>
				<?php echo $post->excerpt()?>
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
	<nav>
	<?php $pagination->output_controls(); ?>
	</nav>
</div>
<?php include_layout('column_blog.php'); ?>
