<?php 
class Index extends Controller{
	
	public function __construct(){
		parent::__construct();
		// Load Modals 
		$this->Url->setBaseurl(getBaseurl());
		
		$Header = $this->load_view('template/header');
		$Footer = $this->load_view('template/footer');
		$this->Template->setTemplate($Header,$Footer);
		
		$this->twitter =  $this->load_modal('twittermodal'); //$this->load_library('twitter');
		$this->twitter->setUp(TWEETER_CONSUMER_KEY,TWEETER_CONSUMER_SECRET,TWEETER_ACCESS_TOKEN,TWEETER_ACCESS_TOKEN_SECRET);
		
	}
	// Home Page
	public function index(){
		$data['twitter_login_url'] = rtrim(getBaseurl(),'/').'/login/';
		$this->Template->setContent($this->load_view('login',$data));
		$this->Template->render();
	}
	
	public function login(){
		$data = array();
		$this->Url->redirect($this->getLoginUrl());
		return true;
	}
	public function logout(){
		$_SESSION = array();
		unset($_SESSION);
		session_destroy();
		
		$this->Url->redirect(getBaseurl());
	}
	
	public function authUser(){
		$input = $this->Input->Get();
		$request_token = isset($_SESSION['token'])?$_SESSION['token']:false;

		if(! (isset($input['oauth_token'],$input['oauth_verifier']) &&  $request_token['oauth_token'] == $input['oauth_token']) ){
			$this->Url->redirect(getBaseurl());
			return false;
		}
		$this->twitter->setUp(TWEETER_CONSUMER_KEY, TWEETER_CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
		$access_token = $this->twitter->getAccessToken($input['oauth_verifier']);
		$_SESSION['access_token'] = $access_token;
		
		// Get user Data
		$user = $this->twitter->setUp(TWEETER_CONSUMER_KEY, TWEETER_CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		//$user = $this->twitter->getUser(); // redundant
		$_SESSION['user'] = (array) $user;
		
		$redirect_url = getBaseurl();
		$this->Url->redirect($redirect_url);
		return false;
	}
	
	public function about(){
		$this->Template->setContent($this->load_view('about'));
		$this->Template->render();
	}
	
	// Helper Function 
	private function getLoginUrl(){
		$callback = rtrim(getBaseurl(),'/').'/authuser/';
		$token = $this->twitter->requestOauth($callback);
		$_SESSION['token'] = $token;
		$url = $this->twitter->getLoginUrl();
		return $url;
	}
	
	
	public function page_404(){
		header('HTTP/1.1 404 Page Not Found');
		$this->Template->setContent('<h1 class="margin-top-lg text-theme text-center">404</h1>');
		$this->Template->render();
	}
}