<?php
	global $page;
	$events = Post::find_by_category_title( 'Event' );
?>
<div id="column_left">
	<?php
		echo "<h1>".$page->title."</h1>";
		echo $page->body;
	?>
	<div id="event_calendar"></div>
</div>
<?php include_layout('column_right.php'); ?>
