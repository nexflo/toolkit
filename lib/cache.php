<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// dependencies
require_once(ROOT_KIRBY_TOOLKIT_LIB . DS . 'cache' . DS . 'driver.php');

class Cache {

  static protected $driver;

  static public function connect($driver, $params = array()) {

    // driver class file
    $file  = ROOT_KIRBY_TOOLKIT_LIB . DS . 'cache' . DS . $driver . '.php';
    $class = $driver . 'CacheDriver';

    if(!file_exists($file)) throw new Exception('The cache driver does not exist: ' . $driver);

    // load the driver class
    require_once($file);

    return self::$driver = new $class($params);

  }

  static public function driver() {
    if(is_null(self::$driver)) throw new Exception('No cache driver connected yet');
    return self::$driver;
  }

  static public function set($key, $value, $minutes = null) {
    return self::driver()->set($key, $value, $minutes);
  }

  static public function get($key, $default = null) {
    return self::driver()->get($key, $default);
  }

  static public function expires($key) {
    return self::driver()->expires($key);
  }

  static public function expired($key) {
    return self::driver()->expired($key);
  }

  static public function exists($key) {
    return self::driver()->exists($key);
  }

  static public function remove($key) {
    return self::driver()->remove($key);
  }

  static public function flush() {
    return self::driver()->flush();
  }

}