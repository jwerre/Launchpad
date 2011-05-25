<?php
include_once '../../lib/initialize.php';

if ( isset( $_POST['id'] ) ) {
	$id = $_POST['id'];
	$media = Media::find_by_id($id);
    
    $deleted = $media->destroy();
	
	echo ( $deleted ) ? 'true' : 'false';
}

?>
