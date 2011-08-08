<?php 
	global $page;
	$nav = new Navigation();
	if(!empty($page)){
		$snippets = $page->snippets();
	}
?>

<div id="column_right">
	<ul id="cta_buttons">
		<li class="get_connected"><?php echo Navigation::get_link('Contact', true); ?></li>
		<li class="our_events"><?php echo Navigation::get_link('Events', true); ?></li>
		<li class="read_blog"><?php echo Navigation::get_link('Blog', true) ?></li>
	</ul>
	<nav class="subnav">
		<?php if(!empty($page)) $nav->subnav(); ?>
	</nav>
<?php
    if($page->title == 'Events'){
		include_widget('recent_events.php');
    }
?>
	<?php if(isset($snippets['bulletin'])) : ?>
		<h5 class="center"> <a href=" <?php echo $snippets['bulletin']; ?>" class="bulletin">Download the weekly bulletin</a></h5>
    <?php endif; ?>
</div>

