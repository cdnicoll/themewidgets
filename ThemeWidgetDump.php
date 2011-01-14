<?php

class Container extends sfServiceContainer
{
  protected $shared = array();

  public function __construct()
  {
    parent::__construct($this->getDefaultParameters());
  }

  protected function getRssReaderService()
  {
    require_once 'lib/themewidget/RssReader.php';

    if (isset($this->shared['rssReader'])) return $this->shared['rssReader'];

    $class = $this->getParameter('rssreader.class');
    $instance = new $class(array('url' => $this->getParameter('rssreader.url'), 'maxArticles' => $this->getParameter('rssreader.maxarticles')));

    return $this->shared['rssReader'] = $instance;
  }

  protected function getCacheReaderService()
  {
    if (isset($this->shared['cacheReader'])) return $this->shared['cacheReader'];

    $instance = call_user_func(array('Zend_Cache', 'factory'), 'Core', 'File', array('lifetime' => $this->getParameter('cachereader.lifetime'), 'automatic_serialization' => true), array('cache_dir' => $this->getParameter('cachereader.tmpdir')));

    return $this->shared['cacheReader'] = $instance;
  }

  protected function getThemeWidgetService()
  {
    require_once 'lib/themewidget/ThemeWidgets.php';

    $instance = new ThemeWidgets(array('cacheName' => $this->getParameter('themewidget.cachename'), 'sideimageDir' => $this->getParameter('themewidget.sideimagedir'), 'rssReader' => $this->getService('rssReader'), 'cacheReader' => $this->getService('cacheReader')));

    return $instance;
  }

  protected function getDefaultParameters()
  {
    return array(
      'themewidget.cachename' => 'CacheFileNewName',
      'themewidget.sideimagedir' => 'images/sidebar-images/',
      'rssreader.class' => 'RssReader',
      'rssreader.url' => 'http://feeds.feedburner.com/benefitsworld',
      'rssreader.maxarticles' => 5,
      'cachereader.lifetime' => 11,
      'cachereader.tmpdir' => '../../../../files/localtests-tmp/',
      'themewidget.rssrefreshtime' => 11,
    );
  }
}
