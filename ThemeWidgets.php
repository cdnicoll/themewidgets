<?php

//require_once '../../library/Zend/Loader/Autoloader.php';
//Zend_Loader_Autoloader::getInstance();

class ThemeWidgets
{
	// Constants for RSS feed	
	const CACHE_NAME = 'CacheFile';
	const RSS_REFRESH_TIME = 10;									// refresh time on cache (22 hours (79200))
	const MAX_RSS_ARTICLES = 2;										// number of articles to pull	
	//
	const SIDEBAR_IMAGES = './images/sidebar-images/';
	const TMP_PATH = '../../../../files/localtests-tmp/';
	
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
	protected $_rssUrl;
	protected $_maxRssArticles;
	protected $_tmpDir;
	protected $_sidebar_image_dir;
	protected $_cacheFileName;
	protected static $_instance = NULL;
	
	
	/*
	public function __construct($url, $amountOfArticles,$cacheFileName) {
		$this->_rssUrl = $url;
		$this->_rssRefreshTime = $amountOfArticles;
		$this->_tmpDir = self::TMP_PATH;
		$this->_sidebar_image_dir = self::SIDEBAR_IMAGES;
		$this->_cacheFileName = $cacheFileName;
	}
	*/
	public function __construct(Array $config = array()) {
		if(isset($config['url'])) {
			$this->_rssUrl = $config['url'];
		}
		
		if(isset($config['amountOfArticles'])) {
			$this->_maxRssArticles = $config['amountOfArticles'];
		}
		
		if(isset($config['cacheName'])) {
			$this->_cacheFileName = $config['cacheName'];
		}
		
		$this->_tmpDir = self::TMP_PATH;
		
		$this->_sidebar_image_dir = self::SIDEBAR_IMAGES;
	}
	
	/**
	 * Implementation of a basic factory method
	 * @param string $url
	 * @param int $amountOfArticles
	 * @param string $cacheFileName
	 */
	public static function factory($url, $amountOfArticles,$cacheFileName) {
		if (self::$_instance == NULL) {
			
			$config = array(
				'url'=>$url,
				'amountOfArticles'=>$amountOfArticles,
				'cacheName'=>$cacheFileName
			);
			
			self::$_instance = new ThemeWidgets($config);
		}
		
		return self::$_instance;
	}
	
	public function setRssUrl($url) {
		$this->_rssUrl = $url;
	}
	
	public function getRssUrl() {
		return $this->_rssUrl;
	}
	
	public function setMaxRssArticles($max) {
		$this->_maxRssArticles = $max;
	}
	
	public function getMaxRssArticles() {
		return $this->_maxRssArticles;
	}
	
	public function setCacheTmpPath($path) {
		if(!is_dir($path)) {
			throw new Exception("The directory ".$path." does not exist ");
		}
		
		$this->_tmpDir = $path;
	}
	
	public function getCacheTmpPath() {
		return $this->_tmpDir;
	}
	
	public function setSideBarImageDir($path) {
		$this->_sidebar_image_dir = $path;
	}
	
	public function getSideBarImageDir() {
		return $this->_sidebar_image_dir;	
	}
	
	public function setCacheFileName($name) {
		$this->_cacheFileName = $name;
	}
	
	public function getCacheFileName() {
		return $this->_cacheFileName;
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
		
		// check the cache directory
		if(!is_dir($this->_tmpDir)) {
			return null;
		}
		
		// check image directory
		if(!is_dir($this->_sidebar_image_dir)) {
			return null;
		}
		
		$frontendOptions = array (
			'lifetime' => self::RSS_REFRESH_TIME,
			'automatic_serialization' => true
		);
		
		$backendOptions = array(
			'cache_dir' => $this->_tmpDir
		);
		
		$cache = Zend_Cache::factory('Core','File',$frontendOptions,$backendOptions);
		
		if(!$cache instanceof Zend_Cache_Core) {
			return null;
		}
		
	
		
		// Check to see if a cache file already exists, as well it has not expired
		if($cache->load($this->_cacheFileName)) {
			$savedCache = $cache->load($this->_cacheFileName);
			return $savedCache[$cacheToLoad];
		} 
		// site is up and active
		else {	
			if(self::isUrlActive($this->_rssUrl)) {
				$date = date("Y-M-d H:i:s",time());	
				//$rss = self::rssToArray(new Zend_Feed_Rss(self::RSS_URL));		// create an array object of the articles
				$rss = self::rssToArray(new Zend_Feed_Rss($this->_rssUrl));
				$image = self::getRandomImage($this->_sidebar_image_dir);
				
				// save the cache
				$cache->save(array('cachedDate'=>$date,'cachedRss'=>$rss,'cachedImage'=>$image),$this->_cacheFileName);
				
				// return the cache
				$savedCache = $cache->load($this->_cacheFileName);
				return $savedCache[$cacheToLoad];
			}
			// site is down
			else {
				// load the previous rss cache
				$savedCache = $cache->load($this->_cacheFileName);
				return $savedCache[$cacheToLoad];
			}
		}
		
		return null;	// empty return value
	}
	
	
	/**
	 * Check if the url is active
	 * @param string the url to check
	 * @return bool (true) if it is active
	 */
	protected function isUrlActive($url) {
		$connection = @fopen($url, 'r'); // @suppresses all error messages
		if($connection) {
			fclose($connection);
			return true;
		}
		return false;
	}
	
	/**
	 * @param Zend_Rss_Object feed of array to turn into an array
	 * @return array of a rss feed 
	 */
	protected function rssToArray($feed) {
		// get the rss feed
		$rss_arr = $feed;

		$index = 0;
		$rss_array = array();
		
		foreach($rss_arr as $rss) {
			if ($index < self::MAX_RSS_ARTICLES) {
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