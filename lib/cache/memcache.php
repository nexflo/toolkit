<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Memcache
 * 
 * @package Kirby Toolkit
 */
class MemcacheCacheDriver extends CacheDriver {

  protected $connection = null;

  /**
   * Set all parameters which are needed for the memcache client
   * see defaults for available parameters
   * 
   * @param array $params
   * @return void
   */
  public function __construct($params = array()) {
    
    $defaults = array(
      'host'    => 'localhost',
      'port'    => 11211, 
      'timeout' => 1
    );

    $this->options    = array_merge($defaults, $params);
    $this->connection = new Memcache();
    $this->connection->connect($this->options['host'], $this->options['port'], $this->options['timeout']);

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
    return $this->connection->set($key, $value, false, $this->expiration($minutes));
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
    if(($cache = $this->connection->get($key)) !== false) return $cache;
    return $default;
  }

  /**
   * Checks if the current key exists in cache
   * 
   * @param string $key
   * @return boolean
   */
  public function exists($key) {
    return !is_null($this->get($key));
  }

  /**
   * Checks when an item in the cache expires
   * 
   * @param string $key
   * @return int
   */
  public function expires($key) {
    return null;
  }

  /**
   * Checks if the key has expired yet
   * 
   * @param string $key
   * @return boolean
   */
  public function expired($key) {
    return !$this->exists($key);
  }

  /**
   * Remove an item from the cache
   * 
   * @param string $key
   * @return boolean
   */
  public function remove($key) {
    return $this->connection->delete($key);
  }

  /**
   * Flush the entire cache directory
   * 
   * @return boolean
   */
  public function flush() {
    return $this->connection->flush();
  }

}