<div class="widget">
	<h3 class="widget_head">Recent Events</h3>
	<div class="widget_bottom">
		<ul>
		<?php 
	    $events = Post::find_by_category_title("Event", 'created DESC', 5);
		if(!empty($events)){
			foreach ($events as $event) {
                $event_snippets = $event->snippets();
				echo '<li><h3><a href="'. $event->get_link().'">'.$event->title.'</a></h3>';
				if(isset($event_snippets['date'])){
					echo '<date datetime="'.$event_snippets['date'].'"><strong>'.$event_snippets['date'].'</strong></date></li>';
				}
			}
		}
		?>
		</ul>
	</div>
</div>
