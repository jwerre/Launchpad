<?php
/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection to a database.
 */

define('DB_SERVER', 'localhost');
define('DB_PORT', '');
define('DB_NAME', 'S2');
define('DB_USER', 'root');
define('DB_PASSWORD', '');


/**
 * Set the default timezone.
 * http://www.php.net/manual/en/timezones.php
 */
date_default_timezone_set('America/Los_Angeles');

/*
 * Site admin details
 */
define('ADMIN_ID', 1); 
define('ADMIN_NAME', ''); 
define('ADMIN_EMAIL', ''); 

/**
 * The maximum amount of time (in seconds) after the last page  refresh that a user are still considered active.
 */
define('SESSION_LIFE', 1800); 

/**
 * Store session in database — (use in load balanced environment)
 */
define('USE_DB_SESSION', false);

/*
 * Approximate length of a post or page excerpt
 */
define('EXCERPT_LENGTH', 200);

/*
 * default amount of items per page ( used in pagination) 
 */
define('ITEMS_PER_PAGE', 10);

/**
 * Cookie Constants - days until cookies expire
 */
define('COOKIE_EXPIRE', 60*60*24*100);  //100 days by default

/**
 * Profile Image width and height in pixels
 */
define('PROFILE_IMG_WIDTH', 80);
define('PROFILE_IMG_HEIGHT', 80);

/**
 * SMTP Email Constants - these specify the email setting.
 */
define('SMTP_HOST', '');
define('SMTP_PORT', 25);
define('SMTP_AUTH', true);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');


/**
 * Uploading Constants - Max size for file uploads
 */
define('MAX_FILE_SIZE', 104857600); // 100MB

/**
 * turn on and off clean urls
 **/
define('CLEAN_URLS', false);

/**
 * Use slug name for url instead of id
 **/
define('SLUG_URLS', true);

?>
