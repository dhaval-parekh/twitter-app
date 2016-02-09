<?php
/*	*	*	*	*	*	*	*	*	*	*	*
 *	Author: Dhaval Parekh  [) ]-[ /-\ \/ /-\ |_ 		*
 *	Author Url: about.me/dmparekh					*
 *	Company : 								*
 *	Company Url : 								*
 *	Description: application configuration File		*
 *											*
 *											*
 *											*
 *											*
 *	*	*	*	*	*	*	*	*	*	*	*
 */




// Site Setting
define('SITE_NAME', 'Twitter');
define('SITE_PHONE', '+919998887776');

// Global Setting
define('BASE_PATH', dirname(dirname(__FILE__)));
define('DEBUG_MODE', true);
define('API_KEY', 'app_secreat_key');

define('PASSWORD','aw-098@#@Q_Ckpawe94');
define('SALT','S%#)(vcds;ldWEas@#@');

define('BASE_URL', 'put-your-domain.com'); //Ex `http://domain.com/`
define('INDEX', 'index.php');
define('DS', DIRECTORY_SEPARATOR);
define('APP_URL', BASE_URL.'app/');
define('API_URL', BASE_URL.INDEX.'/'.'api/');
define('ADMIN_URL', BASE_URL.INDEX.'/'.'admin/');

define('CACHE_EXPIRY_TIME',15*60); // time in second // 15 minute

// session File Setting
ini_set('session.save_path',BASE_PATH.DS.'tmp'.DS.'session');

// Timezone
date_default_timezone_set('UTC');
$timezone = '+5.30';
$timezone = preg_replace('/[^0-9]/', '', $timezone) * 36;
$timezone_name = timezone_name_from_abbr(null, $timezone, true);
$timezone_name = $timezone_name?$timezone_name:'Asia/Kolkata';
date_default_timezone_set($timezone_name);

// Error Reporting 
error_reporting(-1 & ~E_DEPRECATED);
error_reporting(0);


session_start();

define('SITE_EMAIL','site@domain.com'); 
define('SITE_EMAIL_PASS','password');

// Twitter
define('TWEETER_CONSUMER_KEY', 'twitter-consumer-key');
define('TWEETER_CONSUMER_SECRET', 'twitter-consumer-secret');

define('TWEETER_ACCESS_TOKEN','twitter-access-token');
define('TWEETER_ACCESS_TOKEN_SECRET','twitter-access-token-secret');


define('DB_SCRIPT_PATH', BASE_PATH.DS.'db'.DS.'database.sql');
define('DB_NAME', 'project.db');
define('DB_PATH', BASE_PATH.DS.'db'.DS);
define('DATABASE', DB_PATH.DB_NAME);



/*****	Dir Settings	*****/
define('DIR_CONFIG',BASE_PATH.DS.'config');
define('DIR_APP',BASE_PATH.DS.'app');
define('DIR_SYS',BASE_PATH.DS.'sys');
define('DIR_CONTROLLER',DIR_APP.DS.'controllers');
define('DIR_MODAL',DIR_APP.DS.'modals');
define('DIR_VIEW',DIR_APP.DS.'views');
define('DIR_LIBRARY',BASE_PATH.DS.'lib');


/** Upload Directory */
define('UPLOAD_DIR','uploads');
define('USER_DIR',UPLOAD_DIR.DS.'user'.DS);



/**	Importing of file	**/
// Load Libraries 
require_once(DIR_LIBRARY.DS.'twitteroauth'.DS.'vendor'.DS.'autoload.php');


// Load Class	//
require_once(DIR_SYS.DS.'classes'.DS.'class.database-access-sqllite.php');
require_once(DIR_SYS.DS.'classes'.DS.'class.url.php');
require_once(DIR_SYS.DS.'classes'.DS.'class.input.php');


// Load Main MVC Layers
require_once(DIR_SYS.DS.'core'.DS.'controller.php');
require_once(DIR_SYS.DS.'core'.DS.'modal.php');




require_once(DIR_SYS.DS.'helpers'.DS.'helper.app.php');
require_once(DIR_SYS.DS.'helpers'.DS.'helper.url.php');
require_once(DIR_SYS.DS.'helpers'.DS.'helper.debug.php');

/**	Global Variables	**/

$Url = new Url(BASE_URL.INDEX);
$ApiUrl = new Url(API_URL);

