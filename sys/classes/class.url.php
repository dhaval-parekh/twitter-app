<?php
class Url{
	private $BaseUrl;
	public function __construct($BaseUrl = false){
		if($BaseUrl){
			$this->BaseUrl = $BaseUrl;
		}
		trim($this->BaseUrl);
	}
	
	public function setBaseUrl($BaseUrl){
		self::__construct($BaseUrl);
	}
	
	// Public function
	// To get BaseUrl Url
	public function getBaseUrl(){
		return $this->BaseUrl;
	}
	
	// Public function
	// To get Request Url	
	public function getRequestUrl(){
		$host = trim($_SERVER['HTTP_HOST']);
		$prefix = strtolower('www.');
		$request_prefix = strtolower(substr($host,0,strlen($prefix)));
		if(strpos($this->BaseUrl,$prefix) == true){
			if($request_prefix!=$prefix){
				$host = $prefix.$host;		
			}else{
					
			}
		}else{
			if($request_prefix==$prefix){
				$host =  str_replace($prefix,'',$host);
			}
		}
		return 'http://'.$host.$_SERVER['REQUEST_URI'];
	}
	
	// Public function 
	// Will return Segment of user that is after the base url
	public function getUrlSegment($segment = FALSE){
		
		$result =  $this->getRequestUrl(); //'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		//$result = str_replace('www.','',$result);
		
		$result = substr($result,strlen($this->BaseUrl));
		
		$result = str_replace(INDEX,'',$result);
		
		$result = trim($result,'/');
		
		if(is_numeric($segment) ):
			$segments = explode('/',$result);
			
			$index = $segment;
			$segment = isset($segments[$index])?$segments[$index]:false;
			
			if($segment){
				if(strpos($segment,'?') !== false){
					$segment = strstr($segment,'?',true);
				}
				if(strpos($segment,'&') !== false){
					$segment = strstr($segment,'&',true);
				}
				
			}
			
			return $segment;
		endif;
		return  $result;
	}
	
	
	// Public function 
	// to Redirect the page
	public function redirect($url){
		//$url = $this->BaseUrl.$url;
		header('Location: '.$url)
		or
		die('<script> window.location="'.$url.'";</script>');
	}
	
	
	// public function 
	// return the route of url
	public function getRoute($routes,$getAll = false){
		//display($routes);
		$Route = array();
		$Request  = $this->getUrlSegment();
		
		if(strpos($Request,'?') !== false ){
			$Request = strstr($Request,'?',true);
		}
		if(strpos($Request,'&') !== false ){
			$Request = strstr($Request,'&',true);
		}
		$Request = str_replace('/','-',trim($Request,'/').'/');
		foreach($routes as $key=>$value){
			$RegExp = '/^'. str_replace('/','-',trim($key,'/').'/').'/';

			if(preg_match($RegExp, $Request)){
				$Route[] = $value;
			}
		}
		if($getAll){
			// if user want all Routes than 
			// it will return in array of all routes
			return $Route;	
		}else{
			// if user want Execution routes
			// then it will return first route
			if(isset($Route[0])){
				return $Route[0];		
			}else{
				return false;	
			}
			
			
		}
		
	}

}