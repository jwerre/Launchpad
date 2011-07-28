<?php 
	global $page;
	global $post;
	global $options;
	$tags = "";
	if(!empty($page)){
		$tags = $page->tags( true );
	} elseif(!empty($post)){
		$tags = $post->tags( true );
	}
	$nav = new Navigation();
	// $nav->include = array('Your Journey', 'Community', 'Events', 'Resources', 'About Us');
	$nav->current_page = (isset($page) && !empty($page)) ? $page->id : NULL;

	$page_title = "Navigating life together";
	if(isset($page) && !empty($page) ){ $page_title = $page->title; }
	elseif( isset($post) && !empty($post) ){ $page_title = $post->title;}
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
	<meta name="author" content="Launch Interactive">
	<meta name="keywords" content="<?php echo $tags; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta property="og:title" content="<?php echo $page_title;  ?>" />
    <meta property="og:type" content="non_profit"/>
    <meta property="og:url" content="<?php echo current_page_url();?>"/>
    <meta property="og:image" content="images/site_icon.png"/>
	<meta property="og:site_name" content="<?php echo $options->site_name; ?>"/>
    <meta property="fb:admins" content=""/>
    <meta property="og:description" content="Navigating Life Together"/>

	<title><?php echo $page_title; ?></title>
	
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">

	<link rel="stylesheet" href="<?php echo css() ?>">
	<link rel="stylesheet" href="<?php echo js_directory() ?>/libs/jqueryUI/css/jqueryUI.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="<?php echo js_directory() ?>/libs/fullcalendar/fullcalendar.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="<?php echo js_directory() ?>/libs/qtip/jquery.qtip.css" type="text/css" media="screen" title="no title" charset="utf-8">
	
	<script src="<?php echo js_directory() ?>/libs/modernizr-1.6.min.js"></script>

</head>

<body id="<?php echo $page->slug ?>">
	<!--[if lte IE 6]><script src="http://ie6-upgrade-warning.googlecode.com/svn/trunk/ie6/warning.js"></script><script>window.onload=function(){e("http://ie6-upgrade-warning.googlecode.com/svn/trunk/ie6/")}</script><![endif]-->
	<div id="container">
		<header>
		<h1><a href="<?php echo Navigation::get_link(1) ?>" class="logo"><?php echo $options->site_name; ?></a></h1>
			<?php $nav->output_nav(); ?>
		</header>
		<div id="main" class="clearfix">
