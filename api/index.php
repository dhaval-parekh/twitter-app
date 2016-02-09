<?php

$ApiGET = explode('/',$ApiUrl->getUrlSegment());

$command = isset($ApiGET[0])?strtolower($ApiGET[0]):false;
if($command):
	$Request = isset($api_route[$command])?$api_route[$command]:false;
	//DisplayObject($Request);
	if($Request){
		try{
			echo $Request->Response();	
		} catch (ErrorException $e) {
    			echo json_encode(array('status'=>500,'message'=>'Internal Server Error'));
		}
	}else{
		//header('Content-type: application/json');
		echo json_encode(array('status'=>404,'message'=>'request not found'));
	}
	
endif;

