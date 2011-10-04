<?php
$tags = Tag::find_all_in_category('Blog');
$blog_link = Navigation::get_link('Blog');
if(!empty($tags)):
?>
<div class="widget tags_widget">
	<h3 class="widget_head">Tags</h3>
	<div class="widget_bottom">
		<ul class="tags clearfix">
		<?php foreach ($tags as $tag) : ?>
			<li class="tag"><span><a href="<?php echo append_query_string( array('tag'=>$tag->id), $blog_link); ?>"><?php echo $tag->tag; ?></a></span></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
