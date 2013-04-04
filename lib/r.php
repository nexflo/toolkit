<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Request
 * 
 * Handles all incoming requests
 * 
 * @package Kirby
 */
class R {

  // Stores all sanitized request data
  static protected $data = null;

  // the used request method
  static protected $method = null;  
  
  // the request body
  static protected $body = null;

  /**
   * Returns either the entire data array or parts of it
   * 
   * @param string $key An optional key to receive only parts of the data array
   * @param mixed $default A default value, which will be returned if nothing can be found for a given key
   * @param mixed
   */
  static public function data($key = null, $default = null) {
    
    if(!is_null(self::$data)) {
      $data = self::$data;
    } else {
      $_REQUEST = array_merge($_GET, $_POST);
      $data = self::$data = (self::is('GET')) ? self::sanitize($_REQUEST) : array_merge(self::body(), self::sanitize($_REQUEST));
    }
    
    if(is_null($key)) return $data;

    return isset($data[$key]) ? $data[$key] : $default;
    
  }

  /**
   * Private method to sanitize incoming request data
   * 
   * @param array $data
   * @return array 
   */
  static protected function sanitize($data) {

    if(!is_array($data)) {
      return trim(str::stripslashes($data));      
    }

    foreach($data as $key => $value) {
      $value = self::sanitize($value);
      $data[$key] = $value;    
    }      

    return $data;  

  }

  /**
   * Sets or overwrites a variable in the data array
   * 
   * @param mixed $key The key to set/replace. Use an array to set multiple values at once
   * @param mixed $value The value
   * @return array
   */
  static public function set($key, $value = null) {
    
    // set multiple values at once
    if(is_array($key)) {
      foreach($key as $k => $v) self::set($k, $v);
    }

    // make sure the data array is actually an array
    if(is_null(self::$data)) self::$data = array();

    self::$data[$key] = $_REQUEST[$key] = self::sanitize($value);
    return self::$data;

  }

  /**
   * Alternative to self::data($key, $default)
   * 
   * @param string $key An optional key to receive only parts of the data array
   * @param mixed $default A default value, which will be returned if nothing can be found for a given key
   * @param mixed
   */
  static public function get($key = null, $default = null) {
    return self::data($key, $default);  
  }

  /**
   * Removes a variable from the request array
   * 
   * @param string $key
   */
  static public function remove($key) {
    unset($_REQUEST[$key]);
    unset(self::$data[$key]);
  }

  /**
   * Returns the current request method
   *
   * @return string POST, GET, DELETE, PUT
   */  
  static public function method() {
    $method = strtoupper(server::get('request_method', 'GET'));
    return ($method == 'HEAD') ? 'GET' : $method;
  }

  /**
   * Returns the request body from POST requests for example
   *
   * @return array
   */    
  static public function body() {
    if(!is_null(self::$body)) return self::$body; 
    @parse_str(@file_get_contents('php://input'), self::$body); 
    return self::$body = self::sanitize((array)self::$body);
  }

  /**
   * Checks if the request is of a specific type: 
   * 
   * - GET
   * - POST
   * - PUT
   * - DELETE
   * - AJAX
   * 
   * @return boolean
   */
  static public function is($method) {
    if($method == 'ajax') {
      return self::ajax();
    } else {
      return (strtoupper($method) == self::method()) ? true : false;
    }
  }

  /**
   * Returns the referer if available
   * 
   * @param string $default Pass an optional URL to use as default referer if no referer is being found
   * @return string
   */
  static public function referer($default = null) {
    return server::get('http_referer', $default);
  }

  /**
   * Nobody remembers how to spell it
   * so this is a shortcut
   * 
   * @param string $default Pass an optional URL to use as default referer if no referer is being found
   * @return string
   */
  static public function referrer($default = nullg) {
    return self::referer($default);    
  }

  /**
   * Returns the IP address from the 
   * request user if available
   * 
   * @param mixed
   */
  static public function ip() {
    return server::get('remote_addr', '0.0.0.0');
  }

  /**
   * Checks if the request has been made from the command line
   * 
   * @return boolean
   */
  static public function cli() {
    return defined('STDIN') || (substr(PHP_SAPI, 0, 3) == 'cgi' && getenv('TERM'));
  }

  /**
   * Checks if the request is an AJAX request
   * 
   * @return boolean
   */
  static public function ajax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;        
  }

  /**
   * Returns the request scheme
   * 
   * @return string
   */
  static public function scheme() {
    return (server::get('https') && str::lower(server::get('https')) != 'off') ? 'https' : 'http';
  }

  /**
   * Checks if the request is encrypted
   * 
   * @return boolean
   */
  static public function ssl() {
    return (self::scheme() == 'https') ? true : false;
  }

  /**
   * Alternative for self::ssl()
   * 
   * @return boolean
   */
  static public function secure() {
    return self::ssl();
  }

}
