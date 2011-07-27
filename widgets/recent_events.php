<div class="box">
	<h3 class="box_head">Recent Events</h3>
	<div class="box_bottom">
		<ul>
		<?php 
	    $events = Post::find_by_category_title("Event", 'created DESC', 5);
		if(!empty($events)){
			foreach ($events as $event) {
                $event_snippets = $event->snippets();
				echo '<li><h3><a href="'. $event->get_link().'">'.$event->title.'</a></h3>';
				if(isset($event_snippets['date'])){
					echo '<p class="author"><strong>'.$event_snippets['date'].'</strong></p></li>';
				}
			}
		}
		?>
		</ul>
	</div>
</div>
