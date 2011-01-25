<?php

//require_once '../../library/Zend/Loader/Autoloader.php';
//Zend_Loader_Autoloader::getInstance();

class ThemeWidgets
{
	// cache constant keys
	const KEY__RSS_FEED    = 'cachedRss';
    const KEY__DAILY_IMAGE = 'cachedImage';
    const KEY__LAST_CACHED_DATE = 'cachedDate';
	
	protected $VALID_WIDGET= array(
	    self::KEY__RSS_FEED,
	    self::KEY__DAILY_IMAGE,
	    self::KEY__LAST_CACHED_DATE
	);
	
	// instance variables
	// cacheName
	// sideimageDir
	// rssReader
	// cacheReader
	protected $_config = array();
	protected $_rssReader;
	protected $_cacheReader;
	
	public function __construct(Array $config = array()) {		
		$this->_config = $config;
	}
	
	/**
	 * Checks to see if the cache needs to be re-cahced. If it does not
	 * it will load the cache file. If it does it will first check to see
	 * if the site is active. If the site is _NOT_ active it will load the
	 * old cache if the site _IS_ active it will generate a new cache file.
	 * 
	 * @param $VALID_WIDGET $cachedItems
	 * 
	 * @return cached item asked for
	 */
	public function retrieve($cacheToLoad) {
		// not a vaild key
		if (!in_array($cacheToLoad,$this->VALID_WIDGET)) {
			return null;
		}
		
		// Check to see if a cache file already exists, as well it has not expired
		if($this->_config['cacheReader']->load($this->_config['cacheName'])) {
			$savedCache = $this->_config['cacheReader']->load($this->_config['cacheName']);
			return $savedCache[$cacheToLoad];
		} 
		// site is up and active
		else {	
			$date = date("Y-M-d H:i:s",time());	
			
			$rss = $this->_config['rssReader']->getRss();
			
			$image = self::getRandomImage($this->_config['sideimageDir']);
			
			// save the cache
			$this->_config['cacheReader']->save(array('cachedDate'=>$date,'cachedRss'=>$rss,'cachedImage'=>$image),$this->_config['cacheName']);
			
			// return the cache
			$savedCache = $this->_config['cacheReader']->load($this->_config['cacheName']);
			return $savedCache[$cacheToLoad];
		}
		
		return null;	// empty return value
	}
	
	
	
	/**
	 * 
	 * @param str $imageFolder - path to images
	 * @return str of random image
	 */
	protected function getRandomImage($imageFolder) {
		$images = array();
		
		foreach(glob($imageFolder.'/{*.jpg,*.gif,*.png}', GLOB_BRACE) as $image)
		{
			$images[] = $image;
		}
		
		$rand = array_rand($images,1);
		
		// split the path
		$path = explode("/",$images[$rand]);
		
		//return $images[$rand];
		return $path[sizeof($path)-1];
	}
	
	
}