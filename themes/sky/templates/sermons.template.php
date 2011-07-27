<?php
	global $page;
    $limit = isset( $_GET['limit']) ? $_GET['limit'] : ITEMS_PER_PAGE;
	$group = isset( $_GET['group']) ? $_GET['group'] : 1;

	$total = Post::find_by_category_title( 'Sermon', NULL, NULL, NULL, true );
	$pagination = new Pagination($group, $limit, $total);
	$offset = $pagination->offset();
	$sermons = Post::find_by_category_title( 'Sermon', 'created DESC', $limit, $offset, false );
	$feature_sermon = Post::find_by_category_title('Sermon', 'created DESC', 1);
?>

<div id="column_left">
	<?php
		echo "<h1>".$page->title."</h1>";
		echo $page->body;
	?>
	<ul class="sermons">		
	<?php
	 	if(!empty($sermons)):
		$count = 0;
	 	foreach ($sermons as $sermon):
		$snippets = $sermon->snippets();
	?>
		<li class="sermon clearfix">
			<a href="#" class="sermon_play_button" data-id="<?php echo $count; ?>" title="<?php echo $sermon->title; ?>" rel="<?php echo $snippets['audio'] ?>">PLAY</a>  
			<div class="sermon_info">
				<h2><?php echo $sermon->title; ?></h2> 
				<p class="meta"><em>by </em><?php echo $snippets['speaker']; ?><em> on </em><?php echo simple_date($sermon->created, true);  ?>
					<?php if (isset($snippets['notes']) && !empty($snippets['notes'])):?>
						<a href="<?php echo $snippets['notes'] ?>"><img src="<?php echo image_directory('icn_pdf.png'); ?>" alt="Download Sermon Notes" target="_blank"></a>
					<?php endif; ?>
				</p>
			</div>
			<p id="audio_player_<?php echo $count; ?>" class="audio_container"></p>
		</li>
	<?php
		$count++;
		endforeach;
	?>
	</ul>	
	<?php
		else:
	?>
		<h2>There are no sermons.</h2>
	<?php
		endif;
	?>
	<nav>
	<?php $pagination->output_controls(); ?>
	</nav>
</div>
<div id="column_right">
<ul id="cta_buttons">
	<li class="get_connected"><?php echo Navigation::get_link('Contact', true); ?></li>
	<li class="read_blog"><?php echo Navigation::get_link('Blog', true) ?></li>
</ul>
<?php if(!empty($feature_sermon)): ?>
	<div class="box feature_sermon">
		<h3 class="box_head">This Weeks Sermon</h3>
		<div class="box_bottom">
				<?php $snippets = $feature_sermon[0]->snippets(); ?>
				<div class="img_container">
					<img src="<?php echo $snippets['thumbnail'] ?>" alt="" />	
				</div>
				<h2 style="line-height:19px"><?php echo $feature_sermon[0]->title; ?></h2> 
				<p class="meta"><em>by </em><?php echo $snippets['speaker']; ?>
					<?php if (isset($snippets['notes']) && !empty($snippets['notes'])):?>
						<a href="<?php echo $snippets['notes'] ?>"><img src="<?php echo image_directory('icn_pdf.png'); ?>" alt="Download Sermon Notes" target="_blank"></a>
					<?php endif; ?>
				</p>
				<audio id="feature_audio" src="<?php echo $snippets['audio'] ?>" controls ></audio>
		</div>
	</div>
<?php endif; ?>
</div>

