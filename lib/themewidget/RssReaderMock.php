<?php
require_once ('RssReaderAbstract.php');
class RssReaderMock extends RssReaderAbstract
{
	private $_url;
	
	public function __construct($url) {
		
	}
	
	public function getRss() {
		
		$feed1 = array (
			"title" => "Test Title 1",
			"description" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
			"link" => "http://example1.com",
			"pubDate" => 'January 17,2011'
		);
		
		$feed2 = array (
			"title" => "Test Title 2",
			"description" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
			"link" => "http://example2.com",
			"pubDate" => 'January 17,2011'
		);
		
		$feed3 = array (
			"title" => "Test Title 3",
			"description" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
			"link" => "http://example3.com",
			"pubDate" => 'January 17,2011'
		);
		
		return array($feed1, $feed2, $feed3);
	}
	
	public function createRssObject($feed) {
		
	}
	
	public function isUrlActive($url) {
	
	}

}