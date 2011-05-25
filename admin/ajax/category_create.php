<?php
include_once '../../lib/initialize.php';

$category  = new Category();
$category->title = $_POST['title'];
$category->description = $_POST['description'];
$category->slug = slug($_POST['title']);

try{
    $category->save();
} catch (PDOException $error) {
    var_dump($error->getMessage());
}

echo json_encode( $category ); 
?>
