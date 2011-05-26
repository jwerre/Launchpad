<?php
include '../../lib/initialize.php';

$term = $_GET['snippet']['term'];
$sql = "SELECT DISTINCT name FROM snippets WHERE name LIKE '$term%'";
$snippets = Snippet::find_by_sql($sql);
$snippet_arr = array();
foreach ($snippets as $snippet) {
    $snippet_arr[] = $snippet->name;
}
echo json_encode($snippet_arr);
?>
