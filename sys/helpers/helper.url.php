<?php
if(!function_exists('getUrlSegment')):
	function getUrlSegment($base_url,$segment = FALSE){
		$result = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$result = str_replace(INDEX,'',$result);
		$result = substr($result,strlen($base_url));
		$result = trim($result,'/');
		if(is_numeric($segment) ):
			$segments = explode('/',$result);
			//$segments = trim($segment,'?');
			return $segments[$segment];
		endif;
		return  $result;
	}
endif;
if(!function_exists('redirect')):
	function redirect($url){
		header('Location: '.$url)
		or
		die('<script> window.location="'.$url.'";</script>');
	}
endif;

if(!function_exists('getBaseurl')):
	function getBaseurl(){
		return BASE_URL.INDEX;
	}
endif;
