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

// check for mb_string support
if(!defined('MB_STRING')) define('MB_STRING', (int)function_exists('mb_get_info'));

// global store for all roots
$GLOBALS['kirby.roots'] = array();

/**
 * Root handling
 * 
 * @param mixed $key
 * @param string $value
 * @param string $default
 * @return mixed
 */
function root($key = null, $value = null, $default = null) {
  if(is_null($key)) {    
    $result = array();
    foreach($GLOBALS['kirby.roots'] as $k => $v) $result[$k] => root($k);
    return $result;
  }
  if(is_array($key)) {
    foreach($key as $k => $v) root($k, $v);
    return $GLOBALS['kirby.roots'];
  }
  if(!is_null($value)) $GLOBALS['kirby.roots'][$key] = $value;  

  if(isset($GLOBALS['kirby.roots'][$key])) {

    $value = $GLOBALS['kirby.roots'][$key];
    $func  = create_function('$result', 'return root($result[1]);');

    if(strstr($value, '{')) $value = preg_replace_callback('!\{([a-z0-9]+)\}!i', $func, $value);
    return $value;

  } else {
    return $default;
  } 

}

// set the default roots for the toolkit
root(array(
  'toolkit'     => dirname(__FILE__),
  'toolkit.lib' => dirname(__FILE__) . DS . 'lib' 
));

/**
 * Loads all missing toolkit classes on demand
 * 
 * @param string $class The name of the missing class
 * @return void
 */
function toolkitLoader($class) {

  $file = root('toolkit.lib') . DS . strtolower($class) . '.php';

  if(file_exists($file)) {
    require_once($file);
    return;
  } 

}

// register the autoloader function
spl_autoload_register('toolkitLoader');

// load the default config values
require_once(root('toolkit') . DS . 'defaults.php');

// set the default timezone
date_default_timezone_set(c::get('timezone', 'UTC'));

// load the helper functions
require_once(root('toolkit') . DS . 'helpers.php');
