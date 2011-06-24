<?php

if (version_compare(PHP_VERSION, '5.3.0', '<') ) exit("Sorry, this version of Launchpad will only run on PHP version 5.3 or greater!\n");

// DIRECTORY_SEPARATOR is a PHP pre-defined constant (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

$lib = dirname(__FILE__);
$root = rtrim(substr( $lib, 0, stripos($lib, 'lib') ), DS);
$root_folder = trim(substr($root, strlen($_SERVER['DOCUMENT_ROOT']) ), '/');
$protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';

defined('SITE_ROOT') ? null : define('SITE_ROOT', $root );
defined('LIB_PATH') ? null : define('LIB_PATH', $lib );
defined('ADMIN_PATH') ? null : define('ADMIN_PATH', SITE_ROOT.DS.'admin' );
defined('IMAGES_PATH') ? null : define('IMAGES_PATH', SITE_ROOT.DS.'images' );
defined('THEMES_PATH') ? null : define('THEMES_PATH', SITE_ROOT.DS.'themes');
defined('UPLOADS_PATH') ? null : define('UPLOADS_PATH', SITE_ROOT.DS.'uploads');
defined('BASE_URL') ? null : define('BASE_URL', rtrim(str_replace( '\\', '/', $protocol . '://' . $_SERVER['HTTP_HOST'] .'/'. $root_folder), '/') );
defined('UPLOADS_URL') ? null : define('UPLOADS_URL', BASE_URL.'/uploads');

// load config
require_once(SITE_ROOT.DS.'config.php');

// main classes
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

// helper classes
require_once(LIB_PATH.DS.'helper'.DS.'inflect.class.php');
// require_once(LIB_PATH.DS.'helper'.DS.'imageprocessor.php');

?>
