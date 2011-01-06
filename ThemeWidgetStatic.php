<?php
include_once('ThemeWidgets.php');

class ThemeWidgetStatic {
	
	const CACHE_NAME = 'CacheFile';
	const MAX_RSS_ARTICLES = 2;										// number of articles to pull
	const RSS_URL = "http://feeds.feedburner.com/benefitsworld";	// default RSS link
	
	/**
	 * @return cached version of a sidebar image
	 */
	public static function sideBarImage() {
		$tw = self::getThemeWidgetObject();
		return $tw->retrieve($tw::KEY__DAILY_IMAGE);
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
		return new ThemeWidgets(self::RSS_URL, self::MAX_RSS_ARTICLES, self::CACHE_NAME);
	}

}