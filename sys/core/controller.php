<?php
class Controller{
	
    /*
    protected $Input;
	public $Form;
	protected $Url;
    protected $Datatable;
    protected $File;
    protected $Template;
    
    public function __construct(){
        $this->Input = new Input();
        $this->Form = new Form();
        $this->Url = new Url();
        $this->Datatable = new Datatable();
        $this->File = new File();
        $this->Template = new Template();
        
    }
    */
	public function __construct(){
		// load classes form library class
		$autoload = array(
						'Input'=>'input',
						'Form'=>'form',
						'Url'=>'url',
						'Datatable'=>'datatable',
						//'File'=>'file',
						'Template'=>'template',
					);
		$this->autoload($autoload);
	}
	
	protected function autoload($autoload_class){
		if(is_object($autoload_class)){
			$autoload_class = (array) $autoload_class;	
		}
		if(is_array($autoload_class)){
			foreach($autoload_class as $var=>$class){
				$file = DIR_SYS.DS.'classes'.DS.'class.'.$class.'.php';
				if(file_exists($file)){
					require_once($file);	
					if(class_exists($class)){
						// class is loaded
						$this->$var = new $class();
					}
				}
			}
		}
		return false;
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
	
	// To load Controller File 
	protected  function load_controller($controller){
	    
	    $ControllerFile = DIR_CONTROLLER.DS.$controller.'.php';
	    if(file_exists($ControllerFile)){ // Check File is available
	        
	        require_once($ControllerFile);	// Import file
	        if(class_exists($controller)){	// Check if Controller Class avialable or not
	            $Controller = new $controller();
	            return $Controller ;
	        }else{
	            return false;
	        }
	    }else{
	        return false;
	    }
	}
	
	protected function load_view($view,$data = false ){
		$view = trim($view,'/');
		$File = DIR_VIEW.DS.$view.'.php';
		if(file_exists($File)){
			//$content = include $File;
			if(is_object($data)){
				$data = (array)$data;	
			}
			if(is_array($data)){
				extract($data);
			}
			ob_start();
			include $File;
			$content = ob_get_contents();
			ob_end_clean();
			$content = str_replace('	','',$content);
			return $content;
		}else{
			return false;	
		}	
	}
	
	protected function load_library($Lib){
		$LibFile =DIR_SYS.DS.'classes'.DS.'class.'.$Lib.'.php';
		if(file_exists($LibFile)){
			require_once($LibFile);
			
			if(class_exists($Lib)){
				$Object = new $Lib();
				return $Object;	
			}else{
				return false;	
			}
		}else{
			return false;	
		}
	}
	
	protected function ResourceToArray($resource){
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
}