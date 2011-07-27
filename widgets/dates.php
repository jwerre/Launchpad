<?php
$year = date("Y");
$dates = Post::get_dates(NULL, $year, 'Blog');
$blog_link = Navigation::get_link('Blog');
if(!empty($dates)):
?>
<div class="box">
	<h3 class="box_head">Blog Posts in <?php echo $year ?></h3>
	<div class="box_bottom">
		<ul class="dates clearfix">
		<?php foreach ($dates as $date) : ?>
			<li><a href="<?php echo append_query_string( array('month'=>$date['month']), $blog_link); ?>"><?php echo date( "F", mktime(0,0,0,$date['month']) ); ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
