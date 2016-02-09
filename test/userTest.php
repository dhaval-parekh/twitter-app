<?php

class User_test extends Controller{
	
	public function __construct(){
		parent::__construct();
		$this->user = $this->load_modal('twittermodal');
		$this->user->setUp(TWEETER_CONSUMER_KEY,TWEETER_CONSUMER_SECRET,TWEETER_ACCESS_TOKEN,TWEETER_ACCESS_TOKEN_SECRET);
	}
	
	public function getUserTweete($screen_name,$request){
		return $this->user->getUserTwitte($screen_name,$request);
	}
	
	public function getUserFollower($screen_name,$request){
		return $this->user->getUserFollowers($screen_name,$request);
	}
}

class UserUnitTest extends \PHPUnit_Framework_TestCase { //  Controller{ //
	
	private $screen_name;
	private $reuqest;
	private $user;
	public function __construct(){
		$this->user = new User_test();
		$this->screen_name = 'themepunch';
		$this->reuqest = array(
			'screen_name' => $this->screen_name,
			'cache'=>true
		);
	}
	
	public function test_getTweetes(){
		$this->user->getUserTweete($this->screen_name,$this->reuqest);
	} 
	
	public function test_getFollower(){
		$this->user->getUserFollower($this->screen_name,$this->reuqest);
	}
	
}