<?php 
	global $page;
	$nav = new Navigation();
	$nav->excludes = array(1);
	$nav->current_page = (isset($page)) ? $page->id : NULL;
	
?>
<!doctype html>  
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta property="og:title" content=""/>
    <meta property="og:type" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:site_name" content=""/>
    <meta property="fb:admins" content=""/>
    <meta property="og:description" content=""/>

	<title>North Sound Church</title>
	
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">

	<link rel="stylesheet" href="<?php echo css(); ?>?v=2">
	<link rel="stylesheet" type="text/css" href="<?php echo js_directory();  ?>/shadowbox/shadowbox.css">
	
	<script src="<?php echo js_directory() ?>/libs/modernizr-1.6.min.js"></script>

</head>

<body id="<?php echo $page->slug ?>">
	<!--[if lte IE 6]><script src="http://ie6-upgrade-warning.googlecode.com/svn/trunk/ie6/warning.js"></script><script>window.onload=function(){e("http://ie6-upgrade-warning.googlecode.com/svn/trunk/ie6/")}</script><![endif]-->
	<div id="container">
		<header>
			<a href="<?php echo BASE_URL ?>/page/1" alt="My Site" class="logo">My Site</a>
			<?php $nav->output_nav(); ?>
		</header>
		<div id="main" class="clearfix">
