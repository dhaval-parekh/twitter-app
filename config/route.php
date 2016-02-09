<?php
/*
 * Author: Dhaval Parekh 
 * Author Url: about.me/dmparekh
 * Company : 
 * Company Url : 
 * Description: route file
 *
 *
 *
 *
 *
 */
/*
 * NOTE: in Route the position of the route line will affect to actual Execution
 */

// /// Routes /////
$admin_route = array();
$route = array();
$route[''] = 'index/index';
$route['login'] = 'index/login';
$route['logout'] = 'index/logout';
$route['authuser'] = 'index/authUser';

if (isset($_SESSION['access_token'])) :

	$route[''] = 'user/index'; 
	$route['test'] = 'user/test'; 
	$route['download'] = 'user/downloadTweets'; 
	$route['download/tweets'] = 'user/downloadTweets'; 
	/*switch($_SESSION['user']):
	
	endswitch;*/
else:

endif;

