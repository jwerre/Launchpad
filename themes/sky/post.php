	
<?php
	global $post;
	$snippets = $post->snippets();
	$author = $post->author();
	?>
	<div id="column_left">
	<h1 class="<?php echo $post->slug ?>" > <?php echo $post->title; ?></h1> 
	<?php if ( isset($snippets['start']) ): ?>
	<p class="meta"><?php echo simple_date( $snippets['start'], true ); ?>
		<?php if( isset($snippets['end']) ): ?>
			<em>to</em> <?php echo simple_date( $snippets['end'], true ); ?> 
	</p>	
	<?php endif; ?>

	<?php else: ?>
	<p class="meta"><em>by </em><?php echo $author->full_name(); ?><em> on </em><?php echo simple_date($post->created, true);  ?></p>
	<?php endif; ?>
	<p class="">
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_preferred_3"></a>
			<a class="addthis_button_preferred_4"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e0cda386942154d"></script>
		<!-- AddThis Button END -->
	</p>
	<?php if (!empty($post->body)){ echo $post->body; } ?>
	</div>
<?php include_layout('column_blog.php'); ?>
