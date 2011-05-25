<?php
include_once '../../lib/initialize.php';

if ($_POST['content_id'] == 0 ) {
    $snippet = new Options();
}else{
    $snippet  = new Snippet();
    $snippet->content_id = $_POST['content_id'];
}

$snippet->name = $_POST['snippet_name'];
$snippet->value = $_POST['snippet_value'];

$snippet->save();


echo json_encode( $snippet ); 
?>
