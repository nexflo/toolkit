<?php 

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// dependencies
require_once(ROOT_KIRBY_TOOLKIT_LIB . DS . 'cache' . DS . 'value.php');

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
   * Private method to retrieve the cache value
   * This needs to be defined by the driver
   * 
   * @param string $key
   * @return object CacheValue
   */
  abstract function retrieve($key);

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
  public function get($key, $default = null) {

    // get the CacheValue
    $value = $this->retrieve($key);

    // check for a valid cache value
    if(!is_a($value, 'CacheValue')) return $default;
     
    // remove the item if it is expired
    if(time() > $value->expires()) {
      $this->remove($key);
      return $default;
    }

    // get the pure value
    $cache = $value->value();
    
    // return the cache value or the default
    return (!is_null($cache)) ? $cache : $default;

  }

  /**
   * Calculates the expiration timestamp
   * 
   * @param int $minutes
   * @return int
   */
  protected function expiration($minutes = null) {
    // keep forever if minutes are not defined  
    if(is_null($minutes)) $minutes = 2628000;

    // calculate the time
    return time() + ($minutes * 60);
  }

  /**
   * Checks when an item in the cache expires
   * 
   * @param string $key
   * @return int
   */
  public function expires($key) {
    // get the CacheValue object
    $value = $this->retrieve($key);

    // check for a valid CacheValue object
    if(!is_a($value, 'CacheValue')) return false;

    // return the expires timestamp
    return $value->expires();
  }

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
   * Checks when the cache has been created
   * 
   * @param string $key
   * @return int
   */
  public function created($key) {
    // get the CacheValue object
    $value = $this->retrieve($key);

    // check for a valid CacheValue object
    if(!is_a($value, 'CacheValue')) return false;

    // return the expires timestamp
    return $value->created();
  }

  /**
   * An array with value, created timestamp and expires timestamp
   * 
   * @param mixed $value The value, which should be cached
   * @param int $minutes The number of minutes before expiration
   * @return array
   */
  protected function value($value, $minutes) {
    return new CacheValue($value, $minutes);
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
