<?php
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . 'lib' . PATH_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'classes');
date_default_timezone_set('America/Los_Angeles');

require_once '../lib/themewidget/ThemeWidgets.php';
require_once 'PHPUnit/Framework.php';

require_once '../../../library/Zend/Loader/AutoLoader.php';

