<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Globals
 * 
 * The Kirby Globals Class
 * Easy setting/getting of globals
 * 
 * @package Kirby
 */
class G {

  /**
   * Gets an global value by key
   *
   * @param  mixed    $key The key to look for. Pass false or null to return the entire globals array. 
   * @param  mixed    $default Optional default value, which should be returned if no element has been found
   * @return mixed
   */
  static public function get($key = null, $default = null) {
    if(empty($key)) return $GLOBALS;
    return a::get($GLOBALS, $key, $default);
  }

  /** 
   * Sets a global by key
   *
   * @param  string  $key The key to define
   * @param  mixed   $value The value for the passed key
   */  
  static public function set($key, $value = null) {
    if(is_array($key)) {
      // set all new values
      $GLOBALS = array_merge($GLOBALS, $key);
    } else {
      $GLOBALS[$key] = $value;
    }
  }

  /**
   * Removes a variable from the GLOBALS array
   * 
   * @param string $key
   */
  static public function remove($key) {
    unset($GLOBALS[$key]);
  }

}
