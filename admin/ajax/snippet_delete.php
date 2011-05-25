<?php

include_once '../../lib/initialize.php';
$snippet_id = $_POST['id'];

echo Snippet::delete_by_id($snippet_id) ? 'true': 'false';
?>
