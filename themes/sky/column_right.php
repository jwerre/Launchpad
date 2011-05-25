<?php 
global $page;
if($page->id != 1) :

$nav = new Navigation();

$posts = Post::find_last_created(3);

?>

<div id="column_right">
	<nav class="subnav">
		<ul>
			<li class="new_here"><a href="<?php echo BASE_URL ?>/page/18"><img src="<?php echo BASE_URL ?>/images/btn_new-here.png" width="300" height="54" alt="New Here"></a></a></li>
		</ul>
		<?php if(!empty($page)) $nav->subnav($page); ?>
	</nav>
	<?php include_layout_template('recent_blog_posts.php'); ?>
</div>

<?php endif; ?>