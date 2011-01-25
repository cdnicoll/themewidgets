<?php

require_once ('RssReaderAbstract.php');

class RssReader extends RssReaderAbstract {
	
	
	
	public function __construct(array $config = array()) {
		
		$this->_config = $config;		
		
		$url = $this->_config['url'];
		
		if (!$this->isUrlActive($url)) {
			throw new Exception("Bad RSS Feed URL");
		}
		
		$this->_rss = $this->createRssObject(new Zend_Feed_Rss($url));
	}
	
	
	
	public function createRssObject($feed) {
		// get the rss feed
		$rss_arr = $feed;

		$index = 0;
		$rss_array = array();
		
		foreach($rss_arr as $rss) {
			if ($index < $this->_config['maxArticles']) {
				$rss_array[$index]['title'] = $rss->title();
				$rss_array[$index]['description'] = $rss->description();
				$rss_array[$index]['link'] = $rss->link();
				$rss_array[$index]['pubDate'] = date("F d, Y",strtotime($rss->pubDate()));
			}
			$index++;
		}

		return $rss_array;
	}
	
	/**
	 * Check if the url is active
	 * @param string the url to check
	 * @return bool (true) if it is active
	 */
	public function isUrlActive($url) {
		$connection = @fopen($url, 'r'); // @suppresses all error messages
		if($connection) {
			fclose($connection);
			return true;
		}
		return false;
	}
}

?>