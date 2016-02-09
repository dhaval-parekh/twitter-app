<?php
class Template{
	private $before_content;
	private $content;
	private $after_content;
	private $callback;
	
	public $isAjaxCall;
	
	public function __construct(){
		$this->callback = false;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			/* special ajax here */
			//alert('Ajax Call');
			$this->isAjaxCall = true;
		}else{
			$this->isAjaxCall = false;
			//alert('Normal Call');	
		}
		
	}
	
	public function setTemplate($header = false,$footer = false){
		if($header){
			$header = str_replace('	','',$header);
			$this->before_content = $header;
		}
		if($footer){
			$footer = str_replace('	','',$footer);
			$this->after_content = $footer;	
		}
		return true;
	}
	
	public function setContent($content = false){
		if($content){
			$content = str_replace('	','',$content);
			$this->content = $content;
			return true;
		}else{
			return false;	
		}
	}
	public function getContent(){
		return $this->content;	
	}
	
	public function getHtml(){
		if($this->isAjaxCall){
			return $this->content;
		}else{
			$content = $this->before_content.$this->content.$this->after_content;
			return $content;
		}
		
	}
	
	public function setCallback($function){
		$this->callback = $function;
	}
	
	public function render(){
		
		echo $this->getHtml();
		if($this->callback){
			call_user_func($this->callback);
		}
		return true;
	}
}