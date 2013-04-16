<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * File Cache
 * 
 * @package Kirby Toolkit
 */
class FileCacheDriver extends CacheDriver {

  /**
   * Set all parameters which are needed for the file cache
   * see defaults for available parameters
   * 
   * @param array $params
   * @return void
   */
  public function __construct($params = array()) {
    
    $defaults = array(
      'root'      => null,
      'extension' => null
    );

    $this->options = array_merge($defaults, $params);

    // check for a valid cache directory
    if(!is_dir($this->options['root'])) throw new Exception('The cache directory does not exist');

  }

  /**
   * Returns the full path to a file for a given key
   * 
   * @param string $key
   * @return string
   */
  protected function file($key) {
    return $this->options['root'] . DS . $key . r($this->options['extension'], '.' . $this->options['extension']);
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
  public function set($key, $value, $minutes = null) {
    $value = $this->expiration($minutes) . serialize($value);   
    return f::write($this->file($key), $value);
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
  public function get($key, $default = null) {

    // return the default value if the file does not exist at all
    if(!file_exists($this->file($key))) return $default;

    // File based caches store have the expiration timestamp stored in
    // UNIX format prepended to their contents. We'll compare the
    // timestamp to the current time when we read the file.
    if(time() > substr($cache = file_get_contents($this->file($key)), 0, 10)) {
      $this->remove($key);
      return $default;
    }

    // get the unserialized cache value without timestamp
    $cache = unserialize(substr($cache, 10));
    
    // return the cache value or the default
    return (!is_null($cache)) ? $cache : $default;

  }

  /**
   * Checks when an item in the cache expires
   * 
   * @param string $key
   * @return int
   */
  public function expires($key) {
    return substr(f::read($this->file($key)), 0, 10);
  }

  /**
   * Remove an item from the cache
   * 
   * @param string $key
   * @return boolean
   */
  public function remove($key) {
    return f::remove($this->file($key));    
  }

  /**
   * Flush the entire cache directory
   * 
   * @return boolean
   */
  public function flush() {
    return dir::clean($this->options['root']);
  }

}