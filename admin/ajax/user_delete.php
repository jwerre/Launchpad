<?php
    include '../../lib/initialize.php';

	if( !empty($_POST['id']) ) {
		$result = User::delete_by_id($_POST['id']);
		echo ($result) ? 'true' : 'false';
    }


?>
