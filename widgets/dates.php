<?php
$year = date("Y");
$dates = Post::get_dates(NULL, $year, 'Blog');
$blog_link = Navigation::get_link('Blog');
if(!empty($dates)):
?>
<div class="box dates_widget">
	<h3 class="box_head">Posts in <?php echo $year ?></h3>
	<div class="box_bottom">
		<ul class="dates clearfix">
		<?php foreach ($dates as $date) : ?>
			<li><a href="<?php echo $blog_link.'&month='.$date['month'] ?>"><?php echo date( "F", mktime(0,0,0,$date['month']) ); ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
