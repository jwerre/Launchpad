<?php
// reorder page weights
include '../../lib/initialize.php';

$term = $_GET['tag']['term'];
$sql = "SELECT tag FROM tags WHERE tag LIKE '$term%'";
$tags = TAG::find_by_sql($sql);
$tag_arr = array();
foreach ($tags as $tag) {
    $tag_arr[] = $tag->tag;
}
echo json_encode($tag_arr);
?>
