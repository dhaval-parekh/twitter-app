<?php
class User extends Controller{
	
	public function __construct(){
		parent::__construct();
		if(! isset($_SESSION['access_token'])){
			return false;	
		}
		$this->TwitterModal = $this->load_modal('twittermodal');
		
		$this->Url->setBaseurl(getBaseurl());
		$Header = $this->load_view('template/header');
		$Footer = $this->load_view('template/footer');
		$this->Template->setTemplate($Header,$Footer);
		
		$this->twitter = $this->load_modal('twittermodal'); //$this->load_library('twitter');
		$access_token = $_SESSION['access_token'];
		$this->twitter->setUp(TWEETER_CONSUMER_KEY,TWEETER_CONSUMER_SECRET,$access_token['oauth_token'],$access_token['oauth_token_secret']);
		
	}
	
	public function index(){
		$data = array();
		$data['user'] = isset($_SESSION['user'])?$_SESSION['user']:false;
		if(! $data['user']){
			$this->load_controller('index')->page_404();
			return false;
		}
		$user = false;
		
		$data['user_tweetes'] = $data['user_followers'] = array();
		$user['count'] = 10;
		$data['user_tweetes'] = $this->getUserTweete($user);
		$data['user_followers'] = $this->getUserFollowers($user);
		$this->Template->setContent($this->load_view('user',$data));
		$this->Template->render();
	}
	
	// ajax Handlers
	public function ajax_getSingleTweet($input){
		$response = $this->getUserTweete($input);
		return $response[0];
	}
	public function ajax_getUserTweets($input){

		$isHtml = isset($input['isHtml'])&&$input['isHtml']==1?true:false;
		$input['count'] = isset($input['count'])&&is_numeric($input['count'])?$input['count']:10;
		$user_tweetes = $this->getUserTweete($input);
		if($isHtml){
			$data = $this->load_view('widgets/tweet-slider',array('user_tweetes'=>$user_tweetes));
			return $data;
		}
		return $user_tweetes;
	}
	
	public function ajax_getUserFollowers($input){
		$isHtml = isset($input['isHtml'])&&$input['isHtml']==1?true:false;
		$input['count'] = isset($input['count'])&&is_numeric($input['count'])?$input['count']:10;
		$input['cache'] = true; // get data from cache if available
		$user_followers = $this->getUserFollowers($input);
		if($isHtml){
			$data = $this->load_view('widgets/user-follower',array('user_followers'=>$user_followers));
			return $data;
		}
		return $user_followers;
	}
	
	// Export Function 
	public function downloadTweets(){
		$input = $this->Input->Get();
		$format = isset($input['format'])?strtolower($input['format']):'json';
		$tweets = $this->getUserTweete($user);
		if(! count($tweets)){
			echo 'No tweets found';
			return false;
		}
		foreach($tweets as &$tweet){ unset($tweet['media']); }
		$header_type = array();
		$header_type['filename'] = 'tweets';
		$header_type['fileextension'] = 'json';
		$header_type['contenttype'] = 'application/json';
		
		$header_fields = array_keys($tweets[0]);
		$output = fopen('php://output', 'w');
		switch($format){
			case 'xml':
					$header_type['contenttype'] = 'application/xhtml+xml';
					$header_type['fileextension'] = 'xml';
					header("Content-Type: application/force-download");
					header('Content-Type: '.$header_type['contenttype'].'; charset=utf-8');
					header('Content-Disposition: attachment; filename='.$header_type['filename'].'.'.$header_type['fileextension'].'');
					$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><tweets></tweets>");
					//function call to convert array to xml
					array_to_xml($tweets,$xml_user_info,'tweet');
					$xml_file = $xml_user_info->asXML();
					fwrite($output,$xml_file);
					
				break;
			case 'csv':
					$header_type['contenttype'] = 'text/csv';
					$header_type['fileextension'] = 'csv';
					header("Content-Type: application/force-download");
					header('Content-Type: '.$header_type['contenttype'].'; charset=utf-8');
					header('Content-Disposition: attachment; filename='.$header_type['filename'].'.'.$header_type['fileextension'].'');
					fputcsv($output,$header_fields );
					foreach($tweets as $tweet){
						fputcsv($output,array_values($tweet));
					}
					
				break;
			case 'xls':
					$header_type['contenttype'] = 'application/vnd.ms-excel';
					$header_type['fileextension'] = 'xls';
					header("Content-Type: application/force-download");
					header('Content-Type: '.$header_type['contenttype'].'; charset=utf-8');
					header('Content-Disposition: attachment; filename='.$header_type['filename'].'.'.$header_type['fileextension'].'');
					$excel = $this->load_library('excel');
					$excel->setTitle($header_type['filename']);
					
					foreach($header_fields as $label){
						$excel->label($label);
						$excel->right();
							
					}
					$excel->down();
					$excel->home();
					foreach($tweets as $tweet){
						foreach($tweet as $label){
							if(is_numeric($label)){
								$excel->number($label);	
							}else{
								$excel->label($label);
							}
							$excel->right();
						}
						$excel->down();
						$excel->home();
					}
					
					$excel->send();
			
				break;
			case 'json':
			default:
					header("Content-Type: application/force-download");
					header('Content-Type: '.$header_type['contenttype'].'; charset=utf-8');
					header('Content-Disposition: attachment; filename='.$header_type['filename'].'.'.$header_type['fileextension'].'');
					fwrite($output,json_encode($tweets));
				break;
		}
		return true;
	}
	
