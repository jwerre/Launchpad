<?php
    $pages = Page::find_last_created(5);
    $posts = Post::find_last_created(5);
    $media_files = Media::find_last_created(6);
?>
<aside>
	<div class="recent_box">
		<h3>Recent Pages</h3>
        <?php if (!empty($pages)) : ?>
		<ul>
            <?php foreach ($pages as $page) : ?>
                <li><a href="content_edit.php?id=<?php echo $page->id; ?>"><?php echo $page->title ?></a></li>
            <?php endforeach?>
		</ul>
        <?php endif; ?>
	</div>
	<div class="recent_box">
		<h3>Recent Posts</h3>
        <?php if (!empty($posts)) : ?>
		<ul>
            <?php foreach ($posts as $post) : ?>
                <li><a href="content_edit.php?id=<?php echo $post->id; ?>"><?php echo $post->title ?></a></li>
            <?php endforeach?>
		</ul>
        <?php endif; ?>
	</div>
	<!-- div class="recent_box">
		<h3>Recent Images</h3>
		<ul>
                <li><a href="media_files_edit.php?id=<?php echo $media->id; ?>"><img src="<?php echo $media->filename; ?>" alt="Name"/></a></li>
		</ul>
	</div -->
</aside>
