<?php

include_once '../../lib/initialize.php';
$snippet_id = $_POST['id'];
$is_option = (boolean) $_POST['isOption'];

if($is_option){
    $snippet = Options::find_by_id($snippet_id);
}else{
    $snippet = Snippet::find_by_id($snippet_id);
}

echo $snippet->delete() ? 'true' : 'false';
?>
