<?php global $page; ?>
<div id="column_left">
	<div class="journey_wheel">&nbsp;</div>
	<div class="journey_copy">
		<h1><?php echo $page->title ?></h1>
		<?php echo $page->body ?>
	</div>
</div>
<?php
	include_layout("column_right.php");
?>



