	
<?php
	global $post;
	global $snippets;
	$author = $post->author();
	?>
	
	<div id="column_left">
		<h1 class="<?php echo $post->slug ?>"><?php echo $post->title ?></h1>
		<p class="snippet"><em>by </em><?php echo $author->full_name(); ?><em> on </em><?php echo date('F jS, Y', strtotime($post->created));  ?></p>
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_tweet"></a>
		<a class="addthis_counter addthis_pill_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4d3287217a767c12"></script>
		<!-- AddThis Button END -->
		<?php
		if (!empty($post->body)){
			echo $post->body;
		}
?>
	</div>
	<?php   include_layout("column_right.php"); ?>
