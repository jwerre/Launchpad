<?php
include_once '../../lib/initialize.php';

if ( !empty($_POST['width']) && !empty($_POST['height']) ) {
	$image_id = $_POST['image_id'];
	$crop_width = $_POST['width'];
	$crop_height = $_POST['height'];
	$user_id = $_POST['user_id'];
	
	$image = Image::find_by_id($image_id);
	$image->resize($crop_width, $crop_height, Image::CROP);
	$image->save_image(); // save the cropped image
	$image->save(); // save to database;
	
	$user = User::find_by_id( $user_id );
	// need to delete the old image here
	$user->image_id = $image->id;
	$user->save();
	
	echo json_encode($image);
}
?>