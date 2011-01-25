<?php
include_once 'Smarty_Main.php';
require_once '../../library/sfDI/sfServiceContainerAutoloader.php';
class DisplayLogin
{

	protected $_body;
	protected $_smarty;
	protected $_tw;
	
	public function __construct()
	{
		$this->_smarty = new Smarty_Main();
		$this->_body = 'login.tpl';
		$this->_tw = $this->buildThemeWidget();		// call the module bootstrap function
				
		$this->_smarty->assign('environment',WORKING_ENV);
		//$this->_smarty->assign('rss',$this->_tw->retrieve($this->_tw::KEY__RSS_FEED));
		$this->_smarty->assign('rss',$this->_tw->retrieve('cachedRss'));
		$this->_smarty->display($this->_body);
	}
	
	protected function buildThemeWidget() {
		
		sfServiceContainerAutoloader::register();
		
		$container = new sfServiceContainerBuilder();
		$loader = new sfServiceContainerLoaderFileXml($container, array('config/default/'));
		$loader->load('./config/container_'.WORKING_ENV.'.xml');

		
		return $container->themeWidget;
	}
	
}