<?php
require_once '../../library/sfDI/sfServiceContainerAutoloader.php';
//include_once('ThemeWidgets.php');

class ThemeWidgetSFContainer {
	
	
	
	const CACHE_NAME = 'CacheFile';
	const MAX_RSS_ARTICLES = 2;										// number of articles to pull
	const RSS_URL = "http://feeds.feedburner.com/benefitsworld";	// default RSS link
	
	/**
	 * @return cached version of a sidebar image
	 */
	public static function sideBarImage() {
		$tw = self::getThemeWidgetObject();
		return $tw::retrieve($tw::KEY__DAILY_IMAGE);
	}
	
	/**
	 * @return cached version of the rss feed
	 */
	public static function rssFeed() {
		$tw = self::getThemeWidgetObject();
		return $tw->retrieve($tw::KEY__RSS_FEED);
	}
	
	/**
	 * @return the date/time of the last cached event
	 */
	public static function lastCacheDate() {
		$tw = self::getThemeWidgetObject();
		return $tw->retrieve($tw::KEY__LAST_CACHED_DATE);
	}
	
	private static function getThemeWidgetObject() {
		
		sfServiceContainerAutoloader::register();
		
		// To describe services with PHP, you can use the sfServiceContainerBuilder class
		$container = new sfServiceContainerBuilder();
		
		$container->
			register('themeWidget', 'ThemeWidgets')->
			setFile('ThemeWidgets.php')->
			addArgument(array(
				'url' => '%themeWidget.url%',
				'amountOfArticles' => '%themeWidget.amountOfArticles%',
				'cacheName'	=> '%themeWidget.cacheName%'
		))->setShared(false);
		
		$container->addParameters(array(
			'themeWidget.url' => self::RSS_URL,
			'themeWidget.amountOfArticles' => self::MAX_RSS_ARTICLES,
			'themeWidget.cacheName' => self::CACHE_NAME
		));
		
		
		return $container->themeWidget;
	}

}