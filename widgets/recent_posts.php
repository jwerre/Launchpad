<div class="widget recent_posts_widget">
	<h3 class="widget_head">Recent Posts</h3>
	<div class="widget_bottom">
		<ul>
		<?php 
		global $post;
		$cat_type = (isset($post->category_id)) ? $post->category_name()  : "Blog";

	    $posts = Post::find_by_category_title($cat_type, 'created DESC', 5);
		if(!empty($posts)){
			foreach ($posts as $p) {
                $post_snippets = $p->snippets();
				echo '<li><a href="'. $p->get_link().'">'.$p->title.'</a>';
				if(isset($post_snippets['date'])){
					echo '<p class="author"><strong>'.$post_snippets['date'].'</strong></p></li>';
				}
			}
		}
		?>
		</ul>
	</div>
</div>
