<?php
    include '../../lib/initialize.php';
	$events = Post::find_by_category_title( 'Event' );
	if(!empty($events)){
		$all_events = array();

		foreach ($events as $event){
			$new_event = $event->snippets();
			if( isset($new_event['start']) ){
				$new_event['tip'] = simple_date($new_event['start'], true, true);
				$new_event['start'] = strtotime($new_event['start']);
			}
			if( isset($new_event['end']) ) {
				$new_event['tip'] .= ' to '. simple_date($new_event['end'], true, true);
				$new_event['end'] = strtotime($new_event['end']);
			}
			$new_event['id'] = $event->id;
			$new_event['title'] = $event->title;
			$new_event['url'] = $event->get_link();
			$all_events[] = $new_event;
		}

		echo json_encode($all_events);
	}
?>
