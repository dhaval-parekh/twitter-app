<?php
class ApiRequest{
	
	protected $controller;
	protected $action;
	protected $AllowMethod;
	protected $AllowType;
	protected $params;
	
	private $Input;
	private $Response;
	
	static private $response_codes = array(
        200 => 'OK',
	   201 => 'Created',
	   204 =>	'No Content',
	   304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Authentication Failed',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
	   405 => 'Method Not Allowed',
	   406 => 'Not Acceptable',
	   420 => 'Method Failure',
	   409 => 'Conflict',	
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
	   // System Problem
	   600 => 'System Problem',
	   601 => 'Controller File Not Found',
	   602 => 'Controller Not Found',
	   603 => 'Method Not Found',
	   604 => 'Invalid Token',
	   605 => 'Email Address or Phone Already Registerd',
	   606 => 'User is not Registed',
	   607 => 'Cannot create user',
	   608 => 'Session has been Expired',
	   609 => 'Invalid Signature',
	   610 => 'Source IP not Valid',
	   611 => 'Product not available',
	   612 => 'Amount Mismatch' ,
	   613 => 'Data not valid',

    );

	public function __construct($action,$allow_metod,$allow_type,$param = array()){
		$action = trim(trim($action,' '),'/');
		$action = explode('/',$action);
		$this->controller = $action[0];
		$this->action = $action[1];
		$this->AllowMethod = $allow_metod;
		$this->AllowType = $allow_type;
		$this->params = $param;
		
		// Input Controller 
		$this->Input = new Input();
	
	}
	
	private function setResponse($code,$payLoad = false){
		$Response = array();
		if(isset(self::$response_codes[$code])){
			$Response['status'] = $code;
			$Response['message'] = self::$response_codes[$code];
			header('HTTP/1.1 '.$code.' '.self::$response_codes[$code].'');
			if($payLoad){
				$Response['payload'] = $payLoad;	
			}
			$this->Response = $Response;
			return true;
		}else{
			return false;	
		}
		
	}
	
	//	Set up the incomming value in the params
	private function _setParamsValue(){
		$Values = $this->Input->getInputValue();
		
		foreach($this->params as $key=>$Param){
			$this->params[$key]['value'] = isset($Values[$key])?$Values[$key]:false;
		}
		return true;
	}
	
	// Check the individule parameter according to their type and value or required
	private function _isParamValid( $param){
		if($param['required'] &&  (!$param['value']) ){
			return false;	
		}else{
				
		}
		
		//display($param);
		if($param['value']){
			$value = $param['value'];
			
			switch($param['type']){
				case 'text':
				case 'password':
						
					break;
				case 'email':
						if(!filter_var($value, FILTER_VALIDATE_EMAIL) ):
							return false;
						endif;
					break;
				case 'tel':
						$value = ltrim($value,'+');
						$value = trim($value,' ');
						if((!is_numeric($value)) ):
							return false;
						elseif(! preg_match("/^[0-9]{7,15}$/", $value)):
						
							return false;
						endif;
						
					break;
				case 'number':
						
						if((!is_numeric($value)) ):
							return false;
						endif;
						break;
				case 'date':
						$d = date_parse_from_format("m-d-Y", $value);
						if(!checkdate($d['month'],$d['day'],$d['year'])):
							return false;
						endif;
					break;
				case 'checkbox':
						if(is_array($value) && count($value)<=0):
							return false;
						endif;
					break;
				case 'latitude':
							if((! is_numeric($value))):
								return false;	
							elseif( $value < -90 || $value > 90):
								return false;
							endif;
							
						break;
				case 'longitude':
							if((! is_numeric($value))):
								return false;	
							elseif( $value < -180 || $value > 180):
								return false;
							endif;
						break;
				case 'file':

						if($param['value']['error']){
							return false;	
						}
					break;
			}		
		}
		return true;
	}
	
	// Validate the all parameter of request 
	//	Depedant on the $this->_isParamValid()	function 
	private function _validateParams(){
		$this->_setParamsValue();
		
		if( isset($this->params) && is_array($this->params)){
			
			foreach($this->params as $param){
				
				$ParamFlag = $this->_isParamValid($param);
				if(! $ParamFlag){
						// Param Debug	
					
					return false;
				}else{
					// true	
				}
			}
			return true;
		}
		return false;
	}
	
	//	 IT will Validate Whole Request including the parameter
	public function ValidateRequest(){

		if( $this->Input->getMethod() != strtoupper($this->AllowMethod)){
			// Invalid Method
			$this->setResponse(405);
			return false;
		}

		if($this->AllowType){
			if(  strtolower($this->Input->getContentType()) != strtolower($this->AllowType)){
			// Invalid Content type
				$this->setResponse(406);
				return false;
			}
		}
		if(! $this->_validateParams()){
			$this->setResponse(400);
			return false;
		}

		return true;
	}
	
	public function set_array_nil( &$array){
		foreach($array as $key=>$row){
			if(is_array($array[$key])){ $this->set_array_nil($array[$key]); }
			if(empty($array[$key]) && ($array[$key] !=0) ){ $array[$key] = 'NIL'; }
		}
		return $array;
	}
	
	public function ExecuteParam(){
		$ControllerFile = DIR_CONTROLLER.DS.$this->controller.'.php'; 
		$ControllerFile = file_exists($ControllerFile)?$ControllerFile:false;
		
		if(file_exists($ControllerFile)){	// Check Controller file exists or not
			
			require_once($ControllerFile); // Include Control class file
			
			if(class_exists($this->controller)){	// Check Controller Class Exists or not
				
				$Controller = new $this->controller; // Create Controller class new object
				
				if(method_exists($Controller,$this->action)){ // Check The Controller class has request Method or not
					//	Calling of fucntion of controller class
					
					// Main Executation
					$action = $this->action;
					$input = $this->Input->getInputValue();
					$Result = $Controller->$action($input);
					
						if(is_array($Result) && array_key_exists('error',$Result)){
							$this->setResponse($Result['error']);
						}else{
							if(is_array($Result)){
								$this->set_array_nil($Result);
							}else{
								$Result = empty($Result)?'NIL':$Result;
							}
							$this->setResponse(200,$Result);
						}
						return true;
				}else{
					// Controller class does not have function
					$this->setResponse(603);
					return false; 
				}	
			}else{
				// Controller class not exits
				$this->setResponse(602);
				return false; 
			}
		}else{
			// Controller File not found 
			$this->setResponse(601);
			return false; // 404	
		}
	}
	
	public function Response($formate = 'json'){
		//display($this->Response);
		if($this->ValidateRequest()){
			$this->ExecuteParam();
			
		}
	
		switch($formate){
			
			case 'json':
			default:
				header('Content-type: application/json');
				return json_encode($this->Response);
			break;
		}	
	}
	
	// Getter Function 
	
	public function getParam(){
		return $this->params;	
	}
	public function getMethod(){
		return $this->AllowMethod;
	}
	public function getContentType(){
		return $this->AllowType;	
	}
	
}