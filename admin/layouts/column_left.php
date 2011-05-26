<?php
    $pages = Page::find_last_created(5);
    $posts = Post::find_last_created(5);
    $media_files = Media::find_last_created(6);
?>
<aside>
    <?php if (!empty($pages)) : ?>
	<div class="recent_box">
		<h3>Recent Pages</h3>
		<ul>
            <?php foreach ($pages as $page) : ?>
                <li><a href="content_edit.php?id=<?php echo $page->id; ?>"><?php echo $page->title ?></a></li>
            <?php endforeach?>
		</ul>
	</div>
    <?php endif; ?>

    <?php if (!empty($posts)) : ?>
	<div class="recent_box">
		<h3>Recent Posts</h3>
		<ul>
            <?php foreach ($posts as $post) : ?>
                <li><a href="content_edit.php?id=<?php echo $post->id; ?>"><?php echo $post->title ?></a></li>
            <?php endforeach?>
		</ul>
	</div>
    <?php endif; ?>

	<!-- div class="recent_box">
		<h3>Recent Images</h3>
		<ul>
                <li><a href="media_files_edit.php?id=<?php echo $media->id; ?>"><img src="<?php echo $media->filename; ?>" alt="Name"/></a></li>
		</ul>
	</div -->
</aside>
