<?php

include_once '../../lib/initialize.php';
$category_id = $_POST['id'];

echo Category::delete_by_id($category_id) ? 'true': 'false';
?>
