<?php
	include '../lib/initialize.php';
	
	$session->logout();
	
	if(!$session->is_logged_in()){
		session_start();
		$session->msg_type = 'info_msg';
		$session->message( 'You have logged out' );
		redirect_to("login.php");
	}
?>
