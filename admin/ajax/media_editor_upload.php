<?php
include_once '../../lib/initialize.php';

if ( !empty($_FILES['upload']) ) {

	$media = new Media();	
	$media->attach_file($_FILES['upload']);
	$media->author_id = $session->user_id;
	$media->caption = basename($media->filename);
	$result = $media->save();
}

if ($result) {
	// Required: anonymous function reference number as explained above.
	$funcNum = $_GET['CKEditorFuncNum'] ;
	// Optional: instance name (might be used to load a specific configuration file or anything else).
	$CKEditor = $_GET['CKEditor'] ;
	// Optional: might be used to provide localized messages.
	$langCode = $_GET['langCode'] ;
	 
	// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
	$url = $media->filename;
	// Usually you will only assign something here if the file could not be uploaded.
	$message = implode(',', $media->errors);
	 
	echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
}else{
	echo implode(',', $media->errors);
}
?>
