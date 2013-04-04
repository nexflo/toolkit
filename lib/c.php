<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Config 
 * 
 * This is the core class to handle 
 * configuration values/constants. 
 * 
 * @package Kirby
 */
class C {

  /** 
   * The static config array
   * It contains all config values
   * 
   * @var array
   */
  static public $config = array();

  /** 
   * Sets a config value by key
   *
   * @param  string  $key The key to define
   * @param  mixed   $value The value for the passed key
   */  
  static public function set($key, $value = null) {
    if(is_array($key)) {
      // set all new values
      self::$config = array_merge(self::$config, $key);
    } else {
      self::$config[$key] = $value;
    }
  }
  
  /** 
   * Gets a config value by key
   *
   * @param  string  $key The key to look for. Pass false to get the entire config array
   * @param  mixed   $default The default value, which will be returned if the key has not been found
   * @return mixed   The found config value
   */  
  static public function get($key = null, $default = null) {
    if(empty($key)) return self::$config;
    return a::get(self::$config, $key, $default);
  }

  /**
   * Removes a variable from the config array
   * 
   * @param string $key
   */
  static public function remove($key) {
    unset(self::$config[$key]);
  }

}