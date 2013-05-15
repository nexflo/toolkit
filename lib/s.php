<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Session
 * 
 * Handles all session fiddling
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class S {

  static protected $started = false;

  /**
   * Returns the current session id
   * 
   * @return string
   */  
  static public function id() {
    return session_id();
  }

  /** 
   * Sets a session value by key
   *
   * @param  mixed   $key The key to define
   * @param  mixed   $value The value for the passed key
   */    
  static public function set($key, $value = false) {
    if(!isset($_SESSION)) return false;
    if(is_array($key)) {
      $_SESSION = array_merge($_SESSION, $key);
    } else {
      $_SESSION[$key] = $value;
    }
  }

  /**
   * Gets a session value by key
   *
   * @param  mixed    $key The key to look for. Pass false or null to return the entire session array. 
   * @param  mixed    $default Optional default value, which should be returned if no element has been found
   * @return mixed
   */  
  static public function get($key = false, $default = null) {
    if(!isset($_SESSION)) return false;
    if(empty($key)) return $_SESSION;
    return a::get($_SESSION, $key, $default);
  }

  /**
   * Removes a value from the session by key
   *
   * @param  mixed    $key The key to remove by
   * @return array    The session array without the value
   */  
  static public function remove($key) {
    if(!isset($_SESSION)) return false;
    $_SESSION = a::remove($_SESSION, $key, true);
    return $_SESSION;
  }

  /**
   * Starts a new session
   *
   */  
  static public function start() {
    if(self::$started) return true;
    session_start();
    self::$started = true;
  }

  /**
   * Destroys a session
   *
   */  
  static public function destroy() {
    if(self::$started){
      session_destroy();
      unset($_SESSION);
      self::$started = false;
    }
  }

  /**
   * Alternative for s::destroy()
   */
  static public function stop() {
    s::destroy();
  }

  /**
   * Destroys a session first and then starts it again
   *
   */  
  static public function restart() {
    self::destroy();
    self::start();
  }

}
