<?php
	global $page;
	global $snippets;
	$posts = Post::post_by_category_id(1);
?>
<div id="column_left">
	<?php
		echo "<h1>".$page->title."</h1>";
		echo $page->body;
	?>
	<audio id="media_player" class="clearfix" style="display:none; clear:both;">
		Media Players
	</audio>
	<div class="clear">		
	<?php
	 	if(!empty($posts)):
	 	foreach ($posts as $post):
		$post_snippet = $post->snippets();
	?>
	<div class="sermon_block">
		<div class="thumbnail">
			<a class="sermon_button" rel="<?php echo $post_snippet['audio']; ?>" >
				<img src="<?php echo $post_snippet['thumbnail'] ?>"/>
				<span class="listen_icon"><img src="<?php echo BASE_URL ?>/images/gfx_listen_overlay.png"></span>
		 	</a>
		</div>
		<h2>
			<?php echo $post->title;?>
			<?php if (isset($post_snippet['notes']) && !empty($post_meta['notes'])):?>
				<a href="<?php echo $post_snippet['notes'] ?>"><img src="<?php echo BASE_URL ?>/images/icn_pdf.png" alt="Download Sermon Notes" target="_blank"></a>
			<?php endif; ?>
		</h2> 
		<p class="speaker"><?php echo $post_snippet['speaker'] ?></p>
	</div>
	<?php endforeach; ?>
	</div>	
	<?php
		else:
	?>
		<h2>There are no sermons.</h2>
	<?php
		endif;
	?>
</div>
<?php include_layout_template('column_right.php'); ?>
