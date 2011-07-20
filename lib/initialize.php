<?php

if (version_compare(PHP_VERSION, '5.3.0', '<') ) exit("Sorry, Launchpad will only run on PHP version 5.3 or greater\n");

/**
 * Pre-defined constant representing a "/" for Unix and a "\" for Windows)
 * @var string
 **/
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

$lib = dirname(__FILE__);
$root = rtrim(substr( $lib, 0, stripos($lib, 'lib') ), DS);
$root_folder = trim(substr($root, strlen($_SERVER['DOCUMENT_ROOT']) ), '/');
$protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';

/**
 * The site's web root eg: /home/user/www
 * @var string
 **/
defined('SITE_ROOT') ? null : define('SITE_ROOT', $root );
/**
 * The site's include files
 * @var string
 **/
defined('LIB_PATH') ? null : define('LIB_PATH', $lib );
/**
 * The path to the admin pages
 * @var string
 **/
defined('ADMIN_PATH') ? null : define('ADMIN_PATH', SITE_ROOT.DS.'admin' );
/**
 * The path to the themes
 * @var string
 **/
defined('THEMES_PATH') ? null : define('THEMES_PATH', SITE_ROOT.DS.'themes');
/**
 * The path to the upload directory
 * @var string
 **/
defined('UPLOADS_PATH') ? null : define('UPLOADS_PATH', SITE_ROOT.DS.'uploads');
/**
 * The path to the widgets directory
 * @var string
 **/
defined('WIDGETS_PATH') ? null : define('WIDGETS_PATH', SITE_ROOT.DS.'widgets');
/**
 * The base url of the site eg: http://www.mysite.com
 * @var string
 **/
defined('BASE_URL') ? null : define('BASE_URL', rtrim(str_replace( '\\', '/', $protocol . '://' . $_SERVER['HTTP_HOST'] .'/'. $root_folder), '/') );
/**
 * The url of the uploads direcory
 * @var string
 **/
defined('UPLOADS_URL') ? null : define('UPLOADS_URL', BASE_URL.'/uploads');

// load config
require_once(SITE_ROOT.DS.'config.php');
require_once(LIB_PATH.DS.'functions.php');
require_once(LIB_PATH.DS.'database.class.php');
require_once(LIB_PATH.DS.'session.class.php');
require_once(LIB_PATH.DS.'cookie.class.php');
require_once(LIB_PATH.DS.'database_object.class.php');
require_once(LIB_PATH.DS.'options.class.php');
require_once(LIB_PATH.DS.'user.class.php');
require_once(LIB_PATH.DS.'user_role.class.php');
require_once(LIB_PATH.DS.'content.class.php');
require_once(LIB_PATH.DS.'content_status.class.php');
require_once(LIB_PATH.DS.'content_type.class.php');
require_once(LIB_PATH.DS.'post.class.php');
require_once(LIB_PATH.DS.'snippet.class.php');
require_once(LIB_PATH.DS.'tag.class.php');
require_once(LIB_PATH.DS.'category.class.php');
require_once(LIB_PATH.DS.'page.class.php');
require_once(LIB_PATH.DS.'media.class.php');
require_once(LIB_PATH.DS.'image.class.php');
require_once(LIB_PATH.DS.'navigation.class.php');
require_once(LIB_PATH.DS.'url_rewrite.class.php');
require_once(LIB_PATH.DS.'mail.class.php');
?>
