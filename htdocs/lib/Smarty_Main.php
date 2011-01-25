<?php

include_once('../../library/smarty/Smarty.class.php');

class Smarty_Main extends Smarty {
	
	public function __construct() {
		
		parent::__construct ();
			
		$this->template_dir = 'view/';
		$this->compile_dir  = 'smarty/templates_c/';
		$this->config_dir   = 'smarty/configs/';
		$this->cache_dir    = 'smarty/cache/';
		
		$this->debugging = false;
		$this->caching = false;
		$this->cache_lifetime = 120;		
	}
}

?>