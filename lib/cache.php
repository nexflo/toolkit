<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// dependencies
require_once(ROOT_KIRBY_TOOLKIT_LIB . DS . 'cache' . DS . 'driver.php');

/**
 * 
 * Cache 
 * 
 * The ultimate cache wrapper for 
 * all available drivers
 * 
 * @package Kirby
 */
class Cache {

  // the current driver
  static protected $driver;

  /**
   * Connect a cache driver
   * Check out the driver for more details
   * on how to setup individual connections
   * 
   * @param string $driver The name of the driver. ie. 'file'
   * @param array $params Additional params for the driver connection
   * @return object The cache driver object
   */
  static public function connect($driver, $params = array()) {

    // driver class file
    $file  = ROOT_KIRBY_TOOLKIT_LIB . DS . 'cache' . DS . $driver . '.php';
    $class = $driver . 'CacheDriver';

    if(!file_exists($file)) throw new Exception('The cache driver does not exist: ' . $driver);

    // load the driver class
    require_once($file);

    return self::$driver = new $class($params);

  }

  /**
   * Returns the currently connected driver
   * 
   * @return object
   */
  static public function driver() {
    if(is_null(self::$driver)) throw new Exception('No cache driver connected yet');
    return self::$driver;
  }

  /**
   * Write an item to the cache for a given number of minutes.
   *
   * <code>
   *    // Put an item in the cache for 15 minutes
   *    Cache::set('value', 'my value', 15);
   * </code>
   *
   * @param  string  $key
   * @param  mixed   $value
   * @param  int     $minutes
   * @return void
   */
  static public function set($key, $value, $minutes = null) {
    return self::driver()->set($key, $value, $minutes);
  }

  /**
   * Get an item from the cache.
   *
   * <code>
   *    // Get an item from the cache driver
   *    $value = Cache::get('value');
   *
   *    // Return a default value if the requested item isn't cached
   *    $value = Cache::get('value', 'default value');
   * </code>
   *
   * @param  string  $key
   * @param  mixed   $default
   * @return mixed
   */
  static public function get($key, $default = null) {
    return self::driver()->get($key, $default);
  }

  /**
   * Checks when an item in the cache expires
   * 
   * @param string $key
   * @return int
   */
  static public function expires($key) {
    return self::driver()->expires($key);
  }

  /**
   * Checks if an item in the cache is expired
   * 
   * @param string $key
   * @return int
   */
  static public function expired($key) {
    return self::driver()->expired($key);
  }

  /**
   * Determine if an item exists in the cache.
   *
   * @param  string  $key
   * @return boolean
   */
  static public function exists($key) {
    return self::driver()->exists($key);
  }

  /**
   * Remove an item from the cache
   * 
   * @param string $key
   * @return boolean
   */
  static public function remove($key) {
    return self::driver()->remove($key);
  }

  /**
   * Flush the entire cache
   * 
   * @return boolean
   */
  static public function flush() {
    return self::driver()->flush();
  }

}