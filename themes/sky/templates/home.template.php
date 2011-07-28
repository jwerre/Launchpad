<?php
    $events = Post::find_by_category_title('Feature');
    $posts = Post::find_by_category_title('Blog');
?>
<?php if( !empty($events) ) :?>
<div id="slider">
	<div class="container">		
		<div class="slides" >
			<?php foreach ($events as $event) : 
				$snippets = $event->snippets();
			?>
			<div>
				<span class="slide_image">
					<img src="<?php echo $snippets['image'] ?>" width="495" height="259" alt="<?php echo $event->title; ?>">
				</span>
				<span class="slide_copy">
					<h2><?php echo $event->title; ?></h2>
                    <p>
                        <?php $excerpt = $event->excerpt(500); echo $excerpt; ?>
                        <?php if ( strlen($event->body) > strlen($excerpt) ): ?>
                         <a href="<?php echo $event->get_link(); ?>">more...</a>
                        <?php endif; ?>
                    </p>
				</span>
			</div>			
			<?php endforeach; ?>
		</div>
	</div>
	<a href="#" class="previous"></a>
	<a href="#" class="next"></a>
</div>
<?php endif; ?>
<nav>		
	<ul id="cta_buttons">
		<li class="get_connected"><?php echo Navigation::get_link('Contact', true); ?></li>
		<li class="read_blog"><?php echo Navigation::get_link('Blog', true) ?></li>
	</ul>
</nav>
<br class="clear">
<?php if(!empty($posts) ) : ?>
<div class="recent_posts clearfix">
    <h1>Blog</h1>
    <?php
        if( isset($posts[0]) ) :
        $author1 = $posts[0]->author();
    ?>
    <div class="blog_tease" style="margin-right:40px;">
        <h2><?php echo $posts[0]->title ?></h2>
        <p class="meta"><em>by </em><?php echo $author1->full_name(); ?><em> on </em><?php echo simple_date($posts[0]->created, true);  ?></p>
        <?php echo $posts[0]->excerpt()?>
        <div class="blog_tease_foot">
            <a class="read_more" href="<?php echo $posts[0]->get_link(); ?>">Read More</a>
        </div>
    </div>        
    <?php endif; ?>
    <?php
        if( isset($posts[1]) ) :
        $author1 = $posts[1]->author();
    ?>
    <div class="blog_tease">
        <h2><?php echo $posts[1]->title ?></h2>
        <p class="meta"><em>by </em><?php echo $author1->full_name(); ?><em> on </em><?php echo simple_date($posts[1]->created, true);  ?></p>
        <?php echo $posts[1]->excerpt()?>
        <div class="blog_tease_foot">
            <a class="read_more" href="<?php echo $posts[1]->get_link(); ?>">Read More</a>
        </div>
    </div>        
    <?php endif; ?>
</div>
<?php endif; ?>
<?php 

		// echo '<pre>'; print_r($posts); echo '</pre>'; exit;
?>
