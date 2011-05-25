	
<?php
	global $post;
	global $snippets;
	$author = $post->author();
	?>
	
	<div id="column_left">
	<?php 
	if (!empty($post->title)){
			echo '<h1 class="'.$post->slug.'">'.$post->title.'</h1>';
	}
	if ( isset($snippets['event_date']) ):
	?>
	<p class="snippet"><em>Starts on </em><?php echo date('F jS, Y', strtotime($snippets['event_date']));  ?></p>	
	<?php else: ?>
	<p class="snippet"><em>by </em><?php echo $author->full_name(); ?><em> on </em><?php echo date('F jS, Y', strtotime($post->created));  ?></p>
	<?php endif; ?>
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
	if (!empty($snippets['audio']) ) :
	?>
	<a href="<?php echo BASE_URL ?>js/audio-player/player.swf?soundfile=<?php echo $snippets['audio']?>&titles=<?php echo $post->title ?>&artists=<?php echo $snippets['speaker'] ?>&autostart=yes" rel="shadowbox;width=350;height=24;title=<?php echo $post->title ?>" >listen</a>	
	<?php
	endif;
	echo '</div>';
	include_layout_template("column_right.php");
?>

<script type="text/javascript" src="js/shadowbox/shadowbox.js"></script>
<script type="text/javascript">
	Shadowbox.init({
		initialWidth:350,
		initialHeight:24
	});
</script>

