<?php
include_once '../../lib/initialize.php';

if ( !empty($_FILES) ) {

	$media = new Media();	
	$media->caption = $_FILES['Filedata']['name'];
	$media->author_id = $_POST['author_id'];
	$media->attach_file( $_FILES['Filedata'] );
	
	if( $media->save() ) {
		$media->force_mimetype(); // Uploadify always returns application/octet-stream 
		$media->save();
	}
	unset($media->upload_errors);
}

echo json_encode($media);
?>
