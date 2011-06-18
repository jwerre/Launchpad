<div class="box recent_posts">
	<h3 class="box_head">Recent Blog Posts</h3>
	<div class="box_bottom">
		<ul>
		<?php 
		$recent_posts = Post::find_last_created(3);
	
		if(!empty($recent_posts)){
			foreach ($recent_posts as $post) {
				$href = (CLEAN_URLS) ? BASE_URL.'/post/'.$post->id : BASE_URL.'index.php?post='.$post->id;
				echo '<li><h3><a href="'.$href.'">'.$post->title.'</a></h3>';
				echo '<p class="author"><strong>'.$post->author()->full_name().'</strong> <em>'.date('F jS, Y', strtotime($post->created)).'</em></p></li>';
			}
		}
		?>
		</ul>
	</div>
</div>