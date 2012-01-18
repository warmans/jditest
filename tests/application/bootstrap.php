<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

$root = realpath(dirname(__FILE__).'../../..');
$library = $root . '\library';
$tests = $root . '\tests';
$models = $root . '\application\models';
$controllers = $root . '\application/controllers';

$path = array(
    $models,
    $library,
    $tests,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();

/** Zend_Application */
require_once 'Zend/Application.php';

/*Test Environment*/
require_once 'ControllerTestCase.php';

unset($root, $library, $models, $controllers, $tests, $path);