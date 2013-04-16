<?php 

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Template for all cache drivers
 * 
 * @package Kirby Toolkit
 */
abstract class CacheDriver {

  // stores all options for the driver
  protected $options = array();

  /**
   * Set all parameters which are needed to connect to the cache storage
   * 
   * @param array $params
   * @return void
   */
  public function __construct($params = array()) {
    
  }

	/**
	 * Write an item to the cache for a given number of minutes.
	 *
	 * <code>
	 *		// Put an item in the cache for 15 minutes
	 *		Cache::set('value', 'my value', 15);
	 * </code>
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  int     $minutes
	 * @return void
	 */
  abstract function set($key, $value, $minutes = null);

	/**
	 * Get an item from the cache.
	 *
	 * <code>
	 *		// Get an item from the cache driver
	 *		$value = Cache::get('value');
	 *
	 *		// Return a default value if the requested item isn't cached
	 *		$value = Cache::get('value', 'default value');
	 * </code>
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
  abstract function get($key, $default = null);

  /**
   * Checks when an item in the cache expires
   * 
   * @param string $key
   * @return int
   */
  abstract function expires($key);

  /**
   * Checks if an item in the cache is expired
   * 
   * @param string $key
   * @return int
   */
  public function expired($key) {
    return $this->expires($key) <= time();  
  }

  /**
   * Get the expiration time as a UNIX timestamp.
   *
   * @param  int  $minutes
   * @return int
   */
  protected function expiration($minutes) {
    // keep the cache forever if no minutes are defined
    if(is_null($minutes)) $minutes = 2628000;

    // calculate the time 
    return time() + ($minutes * 60);
  }

	/**
	 * Determine if an item exists in the cache.
	 *
	 * @param  string  $key
	 * @return boolean
	 */
  public function exists($key) {
    return !$this->expired($key);
  }

  /**
   * Remove an item from the cache
   * 
   * @param string $key
   * @return boolean
   */
  abstract function remove($key);

  /**
   * Flush the entire cache
   * 
   * @return boolean
   */
  abstract function flush();

}
