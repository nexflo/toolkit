<?php

/**
 * Kirby Toolkit Bootstrapper
 * 
 * Include this file to load all toolkit 
 * classes and helpers on demand
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

// helper constants
if(!defined('KIRBY'))     define('KIRBY',     true);
if(!defined('DS'))        define('DS',        DIRECTORY_SEPARATOR);
if(!defined('MB_STRING')) define('MB_STRING', (int)function_exists('mb_get_info'));

// define toolkit roots
define('KIRBY_TOOLKIT_ROOT',     dirname(__FILE__));
define('KIRBY_TOOLKIT_ROOT_LIB', KIRBY_TOOLKIT_ROOT . DS . 'lib');

/**
 * Loads all missing toolkit classes on demand
 * 
 * @param string $class The name of the missing class
 * @return void
 */
function toolkitLoader($class) {

  $file = KIRBY_TOOLKIT_ROOT_LIB . DS . strtolower($class) . '.php';

  if(file_exists($file)) {
    require_once($file);
    return;
  } 

}

// register the autoloader function
spl_autoload_register('toolkitLoader');

// load the default config values
require_once(KIRBY_TOOLKIT_ROOT . DS . 'defaults.php');

// set the default timezone
date_default_timezone_set(c::get('timezone', 'UTC'));

// load the helper functions
require_once(KIRBY_TOOLKIT_ROOT . DS . 'helpers.php');
