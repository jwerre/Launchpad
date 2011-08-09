<?php
	global $session;
    global $cookie;
	
	if( $session->is_logged_in() ){
		if( $session->role >= UserRole::GUEST ){
			$session->msg_type = 'info_msg';
			$session->message('Hey, you\'re not allowed in here. Ask your site administrator to upgrade your access privileges.');
			redirect_to('login.php');
		}
	}else{	
		// $session->msg_type = 'warning_msg';
		// $session->message('Please login.');
		redirect_to('login.php');
	}
	
    $message = $session->message();
    $colors = array( 'red', 'orange', 'yellow', 'blue', 'green', 'violet', 'black', 'white');
    $widths = array( '75 Percent', '90 Percent', '100 Percent', '960 Pixels', '1280 Pixels', '1400 Pixels', '1600 Pixels');
?>
<!doctype html>  
<!--[if IE 8 ]>    <html class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<title>Launchpad - A content management system</title>
	<meta name="description" content="A content management system">
	<meta name="author" content="Jonah Werre">

	<link rel="shortcut icon" href="favicon.ico">

	<link rel="stylesheet" href="<?php echo BASE_URL?>/admin/css/style.css">
	<link rel="stylesheet" href="<?php echo BASE_URL?>/admin/css/switcher.php?default=black.css">
	<link rel="stylesheet" href="<?php echo BASE_URL?>/admin/css/prettyPhoto.css">
	<link rel="stylesheet" href="<?php echo BASE_URL?>/admin/js/libs/uploadify/uploadify.css">
	<link rel="stylesheet" href="<?php echo BASE_URL?>/admin/js/libs/jqueryUI/css/jqueryUI_fresh.css">

	<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
	<script src="<?php echo BASE_URL ?>/admin/js/libs/modernizr-1.6.min.js"></script>
</head>

<body lang="en" id="layout_100">
	<!--[if lt IE 8]><script src="js/libs/ie_upgrade_warning/warning.js"></script><script>window.onload=function(){e("js/libs/ie_upgrade_warning/")}</script><![endif]-->
	<header>
		<div id="top" class="width">
			<div id="logo"><a href="index.php">Launchpad</a></div>
			<nav class="right">				
				<ul>
                    <li id="settings_nav">
                        <a href="#">Settings</a>
                        <div class="clearfix">
                            <ul id="color_settings">
                                <li> <h4>Color</h4> </li>
                                <?php
                                    foreach ($colors as $color) : 
                                        $current_style = ( isset($_COOKIE['switcher_php-style']) ) ? preg_replace("/\\.[^.\\s]{3,4}$/", "", $_COOKIE['switcher_php-style']) : '';
                                ?>
                                <li> <a href="css/switcher.php?style=<?php echo $color ?>.css" class="<?php echo ($current_style == $color) ? 'on' : ''; ?>" ><?php echo ucwords($color); ?></a> </li>
                                <?php endforeach; ?>
                            </ul>
                            <ul id="layout_settings" >        
                                <li> <h4>Width</h4> </li>
                                <?php foreach ($widths as $width) : ?>
                                <li> <a href="#" data-layout="layout_<?php echo strstr($width, ' ', true) ?>" class="<?php echo ($cookie->lp_width == $layout) ? 'on' : ''; ?>" ><?php echo ucwords($width) ?></a> </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
					<li><a href="<?php echo BASE_URL ?>" target="_blank">Visit Site</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</nav>
		</div>
		<nav class="width">
		<ul >
			<li><a href="content.php?type=page">Pages</a>
				<ul>
					<li><a href="content_edit.php?type=page" class="page_icn">Create New Page</a></li>
				</ul>
			</li>
			<li><a href="content.php?type=post">Posts</a>
				<ul>
					<li><a href="content_edit.php?type=post" class="post_icn">Create New Post</a></li>
					<li><a href="categories.php" class="category_icn">Categories</a></li>
				</ul>
			</li>
			<li>
				<a href="media.php">Media</a>
				<ul>
					<li><a href="media_edit.php" class="upload_icn">Upload New Media</a></li>
					<li><a href="media.php?type=image" class="image_icn">Images</a></li>
					<li><a href="media.php?type=pdf" class="pdf_icn">PDF</a></li>
					<li><a href="media.php?type=audio" class="audio_icn">Audio</a></li>
					<li><a href="media.php?type=video" class="video_icn">Video</a></li>
					<li><a href="media.php?type=other" class="other_icn">Other</a></li>
				</ul>
			</li>
			<li>
				<a href="users.php">Users</a>
				<ul>
					<li><a href="user_edit.php" class="user_icn">Create New User</a></li>
				</ul>
			</li>
			<li>
				<a href="options.php">Options</a>
				<ul>
					<li><a href="themes.php" class="theme_icn">Change Theme</a></li>
				</ul>
			</li>
		</ul>
		</nav>
	</header>
	<?php if( !empty( $message ) ) : ?>
	<div id="message" class="<?php echo $session->msg_type; ?> hidden">
		<p><?php echo $message; ?> <a href="#" class="close">close</a> </p>
	</div>
	<?php endif; ?>
	<div id="container" class="width">
		<?php include_layout("column_left.php" ,"layouts"); ?>
		<div id="main" role="main" class="clearfix">
			<a href="" id="toggle_aside"><span>open</span></a>
