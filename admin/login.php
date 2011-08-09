<?php
require_once('../lib/initialize.php');

	if( $session->is_logged_in() && $session->role < UserRole::GUEST ){
		$session->msg_type = 'info_msg';
		$session->message( 'You\'re still logged in.' );
		redirect_to('index.php');
	}
	
	$error_msg = array("Ooops! That's not it.", 
			"Nope, try again.", 
			"You can always reset your password you know.", 
			"Try logging in with your email address; that works too.", 
			"Uh Oh... you forgot your password didn't you?", 
			"That's not your username and password is it?", 
			"Are you sure you're in the right place?", 
			"Here's a hint: your password is somewhere on your keyboard.", 
			"If I had a nickel for every time someone forgot their password...");
	
	$login_error = false;
	if (isset($_POST['submit'])){
		$username = trim($_POST['username']);
		$password = md5(trim($_POST['password']));
	
		$user = User::authenticate($username, $password);
				
		if( $user ){
			$session->login($user->id);
			$session->user_id = $user->id;
			$session->role = $user->role;
			$session->username = $user->username;
			
			if( $session->role >= UserRole::GUEST ){
				$login_error = true;
				$session->msg_type = 'info_msg';
				$session->message( 'Hey, you can\'t come in here. Ask your site administrator to upgrade your access privileges.' );
				//redirect_to('logout.php');
			}else{
				$session->msg_type = 'success_msg';
				$session->message("Good to see you again ". $user->first_name.".");
				redirect_to('index.php');
			}
			
		}else{
			$login_error = true;
			$session->msg_type = 'error_msg';
			$session->message( $error_msg[ array_rand($error_msg) ] );
		}
	}
	
	$message = $session->message();
?>


<!doctype html>  
<!--[if IE 8 ]>    <html class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Launchpad - A content management system</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="css/style.css?v=1">
	<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
	<script src="js/libs/modernizr-1.6.min.js"></script>
</head>
<body id="" class="login" >
	<div id="container" class="no_aside" style="margin:0 auto;width:360px;" >
	<div id="main" role="main" class="clearfix" style="background:none;border:none;height:auto;min-height:100px;">
			
	<div id="login" class="clearfix">
		<div class="logo"><span></span></div>
		<?php if( !empty($message) ) : ?>
		<div id="message" class="<?php echo $session->msg_type; ?>">
			<p><?php echo $message; ?> <a href="#" class="close">close</a> </p>
		</div>
		<?php endif; ?>
		<form method="post" action="login.php">
			<p><label for="username">Username or Email</label><input id="username" value="" type="text" name="username" /></p>
			<p><label for="password">Password</label><input id="password" value="" type="password" name="password" /></p>
			<p><button type="submit" name="submit" value="Login">Login</button></p>
			<p class="note"><a href="lost_login.php">forgot your password?</a></p>
		</form>
	</div>

<?php
	include_layout("footer.php" ,"layouts");
	
	if( $login_error ){
		echo "<script>
			var speed = 70;
			var distance = 30;
			$('#login').animate({ left: '+='+distance }, speed, function() {
				$('#login').animate({ left: '-='+distance }, speed, function() {
					$('#login').animate({ left: '+='+distance }, speed, function() {
						$('#login').animate({ left: '-='+distance }, speed, function() {
							$('#login').animate({ left: '+='+distance }, speed, function() {
								$('#login').animate({ left: '-='+distance }, speed, function() {
							$('#username').val('');
							$('#password').val('');
								});
							});
						});
					});
				});
			});
		</script>";
	}
?>
