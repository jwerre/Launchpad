<?php 
	global $page;
	global $options;
	$nav = new Navigation();
	$nav->exclude = array('Home');
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
	<meta name="description" content="<?php echo $options->description; ?>">
	<meta name="author" content="<?php echo $options->site_name; ?>">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta property="og:title" content="<?php echo $options->site_name; ?>"/>
    <meta property="og:type" content=""/>
    <meta property="og:url" content="<?php //echo $page->get_link(); ?>"/>
    <meta property="og:image" content=""/>
    <meta property="og:site_name" content="<?php echo $options->site_name; ?>"/>
    <meta property="og:description" content="<?php echo $options->description; ?>"/>

	<title><?php echo $options->site_name . " | ". $page->title;  ?></title>
	
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">

	<link rel="stylesheet" href="<?php echo css(); ?>?v=2">
	<script src="<?php echo js_directory() ?>/libs/modernizr-1.6.min.js"></script>

</head>

<body id="<?php echo $page->slug ?>">
	<!--[if lte IE 6]><script src="http://ie6-upgrade-warning.googlecode.com/svn/trunk/ie6/warning.js"></script><script>window.onload=function(){e("http://ie6-upgrade-warning.googlecode.com/svn/trunk/ie6/")}</script><![endif]-->
	<div id="container">
		<header>
			<a href="<?php echo Navigation::get_page_link(1); ?>" alt="My Site" class="logo"><?php echo $options->site_name; ?></a>
			<?php $nav->output_nav(); ?>
		</header>
		<div id="main" class="clearfix">
