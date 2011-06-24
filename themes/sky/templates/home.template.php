<?php 
    global $page;
	$slides = Post::posts_by_category_title('Slide');
?>
<div id="slider">
	<div class="container">		
		<div class="slides" >
			<?php foreach ($slides as $slide) : 
				$snippet = $slide->snippets();
			?>
			<div>
				<span class="slide_image">
					<img src="<?php echo $snippet['image'] ?>" width="495" height="259" alt="<?php echo $slide->title; ?>">
				</span>
				<span class="slide_copy">
					<h2><?php echo $slide->title; ?></h2>
					<p><?php echo $slide->excerpt(); ?> <a href="<?php echo $slide->get_link(); ?>">more...</a></p>
				</span>
			</div>			
			<?php endforeach; ?>
		</div>
	</div>
	<a href="#" class="previous"></a>
	<a href="#" class="next"></a>
</div>
<div id="column_left">
<?php
	if (!empty($page->title)){
		echo '<h1 class="'.$page->slug.'">'.$page->title.'</h1>';
	}
	if (!empty($page->body)){
		echo $page->body;
	}
?>
</div>
