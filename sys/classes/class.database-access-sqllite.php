<?php
/**
 *	Class Name : SQLite DatabaseAccess 
 *	Use 		 : To Access SQLite Database
 *	Counstruct : 	@param 1 = Database
 *	
 *	
 *	
 *
 *
 */
class sqlLite_DatabaseAccess
{
		private $_database;
		private $_db_script;
		private $_connection;
		
		//	Construct
		public function __construct($database,$script = false){
			$this->_database = $database;
			$this->setup($script);
			$this->_connection = false;
			//$this->Open();
		}
		
		
		
		public function setup($script_file){
			if($script_file && file_exists($script_file)){
				$script = file_get_contents($script_file);
				$connection_flag = $this->_connection;
				if(! $connection_flag){ $this->Open(); }
					$respons = $this->ExecuteNoneQuery($script);
				if(! $connection_flag){ $this->Close(); }
				return true;
			}
			return false;
		}
		
		//	Connection Open
		public function Open(){
			$this->_connection = new SQLite3($this->_database);
		}
		
		//	Connnection Close
		public function Close(){
			unset($this->_connection);
		}
		
		//	Connnection Error
		public function getError(){
			return false;
		}
		
		//	Re Connect Connnection
		public function Reconnect(){
			$this->Close();
			$this->Open();
		}
		
		//	Execute None Query 
		public function ExecuteNoneQuery($query){
			$result = $this->_connection->query($query);
			return $this->_connection->changes();
		}
		
		//	Execute Query
		public function ExecuteQuery($query){
			$results = $this->_connection->query($query);
			$response = array();
			while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
				$response[] = $row;
			}
			return $response;
		}
		
		//	Get First Value From Query or  Result Set
		public function getFirstValue($resource){
			if(!(gettype($resource)=="object" && $resource)){
				$ResultSet = $this->ExecuteQuery($resource);
			}else{
				$ResultSet = $resource;	
			}
			if($ResultSet && count($ResultSet) > 0){
				$ResultSet = $ResultSet[0];
				reset($ResultSet);
				$first_key = key($ResultSet);
				if(isset($ResultSet[$first_key])){
					return $ResultSet[$first_key];
				}
			}
			return false;
		}
		
		//	Name : getRow 
		//	Use : Get The No. of row form Query or Result Set
		//	@param 1 : Resource [ query_string || result_set ]
		//	@param 2 : No [ No. of Row ] 
		public function getRow($resource ,$No = 0){
			if(!(gettype($resource)=="object" && $resource)){
				$ResultSet = $this->ExecuteQuery($resource);
			}else{
				$ResultSet = $resource;	
			}
			if($ResultSet && count($ResultSet) > $No){
				return $ResultSet[$No];
			}
			return false;
			
		}
		/*
		public function beginTransaction(){
			return $this->ExecuteNoneQuery('START TRANSACTION');
		}
		//	get last Affected Row
		public function getLastAffectedRow(){
			return mysql_affected_rows();	
		}
		
		// Set Auto Commit
		public function setAutoCommit($flag =true){
			if($flag){
				return $this->ExecuteNoneQuery('SET AUTOCOMMIT=1');	
			}else{
				return $this->ExecuteNoneQuery('SET AUTOCOMMIT=0');
			}
		}
		
		//COMMIT
		public function Commit(){
			return $this->ExecuteNoneQuery('COMMIT');	
		}
		
		//ROLLBACK
		public function Rollback(){
			return $this->ExecuteNoneQuery('ROLLBACK');	
		}*/
} 