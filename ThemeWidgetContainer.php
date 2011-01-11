<?php
require_once '../../library/sfDI/sfServiceContainerAutoloader.php';

class ThemeWidgetContainer {
	
	protected static $_container = NULL;
	
	public static function main() {
		//self::dumpMockXml();
	}
	
	private static function dumpMockXml() {
		sfServiceContainerAutoloader::register();
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
			'themeWidget.url' => "http://feeds.feedburner.com/benefitsworld",
			'themeWidget.amountOfArticles' => 2,
			'themeWidget.cacheName' => 'CacheFile'
		));
		
		$dumper = new sfServiceContainerDumperXml($container);
		file_put_contents('./container.xml', $dumper->dump());
	}
	
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
		
		self::$_container = new sfServiceContainerBuilder();
		$loader = new sfServiceContainerLoaderFileXml(self::$_container, array('config/default/'));
		$loader->load('config/container.xml');
		
		// It's possible to override specific variables once the container has been loaded.
		
		self::$_container->addParameters(array(
			'themeWidget.amountOfArticles' => 2
		));
		
		
		return self::$_container->themeWidget;
	}

}