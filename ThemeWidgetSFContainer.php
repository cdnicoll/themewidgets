<?php
require_once '../../library/sfDI/sfServiceContainerAutoloader.php';
//include_once('ThemeWidgets.php');

class ThemeWidgetSFContainer {
	
	
	
	const CACHE_NAME = 'CacheFile';
	const MAX_RSS_ARTICLES = 2;										// number of articles to pull
	const RSS_URL = "http://feeds.feedburner.com/benefitsworld";	// default RSS link
	
	protected static $container = NULL;
	
	/**
	 * @return cached version of a sidebar image
	 */
	public static function sideBarImage() {
		self::createThemeWidgetObject();
		
		self::$container->addParameters(array(
			'themeWidget.url' => self::RSS_URL,
			'themeWidget.amountOfArticles' => self::MAX_RSS_ARTICLES,
			'themeWidget.cacheName' => self::CACHE_NAME
		));
		
		$tw = self::$container->themeWidget;
		
		return $tw::retrieve($tw::KEY__DAILY_IMAGE);
	}
	
	/**
	 * @return cached version of the rss feed
	 */
	public static function rssFeed() {
		self::createThemeWidgetObject();
		
		self::$container->addParameters(array(
			'themeWidget.url' => self::RSS_URL,
			'themeWidget.amountOfArticles' => self::MAX_RSS_ARTICLES,
			'themeWidget.cacheName' => self::CACHE_NAME
		));
		
		$tw = self::$container->themeWidget;
		return $tw->retrieve($tw::KEY__RSS_FEED);
	}
	
	/**
	 * @return the date/time of the last cached event
	 */
	public static function lastCacheDate() {
		self::createThemeWidgetObject();
		
		self::$container->addParameters(array(
			'themeWidget.url' => self::RSS_URL,
			'themeWidget.amountOfArticles' => self::MAX_RSS_ARTICLES,
			'themeWidget.cacheName' => self::CACHE_NAME
		));
		
		$tw = self::$container->themeWidget;
		
		return $tw->retrieve($tw::KEY__LAST_CACHED_DATE);
	}
	
	private static function createThemeWidgetObject() {
		
		sfServiceContainerAutoloader::register();
		
		if (self::$container == NULL) {
			// To describe services with PHP, you can use the sfServiceContainerBuilder class
			self::$container = new sfServiceContainerBuilder();
			
			self::$container->
				register('themeWidget', 'ThemeWidgets')->
				setFile('ThemeWidgets.php')->
				addArgument(array(
					'url' => '%themeWidget.url%',
					'amountOfArticles' => '%themeWidget.amountOfArticles%',
					'cacheName'	=> '%themeWidget.cacheName%'
			))->setShared(false);
		}
	}

}