<div class="box">
	<h3 class="box_head">Recent Posts</h3>
	<div class="box_bottom">
		<ul>
		<?php 
	    $posts = Post::find_by_category_title("Blog", 'created DESC', 5);
		if(!empty($posts)){
			foreach ($posts as $post) {
                $post_snippets = $post->snippets();
				echo '<li><h3><a href="'. $post->get_link().'">'.$post->title.'</a></h3>';
				if(isset($post_snippets['date'])){
					echo '<p class="author"><strong>'.$post_snippets['date'].'</strong></p></li>';
				}
			}
		}
		?>
		</ul>
	</div>
</div>