<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Language
 * 
 * Some handy methods to handle multi-language support
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class L {
  
  /**
   * The global language array
   * 
   * @var array
   */
  static public $lang = array();

  /**
   * Gets a language value by key
   *
   * @param  mixed    $key The key to look for. Pass false or null to return the entire language array. 
   * @param  mixed    $default Optional default value, which should be returned if no element has been found
   * @return mixed
   */
  static public function get($key = null, $default = null) {
    if(empty($key)) return self::$lang;
    return a::get(self::$lang, $key, $default);
  }

  /** 
   * Sets a language value by key
   *
   * @param  mixed   $key The key to define
   * @param  mixed   $value The value for the passed key
   */  
  static public function set($key, $value=null) {
    if(is_array($key)) {
      self::$lang = array_merge(self::$lang, $key);
    } else {
      self::$lang[$key] = $value;
    }
  }

  /**
   * Removes a variable from the language array
   * 
   * @param string $key
   */
  static public function remove($key) {
    unset(self::$lang[$key]);
  }
  
}
