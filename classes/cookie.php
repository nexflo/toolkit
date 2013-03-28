<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Cookie
 * 
 * This class makes cookie handling easy
 * 
 * @package Kirby
 */
class Cookie {

  /**
    * Set a new cookie
    * 
    * @param  string  $key The name of the cookie
    * @param  string  $value The cookie content
    * @param  int     $expires The number of seconds until the cookie expires
    * @param  string  $path The path on the server to set the cookie for
    * @param  string  $domain the domain 
    * @param  boolean $secure only sets the cookie over https
    * @return boolean true: the cookie has been created, false: cookie creation failed
    */
  static public function set($key, $value, $expires = 0, $path = '/', $domain = null, $secure = false) {
  
    // convert minutes to seconds    
    if($expires > 0) $expires = time() + ($expires * 60);
    
    // convert array values to json 
    if(is_array($value)) $value = a::json($value);

    // store that thing in the cookie global 
    $_COOKIE[$key] = $value;
    
    // store the cookie
    return setcookie($key, $value, $expires, $path, $domain, $secure);
  
  }

  /**
    * Get a cookie value
    * 
    * @param  string  $key The name of the cookie
    * @param  string  $default The default value, which should be returned if the cookie has not been found
    * @return mixed   The found value
    */
  static public function get($key, $default = null) {
    return a::get($_COOKIE, $key, $default);
  }

  /**
    * Remove a cookie
    * 
    * @param  string  $key The name of the cookie
    * @param  string  $domain The domain of the cookie
    * @return mixed   true: the cookie has been removed, false: the cookie could not be removed
    */
  static public function remove($key, $path = '/', $domain = null, $secure = false) {
    unset($_COOKIE[$key]);
    return setcookie($key, false, -3600, $path, $domain, $secure);
  }

}
