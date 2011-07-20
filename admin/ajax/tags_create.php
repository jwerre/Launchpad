<?php
include '../../lib/initialize.php';

$content_id = $_POST['id'];
$tags = $_POST['tags'];
$content = Content::find_by_id($content_id);
echo json_encode( $content->set_tags($tags) );

// echo $content->save() ? 'true' : 'false';
?>
