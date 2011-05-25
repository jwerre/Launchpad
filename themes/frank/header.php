<?php
	global $page;
	$nav = new Navigation();
	// $nav->excludes = array(1);
	$nav->current_page = (isset($page)) ? $page->id : NULL;
?>
<!DOCTYPE html>
<html lang="en">
<head>

<!-- TITLE -->
<title>Home | Project title</title>

<!-- META INFORMATION -->
<meta charset="UTF-8">
<meta name="description" content="Website description" /> 
<meta name="keywords" content="Comma separated keywords" /> 
<meta name="author" content="Your Name" /> 
<meta name="copyright" content="2010 Company" />
<meta name="publisher" content="Company" /> 
<meta name="robots" content="index,all" /> 
<meta name="revisit-after" content="1 day" />

<!-- STYLESHEETS --> 
<link type="text/css" href="<?php echo css_directory(); ?>/reset.css" rel="stylesheet" /> 
<link type="text/css" href="<?php echo css_directory(); ?>/style.css" rel="stylesheet" />
<link type="text/css" href="<?php echo css_directory(); ?>/jquery.fancybox-1.3.1.css"  media="screen" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
<!--[if lte IE 7]>
<link type="text/css" href="css/ie7.css"  media="screen" rel="stylesheet" />
<![endif]-->
<!--[if lte IE 6]>
<link type="text/css" href="css/ie6.css"  media="screen" rel="stylesheet" />
<![endif]-->


<!-- JAVASCRIPT --> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo js_directory(); ?>/jquery.fancybox-1.3.1.js"></script>

<!-- FAVICON --> 
<link type="image/x-icon" href="favicon.ico" rel="shortcut icon" /> 

<!-- HTML5 FIX OLDER BROWSERS -->
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">	</script>
<![endif]-->

</head>

<body>

<!-- START WRAPPER -->
<div id="wrapper">
	<div class="grid2 margin_no_bottom">
		<nav>
            <?php $nav->output_nav() ?>
		</nav>
