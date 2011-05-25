<?php include 'header.php';?>
		<div class="grid2 align_right">
			<div class="font_small">8 september 2010</div>
			<h3>Finibus Bonorum et Mal</h3>
			<p class="padding_no_bottom">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium.</p>
			<a href="#">View album</a>
			
			<div class="font_small padding_top">10 april 2009</div>
			<h3>Finibus Bonorum et Mal</h3>
			<p class="padding_no_bottom">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium.</p>
			<a href="#">View album</a>
			
			<div class="font_small padding_top">3 june 2008</div>
			<h3>Finibus Bonorum et Mal</h3>
			<p class="padding_no_bottom">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium.</p>
			<a href="#">View album</a>
			
		</div>
	</div>
	
    <header class="grid3 no_side_margin">
        <h1 class="logo">Frank Yamamoto</h1>
	</header>
	
	<div class="grid3 no_side_margin">
		<div class="ruler">
			<hr />
			<h2>Photo Gallery</h2>
		</div>
		<p class="italic">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
		<h3>Bonorum et Malorum</h3>
		<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
		<ul class="gallery">
                <?php getGalleryPhotos(3, 120, 90) ?>
		</ul>
	</div>
    <?php include 'footer.php';?>
