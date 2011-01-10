<?php
// ================================= SET UP =====================================
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
date_default_timezone_set('America/Los_Angeles');

require_once '../../library/Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

include_once 'ThemeWidgetContainer.php';
include_once('ThemeWidgetSFContainer.php');
// =============================================================================


$date = ThemeWidgetContainer::lastCacheDate();
echo $date;
echo '<hr>';

$date = ThemeWidgetSFContainer::lastCacheDate();
echo $date;