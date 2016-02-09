<?php
class DatabaseTest extends PHPUnit_Framework_TestCase{
	
	public function __construct(){
		
	}
	
	public function test_DBInit(){
		$connection = new sqlLite_DatabaseAccess(DATABASE,DB_SCRIPT_PATH);
		$connection->Open();
		//$this->assertEquals(1,file_exists(DATABASE));
		
		/*$data = array(
			'screen_name'=>'nicolash_smith',
			'tweets' => 'User Tweetes will stored here ',
			'tweets_lastupdate'=>date('Y-m-d H:i:s'),
			'followers' => "User Follower will store here  ",
			'followers_lastupdate'=>date('Y-m-d H:i:s'),
		);
		$query = "SELECT * FROM usermaster WHERE screen_name = '".$data['screen_name']."'; ";
		$record = $connection->getRow($query);
		if($record){
			
		}
		print_r($connection->getRow($query));
		print_r($data);
		$query = "INSERT INTO usermaster VALUES ('".$data['screen_name']."','".$data['tweets']."','".$data['tweets_lastupdate']."','".$data['followers']."','".$data['followers_lastupdate']."');";
		//$this->assertEquals(1,$connection->ExecuteQuery($query));
		*/
	}	
	
	
	
	
}