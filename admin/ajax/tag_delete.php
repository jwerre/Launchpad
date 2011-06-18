<?php
    include '../../lib/initialize.php';

    $content_id = $_POST['content_id'];
    $tag_id = $_POST['tag_id'];
    $content = Content::find_by_id($content_id);

    if( !empty($content) ){
        echo $content->delete_tag($tag_id) ? 'true' : 'false';
    }else{
        echo 'false';
    }
    
?>
