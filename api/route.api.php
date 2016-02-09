<?php
$api_route = array();
$api_route['index'] = new ApiRequest(
							'index/api_index',
							'POST',
							false, //'application/json',
							array(
								//'uid'=>array('type'=>'number','required'=>true)
							)
						);
						
$api_route['getsingletweet'] = new ApiRequest(
							'user/ajax_getSingleTweet',
							'POST',
							false, //'application/json',
							array(
								'id'=>array('type'=>'number','required'=>true)
							)
						);
$api_route['getusertweets'] = new ApiRequest(
							'user/ajax_getUserTweets',
							'POST',
							false, //'application/json',
							array(
								'screen_name'=>array('type'=>'text','required'=>true),
								'isHtml'=>array('type'=>'number','required'=>false)
							)
						);
						
$api_route['getuserfollowers'] = new ApiRequest(
							'user/ajax_getUserFollowers',
							'POST',
							false, //'application/json',
							array(
								'screen_name'=>array('type'=>'text','required'=>true),
								'isHtml'=>array('type'=>'number','required'=>false)
							)
						);