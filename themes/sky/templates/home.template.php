<?php
	$connect = Page::find_by_title("Connect");
	$grow = Page::find_by_title("Grow");
	$serve = Page::find_by_title("Serve");
	$share = Page::find_by_title("Share");
	$worship = Page::find_by_title("Worship");
	
	$events = Post::post_by_category_id(2, 5);
?>
<div id="feature">
	<ul>
		<li class="get_connected"><a href="<?php echo BASE_URL ?>/page/18"></a></li>
		<li class="listen_to_sermon"><a href="<?php echo BASE_URL ?>/page/5"></a></li>
	</ul>
	<div id="steeple">&nbsp;</div>
</div>
<ul class="worship_wheel">
	<li class="connect">
		<a href="<?php echo BASE_URL."/page/".$connect->id ?>"><?php echo $connect->title; ?></a>
		<div class="tooltip" style="bottom: 150px; display:none">
			<div class="tt_point">&nbsp;</div>
			<h2><?php echo $connect->title; ?></h2>
			<p><?php echo $connect->excerpt(); ?></p>
		</div>
	</li>
	<li class="worship">
		<a href="<?php echo BASE_URL."/page/".$worship->id ?>"><?php echo $worshp->title; ?></a>
		<div class="tooltip" style="bottom: 150px; display: none;"  >
			<div class="tt_point">&nbsp;</div>
			<h2><?php echo $worship->title; ?></h2>
			<p><?php echo $worship->excerpt(); ?></p>
		</div>
	</li>
	<li class="grow">
		<a href="<?php echo BASE_URL."/page/".$grow->id ?>"><?php echo $grow->title; ?></a>
		<div class="tooltip" style="bottom: 150px; display:none">
			<div class="tt_point">&nbsp;</div>
			<h2><?php echo $grow->title; ?></h2>
			<p><?php echo $grow->excerpt(); ?></p>
		</div>
	</li>
	<li class="worship">
		<a href="<?php echo BASE_URL."/page/".$worship->id ?>"><?php echo $worshp->title; ?></a>
		<div class="tooltip" style="bottom: 150px; display: none;"  >
			<div class="tt_point">&nbsp;</div>
			<h2><?php echo $worship->title; ?></h2>
			<p><?php echo $worship->excerpt(); ?></p>
		</div>
	</li>
	<li class="serve">
		<a href="<?php echo BASE_URL."/page/".$serve->id ?>"><?php echo $serve->title; ?></a>
		<div class="tooltip" style="bottom: 150px; display:none">
			<div class="tt_point">&nbsp;</div>
			<h2><?php echo $serve->title; ?></h2>
			<p><?php echo $serve->excerpt(); ?></p>
		</div>
	</li>
	<li class="worship">
		<a href="<?php echo BASE_URL."/page/".$worship->id ?>"><?php echo $worshp->title; ?></a>
		<div class="tooltip" style="bottom: 150px; display: none;"  >
			<div class="tt_point">&nbsp;</div>
			<h2><?php echo $worship->title; ?></h2>
			<p><?php echo $worship->excerpt(); ?></p>
		</div>
	</li>
	<li class="share">
		<a href="<?php echo BASE_URL."/page/".$share->id ?>"><?php echo $share->title; ?></a>
		<div class="tooltip" style="bottom: 150px; display:none">
			<div class="tt_point">&nbsp;</div>
			<h2><?php echo $share->title; ?></h2>
			<p><?php echo $share->excerpt(); ?></p>
		</div>
	</li>
</ul>
<div id="slider">
	<div class="container">		
		<div class="slides" >
			<?php foreach ($events as $event) : 
				$snippet = $event->snippets();
			?>
			<div>
				<span class="slide_image">
					<img src="<?php echo $snippet['image'] ?>" width="495" height="259" alt="<?php echo $event->title; ?>">
				</span>
				<span class="slide_copy">
					<h2><?php echo $event->title; ?></h2>
					<p><?php echo $event->excerpt(); ?> <a href="<?php echo BASE_URL."/post/".$event->id ?>">more...</a></p>
				</span>
			</div>			
			<?php endforeach; ?>
		</div>
	</div>
	<a href="#" class="previous"></a>
	<a href="#" class="next"></a>
</div>
