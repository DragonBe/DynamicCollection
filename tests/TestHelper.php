<?php
/*
 * Set error reporting to the level to which code must comply.
 */
error_reporting( E_ALL | E_STRICT );

/*
 * Set default timezone
 */
date_default_timezone_set('Europe/Brussels');

/*
 * Set include paths
 */
if (!defined('TEST_PATH')) define('TEST_PATH', dirname(__FILE__));
set_include_path(implode(PATH_SEPARATOR, array (
    realpath(TEST_PATH . '/../lib'),
    get_include_path(),
)));

/*
 * Let the autoloader work for a change
 */
spl_autoload_register(function($class) { return spl_autoload(str_replace('_', '/', $class)); });