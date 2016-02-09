<?php
class Modal {
	// Instans of database
	protected $Database;
	public function __construct($con = false ){
		if(! $con || ( $con instanceof sqlLite_DatabaseAccess) ){ // check if this is object of sqlLite_DatabaseAccess Class 
			//	if Not than load default database
			$this->Database = new sqlLite_DatabaseAccess(DATABASE,DB_SCRIPT_PATH);
		}else{
			// if yes then load requested  database
			$this->Database = $con;
		}
		$this->Database->Open();
	}
	protected function set_db($con){
		self::__construct($con);
	}
	
	public function ResourceToArray($resource){
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
	
	// To load Modal File
	protected function load_modal($modal , $con = false){
		$ModalFile = DIR_MODAL.DS.$modal.'.php';
		if(file_exists($ModalFile)){ // Check File is available
			require_once($ModalFile);	// Import file
			if(class_exists($modal)){	// Check if Modal Class avialable or not
				
				$Modal = new $modal($con);
				return $Modal;
			}else{
				return false;	
			}
		}else{
			return false;
		}	
	}
	
	public function custom($query){
		return $this->Database->ExecuteQuery($query);
	}
	
	public function customNone($query){
		return $this->Database->ExecuteNoneQuery($query);
	}
	
	public function __destruct(){
		//$this->Database->Close();	
	}
}