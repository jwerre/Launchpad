<?php
include_once '../../lib/initialize.php';

if ( isset( $_GET['id'] ) ) {
	$id = $_GET ['id'];
	$content = Content::find_by_id($id);
	
	if( $content->delete() ){
		$session->msg_type = 'success_msg';
		$session->message('The '.$content->type.' '.$content->title.' was deleted.');
		echo 'true';
	}else{
		// $session->msg_type = 'success_msg';
		// $session->message('The '.$content->type.' '.$content->title.' could not be deleted.');
		echo 'false';
	}
}

?>
