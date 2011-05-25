	
<?php
global $page;
if( empty($page->template) ){
	echo '<div id="column_left">';
	if (!empty($page->title))
		echo '<h1 class="'.$page->slug.'">'.$page->title.'</h1>';
	if (!empty($page->body))
		echo $page->body;
	echo '</div>';
	include_layout("column_right.php");
}else{
	include_template($page->template);
}
?>

