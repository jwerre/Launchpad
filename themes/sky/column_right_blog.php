<div id="column_right">
	<nav class="subnav">
		<ul>
			<li class="new_here"><a href="<?php echo BASE_URL ?>/page/18"><img src="<?php echo BASE_URL ?>/images/btn_new-here.png" width="300" height="54" alt="New Here"></a></a></li>
		</ul>
		<?php 
			$nav = new Navigation();
			$nav->subnav($page);
		?>
	</nav>
	<div class="box topics">
		<h3 class="box_head">Topics</h3>
		<div class="box_bottom">
			<?php Navigation::categories(array(3)); ?>
		</div>
	</div>
	<?php include_layout_template('recent_blog_posts.php'); ?>
</div>
