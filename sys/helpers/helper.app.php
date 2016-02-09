<?php
/*function validateParam($requvired,&$args){
	$flag = false;
	
	foreach($requvired as $key=>$value){
		
		if(! array_key_exists($value[0],$args)){
			return false;
		}else{
			$args[$value[0]] = urldecode($args[$value[0]]);
			
			if(! _isParamValid($args[$value[0]],$value[1])){
				return false;	
			}
		}
	}
	return true;
}

function ResourceToArray($resource){
	if(gettype($resource)=="resource" && $resource){
		$array = array();
		while($row = mysql_fetch_assoc($resource)){
			$array[] = $row;
		}
		return $array;
	}else{
		return false;	
	}
}
function _isParamValid($value,$type){
	if(empty($value)){ return false; }
	if($value){
		switch($type){
			case 'text':
			case 'password':

				break;
			case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL) ):
						return false;
					endif;
				break;
			case 'tel':
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
*/

function getTweeterIcard($tweet_id){
	$url = 'https://twitter.com/i/cards/tfw/v1/'.$tweet_id;
	return $url;
}


function array_to_xml($array, &$xml_user_info,$item_name = 'item') {
	foreach($array as $key => $value) {
		if(is_array($value)) {
			if(!is_numeric($key)){
				$subnode = $xml_user_info->addChild("$key");
				array_to_xml($value, $subnode);
			}else{
				$subnode = $xml_user_info->addChild($item_name."$key");
				array_to_xml($value, $subnode);
			}
		}else {
			$xml_user_info->addChild("$key",htmlspecialchars("$value"));
		}
	}
}