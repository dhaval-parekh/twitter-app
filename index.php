<?php
/**
 *	Author: Dhaval Parekh 
 *	Author Url: about.me/dmparekh
 *	Company : 
 *	Company Url : 
 *	Description: index file of application
 *
 *
 *
 *
 *
 */

require_once('config/config.php');
require_once('config/route.php');

// Create Upload Directory if Not Exist
if(!is_dir(UPLOAD_DIR)){	mkdir(UPLOAD_DIR,0777); }
if(!is_dir(USER_DIR)){	mkdir(USER_DIR,0777); }

$host = trim($_SERVER['HTTP_HOST']);
$prefix = strtolower('www.');
$request_prefix = strtolower(substr($host,0,strlen($prefix)));
if(strpos(BASE_URL,$prefix) == true){
	if($request_prefix!=$prefix){
		$host = $prefix.$host;	
		$redirect_url = 'http://'.$host.$_SERVER['REQUEST_URI'];	
		$Url->redirect($redirect_url);

	}
}else{
	if($request_prefix==$prefix){
		$host =  str_replace($prefix,'',$host);
		$redirect_url = 'http://'.$host.$_SERVER['REQUEST_URI'];	
		$Url->redirect($redirect_url);

	}
}

$GET = array();
$GET[0] = $Url->getUrlSegment(0);
//$GET = explode('/',$GET);

$isApiRequest = isset($GET[0])&&strtolower($GET[0])=='api'?true:false;


if($isApiRequest):

	/////*****	 API		*****/////
	require_once(DIR_SYS.DS.'core'.DS.'class.api-request.php');
	require_once(BASE_PATH.'/api/route.api.php');
	require_once(BASE_PATH.'/api/index.php');
	return true;
	/////*****	/API		*****/////

else:
	// to handle normal website responce
	
	$Request  = $Url->getRoute($route);
	$Request = trim($Request,'/');
	$Request = explode('/',$Request);
	$ControllerFile = DIR_CONTROLLER.DS.$Request[0].'.php'; 
	$ControllerFile = file_exists($ControllerFile)?$ControllerFile:false;
	if($ControllerFile){
		require_once($ControllerFile);
		if(class_exists($Request[0])){
			$Controller = new $Request[0]();
			if(method_exists($Controller,$Request[1])){
				$Controller->$Request[1]();
				return true;
			}else{
				
			}
		}else{
			// Control Not Found	
		}
	}else{
		// Controller File not Found
	}
	// 404 :(
	
endif;
$ControllerFile = DIR_CONTROLLER.DS.'index.php'; 
require_once($ControllerFile);
$Controller = new Index();
$Controller->page_404();
//alert((memory_get_usage(true)/1024).' KB\n'.(memory_get_usage()/1024).' KB');
//alert(ini_get('memory_limit'));
