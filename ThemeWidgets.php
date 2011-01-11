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
	protected $_rssUrl;
	protected $_maxRssArticles;
	protected $_tmpDir;
	protected $_sidebar_image_dir;
	protected $_cacheFileName;
	protected $_cacheExpireTime;
	protected $_zendRssReader;
	protected $_zendCacheReader;	
	
	/*
	public function __construct($url, $amountOfArticles,$cacheFileName) {
		$this->_rssUrl = $url;
		$this->_rssRefreshTime = $amountOfArticles;
		$this->_tmpDir = self::TMP_PATH;
		$this->_sidebar_image_dir = self::SIDEBAR_IMAGES;
		$this->_cacheFileName = $cacheFileName;
	}
	*/
	public function __construct(Array $config = array(), $zendRssReader, $zendCacheReader) {		
		// ========= RSS URL =========
		if(isset($config['rssUrl'])) {
			$this->_rssUrl = $config['rssUrl'];
		}
		else {
			throw new Exception("@param url is missing");
		}
		
		// ========= Amount Of Articles =========
		if(isset($config['amountOfArticles'])) {
			$this->_maxRssArticles = $config['amountOfArticles'];
		}
		else {
			throw new Exception("@param amountOfArticles is missing");
		}
		
		// ========= Cache File Name =========
		if(isset($config['cacheName'])) {
			$this->_cacheFileName = $config['cacheName'];
		}
		else {
			throw new Exception("@param cacheName is missing");
		}
		
		// ========= Cache Expirary Time =========
		if(isset($config['rssRefreshTime'])) {
			$this->_cacheExpireTime = $config['rssRefreshTime'];
		}
		else {
			throw new Exception("@param rssRefreshTime is missing");
		}
		
		// ========= Temp Directory =========
		if(isset($config['tmpDir'])) {
			if(!is_dir($config['tmpDir'])) {
				throw new Exception("The directory ".$config['tmpDir']." does not exist ");
			}
			$this->_tmpDir = $config['tmpDir'];
		}
		else {
			throw new Exception("@param tmpDir is missing");
		}
		
		// ========= Side Bar Images =========
		if(isset($config['sideimageDir'])) {
			if(!is_dir($config['sideimageDir'])) {
				throw new Exception("The directory ".$config['sideimageDir']." does not exist ");
			}
			$this->_sidebar_image_dir = $config['sideimageDir'];
		}
		else {
			throw new Exception("@param sideimageDir is missing");
		}
		
		// ========= Zend RSS Reader =========
		$this->_zendRssReader = new $zendRssReader($this->_rssUrl);
		
		// ========= Zend Cache Reader =========
		$frontendOptions = array (
			'lifetime' => $this->_cacheExpireTime,
			'automatic_serialization' => true
		);
		
		$backendOptions = array(
			'cache_dir' => $this->_tmpDir
		);
		
		$this->_zendCacheReader = $zendCacheReader::factory('Core','File',$frontendOptions,$backendOptions);
					
	}
	
	/**
	 * @return the $_rssUrl
	 */
	public function getRssUrl() {
		return $this->_rssUrl;
	}

	/**
	 * @return the $_maxRssArticles
	 */
	public function getMaxRssArticles() {
		return $this->_maxRssArticles;
	}

	/**
	 * @return the $_tmpDir
	 */
	public function getTmpDir() {
		return $this->_tmpDir;
	}

	/**
	 * @return the $_sidebar_image_dir
	 */
	public function getSidebar_image_dir() {
		return $this->_sidebar_image_dir;
	}

	/**
	 * @return the $_cacheFileName
	 */
	public function getCacheFileName() {
		return $this->_cacheFileName;
	}

	/**
	 * @return the $_cacheExpireTime
	 */
	public function getCacheExpireTime() {
		return $this->_cacheExpireTime;
	}

	/**
	 * @return the $_zendRssStremReader
	 */
	public function getZendRssStremReader() {
		return $this->_zendRssReader;
	}

	/**
	 * @return the $_zendCacheReader
	 */
	public function getZendCacheReader() {
		return $this->_zendCacheReader;
	}

	/**
	 * @param field_type $_rssUrl
	 */
	public function setRssUrl($_rssUrl) {
		$this->_rssUrl = $_rssUrl;
	}

	/**
	 * @param field_type $_maxRssArticles
	 */
	public function setMaxRssArticles($_maxRssArticles) {
		$this->_maxRssArticles = $_maxRssArticles;
	}

	/**
	 * @param field_type $_tmpDir
	 */
	public function setTmpDir($_tmpDir) {
		if(!is_dir($_tmpDir)) {
			throw new Exception("The directory ".$_tmpDir." does not exist ");
		}
		
		$this->_tmpDir = $_tmpDir;
	}

	/**
	 * @param field_type $_sidebar_image_dir
	 */
	public function setSidebar_image_dir($_sidebar_image_dir) {
		$this->_sidebar_image_dir = $_sidebar_image_dir;
	}

	/**
	 * @param field_type $_cacheFileName
	 */
	public function setCacheFileName($_cacheFileName) {
		$this->_cacheFileName = $_cacheFileName;
	}

	/**
	 * @param field_type $_cacheExpireTime
	 */
	public function setCacheExpireTime($_cacheExpireTime) {
		$this->_cacheExpireTime = $_cacheExpireTime;
	}

	/**
	 * @param field_type $_zendRssStremReader
	 */
	public function setZendRssStremReader($_zendRssStremReader) {
		$this->_zendRssReader = $_zendRssStremReader;
	}

	/**
	 * @param field_type $_zendCacheReader
	 */
	public function setZendCacheReader($_zendCacheReader) {
		var_dump($_zendCacheReader);
		//$this->_zendCacheReader = new $_zendCacheReader;
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
		
		/*
		if(!$cache instanceof Zend_Cache_Core) {
			return null;
		}
		*/
		
		
		// Check to see if a cache file already exists, as well it has not expired
		if($this->_zendCacheReader->load($this->_cacheFileName)) {
			$savedCache = $this->_zendCacheReader->load($this->_cacheFileName);
			return $savedCache[$cacheToLoad];
		} 
		// site is up and active
		else {	
			if(self::isUrlActive($this->_rssUrl)) {
				$date = date("Y-M-d H:i:s",time());	
				
				$rss = self::rssToArray($this->_zendRssReader);
				
				$image = self::getRandomImage($this->_sidebar_image_dir);
				
				// save the cache
				$this->_zendCacheReader->save(array('cachedDate'=>$date,'cachedRss'=>$rss,'cachedImage'=>$image),$this->_cacheFileName);
				
				// return the cache
				$savedCache = $this->_zendCacheReader->load($this->_cacheFileName);
				return $savedCache[$cacheToLoad];
			}
			// site is down
			else {
				// load the previous rss cache
				$savedCache = $this->_zendCacheReader->load($this->_cacheFileName);
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
	protected function rssToArray(Zend_Feed_Rss $feed) {
		// get the rss feed
		$rss_arr = $feed;

		$index = 0;
		$rss_array = array();
		
		foreach($rss_arr as $rss) {
			if ($index < $this->_maxRssArticles) {
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