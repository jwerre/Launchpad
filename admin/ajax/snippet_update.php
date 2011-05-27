<?php
include_once '../../lib/initialize.php';

$snippet_id = $_POST['id'];
$snippet_name = $_POST['name'];
$snippet_value = $_POST['value'];
$is_option = $_POST['isOption'];

if($is_option == 'true'){
    $snippet = Options::find_by_id($snippet_id);
}else{
    $snippet = Snippet::find_by_id($snippet_id);
}
$snippet->name = $snippet_name;
$snippet->value = $snippet_value;

echo $snippet->save() ? 'true' : 'false';
?>
