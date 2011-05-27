<?php

include_once '../../lib/initialize.php';
$snippet_id = $_POST['id'];
$is_option = $_POST['isOption'];

if($is_option == 'true'){
    $snippet = Options::find_by_id($snippet_id);
}else{
    $snippet = Snippet::find_by_id($snippet_id);
}

echo $snippet->delete() ? 'true' : 'false';
?>
