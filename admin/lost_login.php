<?php
require_once('../lib/initialize.php');

	if($session->is_logged_in()){
		redirect_to('index.php');
	}

	if (isset($_POST['submit'])){
		$user_info = trim($_POST['user_info']);
		
		$user = User::get_user($user_info);
		$new_password = $user->generate_random_password();
		$user->password = md5(trim($new_password));
		if($user->save()){
			$site = ($options->get_options()) ? $options->get_options()->site_name : "";
			$mail = new Mail();
			$mail->to_name = $user->full_name();
			$mail->to_email = $user->email;
			$mail->subject = "Your Username and Password for ".$site;
			$mail->message = "Username: ".$user->username."\nPassword: ";
			$mail->send();
			unset($mail);
			$message = 'Your username and password was sent to '.$user->email.'<br><a href="login.php">Click Here to Login</a>';
		}else{
			$message = "The username or email address you entered could not be found";
		}
	}else{
		
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Provent | Content Management System</title>
	<link rel="stylesheet" href="css/admin.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Neuton&subset=latin' rel='stylesheet' type='text/css'>
	<script type="text/javascript" charset="utf-8">
	function clearInput(pInput, pDefaultText) {
		if (pInput.value == pDefaultText) {
			pInput.value = "";
			pInput.style.color = "#1c2324";
		}
	}

	function recallInput(pInput, pDefaultText) {
		if (pInput.value == "") {
			pInput.value = pDefaultText;
			pInput.style.color = "#B8B8B8";
		}
	}
	</script>
</head>
<body>


<?php echo output_message($message); ?>
<div id="login">
	<div class="logo"><img src="images/logo_launchpad.png" width="190" height="37" alt="Launchpad A Content Management System"></div>
	<form method="post" action="lost_login.php">
		<p><input type="text" name="user_info" maxlength="30" value="Username or Email" onclick="clearInput(this, 'Username or Email')" onblur="recallInput(this,'Username or Email')"/></p>
		<p><button type="submit" name="submit">Email it to me</button></p>
	</form>
</div>


<?php 
if(isset($database)){
	$database->close_connection();
} 
?>

</body>
</html>
