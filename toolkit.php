<?php

/**
 * Kirby Toolkit Bootstrapper
 * 
 * Include this file to load all toolkit 
 * classes and helpers on demand
 */

// direct access protection
if(!defined('KIRBY')) define('KIRBY', true);

// store the directory separator in something simpler to use
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// store the main toolkit root
if(!defined('ROOT_KIRBY_TOOLKIT'))         define('ROOT_KIRBY_TOOLKIT',         dirname(__FILE__));
if(!defined('ROOT_KIRBY_TOOLKIT_CLASSES')) define('ROOT_KIRBY_TOOLKIT_CLASSES', ROOT_KIRBY_TOOLKIT . DS . 'classes');

// check for mb_string support
if(!defined('MB_STRING')) define('MB_STRING', (int)function_exists('mb_get_info'));

/**
 * Loads all missing toolkit classes on demand
 * 
 * @param string $class The name of the missing class
 * @return void
 */
function toolkitLoader($class) {

  $file = ROOT_KIRBY_TOOLKIT_CLASSES . DS . strtolower($class) . '.php';

  if(file_exists($file)) {
    require_once($file);
    return;
  } 

}

// register the autoloader function
spl_autoload_register('toolkitLoader');

// load the default config values
require_once(ROOT_KIRBY_TOOLKIT . DS . 'defaults.php');

// set the default timezone
date_default_timezone_set(c::get('timezone', 'UTC'));

// load the helper functions
require_once(ROOT_KIRBY_TOOLKIT . DS . 'helpers.php');
