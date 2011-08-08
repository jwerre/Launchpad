<?php
require_once('../lib/initialize.php');

	if($session->is_logged_in()){
		redirect_to('index.php');
	}

	if (isset($_POST['submit'])){
		$user_info = trim($_POST['user_info']);
		
		$user = User::get_user($user_info);
		echo '<pre>' . print_r($user, true) . '</pre>'; exit;
		if(!empty($user)){
			$new_password = $user->generate_random_password();
			$user->password = md5(trim($new_password));

			if($user->save()){

				$mail = new Mail();
				$mail->Subject = "Your Username and Password for ".$options->site_name;
				$mail->Body = "Username: ".$user->username."\nPassword: $new_password";
				$mail->AddAddress($user->email, $user->full_name());
				$mail->Send();
				unset($mail);

				$session->msg_type = 'success_msg';
				$session->message('Your username and password was sent to '.$user->email.'<br><a href="login.php">Click Here to Login</a>');
			}else{
				$session->msg_type = 'error_msg';
				$session->message('The username or email address you entered could not be found');
			}
		}else{
			$session->msg_type = 'error_msg';
			$session->message('The username or email address you entered could not be found');
		}
	}
	$message = $session->message();
?>


<!doctype html>  
<!--[if IE 8 ]>    <html class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Launchpad - A content management system</title>
	<link rel="stylesheet" href="css/style.css?v=1">
	<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
	<script src="js/libs/modernizr-1.6.min.js"></script>
</head>
<body id="" >
	<div id="container" class="no_aside" style="margin:0 auto;width:360px;" >
	<div id="main" role="main" class="clearfix" style="background:none;border:none;height:auto;min-height:100px;">
			
	<div id="login" class="clearfix">
		<div class="logo"><span></span></div>
		<?php if( !empty($message) ) : ?>
		<div id="message" class="<?php echo $session->msg_type; ?>">
			<p><?php echo $message; ?> <a href="#" class="close">close</a> </p>
		</div>
		<?php endif; ?>
		<form method="post" action="lost_login.php">
			<p><input type="text" name="user_info" maxlength="30" placeholder="Username or Email"/></p>
			<p ><button type="submit" name="submit">Email me my password</button></p>
		</form>
	</div>

<?php
	include_layout("footer.php" ,"layouts");
?>
