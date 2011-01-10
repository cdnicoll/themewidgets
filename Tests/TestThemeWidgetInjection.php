<?php
require_once('../Bootstrap.php');
require_once '../../library/sfDI/sfServiceContainerAutoloader.php';

sfServiceContainerAutoloader::register();

class ThemeWidgetsInjectionTest extends PHPUnit_Framework_TestCase
{
	// To describe services with PHP, you can use the sfServiceContainerBuilder class
	private $container;
	
	public function setUp() 
	{
		$this->container = new sfServiceContainerBuilder();	
	}
}