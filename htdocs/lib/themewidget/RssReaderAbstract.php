<?php

abstract class RssReaderAbstract {
	
	// url
	// maxArticles
	protected $_config;
	protected $_rss = array();
	
	public function getRss() {
		return $this->_rss;
	}
	
	public function setRss(array $rss) {
		$this->_rss = $rss;
	}
	
	public abstract function createRssObject($feed);
	public abstract function isUrlActive($url);
}

?>