	// Get User Tweet
	private function getUserTweete($input = array()){
		$screen_name = isset($input['screen_name'])?$input['screen_name']:false;
		if(! $screen_name){ $screen_name = $_SESSION['access_token']['screen_name']; }
		$params = $input;
		unset($params['screen_name']);
		if(isset($params['count']) && $params['count'] == -1){ unset($params['count']);  } 
		$user_twitte = $this->twitter->getUserTwitte($screen_name,$params);
		$response = array();
		
		foreach($user_twitte as $twitte){
			
			$single_twitte = array();
			$twitte = (array)$twitte;
			$single_twitte['created_at'] = $twitte['created_at'];
			$single_twitte['id'] = $twitte['id_str'];
			$single_twitte['card_url'] = getTweeterIcard($twitte['id']);
			$single_twitte['text'] = $twitte['text'];
			$single_twitte['source'] = $twitte['source'];
			$single_twitte['geo'] = $twitte['geo'];
			$single_twitte['coordinates'] = $twitte['coordinates'];
			$single_twitte['place'] = $twitte['place'];
			$single_twitte['retweet_count'] = $twitte['retweet_count'];
			$single_twitte['favorite_count'] = $twitte['favorite_count'];
			$single_twitte['favorited'] = $twitte['favorited'];
			$single_twitte['retweeted'] = $twitte['retweeted'];
			
			$media = array();
			$media['image'] = false;
			$media['audio'] = false;
			$media['video'] = false;
			$media['urls']  = false;
			
			// Image
			if(isset($twitte['entities']->media)){
				$images = (array) $twitte['entities']->media;
				$count = 0;
				foreach($images as $image){
					$image = (array) $image;
					$media['image'][$count]['id'] = $image['id'];
					$media['image'][$count]['url'] = $image['media_url_https'];
					$media['image'][$count]['display_url'] = $image['display_url'];
					$media['image'][$count]['type'] = $image['type'];	
					$count++;
				}
			}
			
			if(isset($twitte['entities']->urls)){
				$urls = (array) $twitte['entities']->urls;
				$count = 0;
				foreach($urls as $url){
					$url = (array) $url;
					$media['urls'][$count] = $url;
					$count++;
				}
			}
			
			$single_twitte['media'] = $media;
			$response[count($response)] = $single_twitte;
		}
		return $response;
	}
	
	private function getUserFollowers($input = array()){
		$screen_name = isset($input['screen_name'])?$input['screen_name']:false;
		if(! $screen_name){ $screen_name = $_SESSION['access_token']['screen_name']; }
		$params = $input;
		unset($params['screen_name']);
		if(isset($params['count']) && $params['count'] == -1){ unset($params['count']);  }
		$user_followers = $this->twitter->getUserFollowers($screen_name,$params);
		if(! $user_followers){ return false; }
		$user_followers = (array) $user_followers;
		$response = array();
		//$user_followers = isset($user_followers['users'])?$user_followers['users']:false;
		if($user_followers && count($user_followers)>0){
			foreach($user_followers as $follower){
				$follower = (array) $follower;
				if(isset($input['query'])){
					$pattent = '/'.strtolower($input['query']).'/'; 
					if(! preg_match($pattent,strtolower($follower['name']))){
						continue;	
					}
				}
				
				$single_user = array();
				
				$single_user['name'] = $follower['name'];
				$single_user['screen_name'] = $follower['screen_name'];
				$single_user['location'] = $follower['location'];
				$single_user['description'] = $follower['description'];
				$single_user['url'] = $follower['url'];
				$single_user['profile_pic'] = $follower['profile_image_url_https'];
				/*$single_user[''] = $follower[''];
				$single_user[''] = $follower[''];
				$single_user[''] = $follower[''];
				$single_user[''] = $follower[''];
				$single_user[''] = $follower[''];*/
				
				$response[count($response)] = $single_user;
			}
		}
		if(isset($params['count']) && is_numeric($params['count'])){
			$response = array_slice($response,0,$params['count']);
		}
		
		return $response;
	}
	
} 