<?php
$tags = Tag::find_all_in_category('Blog');
if(!empty($tags)):
?>
<div class="box">
	<h3 class="box_head">Tags</h3>
	<div class="box_bottom">
		<ul class="tags clearfix">
		<?php foreach ($tags as $tag) : ?>
			<li><a href="<?php echo append_query_string( array('tag'=>$tag->id) ) ?>"><?php echo $tag->tag ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
