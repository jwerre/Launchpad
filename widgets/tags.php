<?php
$tags = Tag::find_all_in_category('Blog');
$blog_link = Navigation::get_link('Blog');
if(!empty($tags)):
?>
<div class="box tags_widget">
	<h3 class="box_head">Tags</h3>
	<div class="box_bottom">
		<ul class="tags clearfix">
		<?php foreach ($tags as $tag) : ?>
			<li class="tag"><span><a href="<?php echo append_query_string( array('tag'=>$tag->id), $blog_link); ?>"><?php echo $tag->tag; ?></a></span></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>
