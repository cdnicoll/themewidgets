<?php
// ================================= SET UP =====================================
include_once 'Bootstrap.php';

// namespace: lib\themewidge\
include_once 'lib/themewidget/ThemeWidgetContainer.php';
// =============================================================================


$date = ThemeWidgetContainer::lastCacheDate();
var_dump($date);

$rss = ThemeWidgetContainer::rssFeed();
var_dump($rss);

ThemeWidgetContainer::dumpThemeWidgetToPhp();

//$tw = ThemeWidgetContainer::main();
//var_dump($tw);