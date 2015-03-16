<?php
/**
 * Created by PhpStorm.
 * User: user pc
 * Date: 2/7/15
 * Time: 4:23 PM
 */
// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected
// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', '/');
defined('BASE_URL')?null:define('BASE_URL',$_SERVER['SERVER_NAME']);
defined('SITE_ROOT') ? null : define('SITE_ROOT',dirname($_SERVER['PHP_SELF']));
defined('SITE_ROOT_URL')?null:define('SITE_ROOT_URL','http://'. BASE_URL.SITE_ROOT);
//echo SITE_ROOT_URL;