<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Server
 * 
 * Makes it more convenient to get variables
 * from the global server array
 * 
 * @package Kirby
 */
class Server {

  /**
   * Gets a value from the _SERVER array
   *
   * @param  mixed    $key The key to look for. Pass false or null to return the entire server array. 
   * @param  mixed    $default Optional default value, which should be returned if no element has been found
   * @return mixed
   */  
  static public function get($key = false, $default = null) {
    if(empty($key)) return $_SERVER;
    return a::get($_SERVER, str::upper($key), $default);
  }

}
