<?php
include_once '../../lib/initialize.php';

$snippet_id = $_POST['id'];
$snippet_name = $_POST['name'];
$snippet_value = $_POST['value'];
$is_option = (boolean) $_POST['isOption'];
// echo '<pre>'; print_r($_POST); echo '</pre>';
if($is_option){
    $snippet = Options::find_by_id($_POST['id']);
    $snippet->name = $snippet_name;
    $snippet->value = $snippet_value;
}else{
    $snippet = Snippet::find_by_id($snippet_id);
    $snippet->name = $snippet_name;
    $snippet->value = $snippet_value;
}
echo $snippet->save() ? 'true' : 'false';
?>
