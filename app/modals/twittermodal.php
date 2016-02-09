<?php

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterModal extends Modal{
	
	protected $twitter;
	protected $request_token;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function setUp($consumer_key,$counsumer_secret,$access_token,$access_token_secret){
		try{
			$this->twitter = new TwitterOAuth($consumer_key, $counsumer_secret, $access_token, $access_token_secret);
			//return true;
			$content = $this->twitter->get("account/verify_credentials");
			if($content){
				return $content;	
			}
		}catch(Abraham\TwitterOAuth\TwitterOAuthException $e){
			system_log($e);
			return false;
			//display($e);
		}catch(Execption $e){
			system_log($e);
			return false;
		}
		return false;
	}	
	
	public function requestOauth($callback){
		if(! $this->twitter){ return false; }
		$request_token = $this->twitter->oauth('oauth/request_token', array('oauth_callback' => $callback));
		$this->token = $request_token;
		return $this->token;
	}
	
	public function getLoginUrl(){
		$url = $this->twitter->url('oauth/authorize', array('oauth_token' => $this->token['oauth_token']));
		return $url;
	}
	
	public function getUser(){
		return $this->twitter->get("account/verify_credentials");	
	}
	
	public function getAccessToken($oauth_verifier){
		 return $this->twitter->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));	
	}
	
	public function getUserTwitte($screen_name,$params = array()){
		$params['screen_name'] = $screen_name;
		return $this->twitter->get('statuses/user_timeline', $params );	
	}
	
	public function getUserFollowers($screen_name,$params = array()){
		$params['screen_name'] = $screen_name;
		$query = "SELECT * FROM usermaster WHERE screen_name = '".$screen_name."'; ";
		$user_data = $this->Database->getRow($query);
		if($user_data){
			//$user_data['followers_lastupdate'] = date('Y-m-d H:i:s');
			$expire_date = new DateTime($user_data['followers_lastupdate']);
			$expire_date->add(new Dateinterval('PT'.CACHE_EXPIRY_TIME.'S'));
			$expire_date = (array)$expire_date;
			$expire_date  = $expire_date['date'];
			$current_date = date('Y-m-d H:i:s');
			if($current_date <= $expire_date || (isset($params['cache']) && $params['cache'])){
				return json_decode($this->decrypt($user_data['followers'],PASSWORD,SALT),true);
			}
		}
		$request_limit = 100;
		$followers = array();
		$follower_ids = (array) $this->twitter->get('followers/ids', array('screen_name' => $screen_name)); // to get ids "followers/ids"
		
		if(isset($follower_ids['errors'])){
			system_log('Funtion : '.__FUNCTION__.' | LINE : '.__LINE__.' | File : '.__FILE__);
			system_log($follower_ids);
			return false;
		}
		
		
		$follower_ids = $follower_ids['ids'];
		$slot_count = ceil(count($follower_ids)/$request_limit);
		if($slot_count > 15){$slot_count = 15; }
		for($i=1;$i<=$slot_count;$i++){
			$slot = array(); 
			$slot['end_id'] = $i*100;
			$slot['start_id'] = $slot['end_id']-99;
			$slot['ids'] = array_slice($follower_ids,$slot['start_id'],$request_limit);
			$slot['params'] = array();
			$slot['params']['user_id'] = '';
			$slot['params']['user_id'] = implode(',',$slot['ids']);
			
			$slot['followers'] = (array) $this->lookup($slot['params']);
			if(isset($slot['followers']['errors'])){  
				system_log('Funtion : '.__FUNCTION__.' | LINE : '.__LINE__.' | File : '.__FILE__);
				system_log($slot['followers']);
				continue;
			}
			$followers = array_merge($followers,$slot['followers']);
		}
		
		// put user followe list in database for cache
		$user_data['followers'] = json_encode($followers);
		$user_data['followers'] = $this->encrypt($user_data['followers'],PASSWORD,SALT);
		$user_data['followers_lastupdate'] = false; // it will auto update it with current
		$this->setUserData($screen_name,$user_data);
		
		return $followers;

	}
	public function searchUser($screen_name,$params = array()){
		return $this->twitter->get('users/search', $params); 
	}
	
	public function lookup($params = array()){
		return $this->twitter->post('users/lookup', $params); 
	}
	
	
	
	
	// Private function 
	private function setUserData($screen_name, $data = array()){
		if(! (isset($screen_name) && $screen_name && !empty($screen_name))){ return false; }
		$query = "SELECT count(1) FROM usermaster WHERE screen_name = '".$screen_name."'; ";
		$row = $this->Database->getRow($query);
		
		if(isset($row) && $row['count(1)'] != 0){
			// update code 
			$can_udpate = false;
			$query  = "UPDATE usermaster SET ";
			
			if(isset($data['tweets'])){
				$query .= $can_udpate?' , ':'';
				$data['tweets'] = mysql_real_escape_string($data['tweets']);
				$query .= " tweets = '".$data['tweets']."' ";
				$can_udpate = true;
				$data['tweets_lastupdate'] = isset($data['tweets_lastupdate'])&&$data['tweets_lastupdate']?$data['tweets_lastupdate']:date('Y-m-d H:i:s');
			}
			
			if(isset($data['tweets_lastupdate'])){
				$query .= $can_udpate?' , ':'';
				$query .= " tweets_lastupdate = '".$data['tweets_lastupdate']."' ";
				$can_udpate = true;
			}
			
			if(isset($data['followers'])){
				$query .= $can_udpate?' , ':'';
				$data['followers'] = mysql_real_escape_string($data['followers']);
				$query .= " followers = '".$data['followers']."' ";
				$can_udpate = true;
				$data['followers_lastupdate'] = isset($data['followers_lastupdate'])&&$data['followers_lastupdate']?$data['followers_lastupdate']:date('Y-m-d H:i:s');
			}
			
			if(isset($data['followers_lastupdate'])){
				$query .= $can_udpate?' , ':'';
				$query .= " followers_lastupdate = '".$data['followers_lastupdate']."' ";
				$can_udpate = true;
			}
			
			$query .= "WHERE screen_name = '".$screen_name."'; ";
			
			if(! $can_udpate){ return false; }
		}else{
			// insert code  	
			$data['tweets'] = isset($data['tweets'])&&$data['tweets']?mysql_real_escape_string($data['tweets']):'';
			$data['tweets_lastupdate'] = isset($data['tweets_lastupdate'])&&$data['tweets_lastupdate']?$data['tweets_lastupdate']:date('Y-m-d H:i:s');
			$data['followers'] = isset($data['followers'])&&$data['followers']?mysql_real_escape_string($data['followers']):'';
			$data['followers_lastupdate'] = isset($data['followers_lastupdate'])&&$data['followers_lastupdate']?$data['followers_lastupdate']:date('Y-m-d H:i:s');
			
			$query = "INSERT INTO usermaster (screen_name,tweets,tweets_lastupdate,followers,followers_lastupdate) VALUES
					('".$screen_name."','".$data['tweets']."','".$data['tweets_lastupdate']."','".$data['followers']."','".$data['followers_lastupdate']."'); ";
		}
		$response = $this->Database->ExecuteNoneQuery($query);
		return $response;
	}
	
	private function getUserData($screen_name){
		if(! (isset($screen_name) && $screen_name && !empty($screen_name))){ return false; }
		$query = "SELECT * FROM usermaster WHERE screen_name = '".$screen_name."'; ";
		$row = $this->Database->getRow($query);
		return $row;
	}
	
	private function deleteUserData($screen_name){
		if(! (isset($screen_name) && $screen_name && !empty($screen_name))){ return false; }
		$query = "DELETE FROM usermaster WHERE screen_name = '".$screen_name."'; ";
		return $this->Database->ExecuteNoneQuery($query);
		
	}
	
	private function encrypt($decrypted, $password, $salt=']w!#D{x3#)s&*f@#$') {
		$key = hash('SHA256', $salt . $password, true);
		srand(); 
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
		if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
		return $iv_base64 . $encrypted;
	}
	
	private function decrypt($encrypted, $password, $salt=']w!#D{x3#)s&*f@#$') {
		$key = hash('SHA256', $salt . $password, true);
		$iv = base64_decode(substr($encrypted, 0, 22) . '==');
		$encrypted = substr($encrypted, 22);
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
		$hash = substr($decrypted, -32);
		$decrypted = substr($decrypted, 0, -32);
		if (md5($decrypted) != $hash) return false;
		return $decrypted;
	}
	
}