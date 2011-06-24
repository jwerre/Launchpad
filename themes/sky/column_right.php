<div id="column_right">
	<nav class="subnav">
		<?php 
			global $page;
			$nav = new Navigation();
			$nav->subnav($page);
		?>
	</nav>
	<div class="box recent_posts">
		<h3 class="box_head">Recent Blog Posts</h3>
		<div class="box_bottom">
			<ul>
			<?php 
			$recent_posts = Post::posts_by_category_title('Blog', 'created DESC', 5);
			if(!empty($recent_posts)){
				foreach ($recent_posts as $post) {
					echo '<li><h3><a href="'.$post->get_link().'">'.$post->title.'</a></h3>';
					echo '<p class="author"><strong>'.$post->author()->full_name().'</strong> <em>'.date('F jS, Y', strtotime($post->created)).'</em></p></li>';
				}
			}
			?>
			</ul>
		</div>
	</div>
</div>
