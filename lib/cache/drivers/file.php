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
    return f::write($this->file($key), serialize($this->value($value, $minutes)));
  }

  /**
   * Retrieve an item from the cache.
   *
   * @param  string  $key
   * @return object CacheValue
   */
  public function retrieve($key) {
    // unserialized value array (see $this->value())
    return f::read($this->file($key), 'php');
  }

  /**
   * Checks when the cache has been created
   * 
   * @param string $key
   * @return int
   */
  public function created($key) {
    // use the modification timestamp
    // as indicator when the cache has been created/overwritten
    clearstatcache();
    // get the file for this cache key
    $file = $this->file($key);
    return file_exists($file) ? filemtime($this->file($key)) : 0;
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