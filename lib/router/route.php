<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Route
 * 
 * A route can be added to the Router
 * which wil then try to match it agains the current URL
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Route {

  // the allowed methods
  protected $method = null;
  
  // the uri pattern for this route
  protected $pattern = null;
    
  // the callable action
  protected $action = null;

  // an array of params, which are found in the uri 
  protected $options = array();

  /**
   * Constructor
   * 
   * @param string $method
   * @param string $pattern The url pattern for this route
   * @param array $action Callable action
   * @param array $options
   */
  public function __construct($method, $pattern, $action, $options = array()) {
    $this->method  = $method;
    $this->pattern = $pattern;
    $this->action  = $action;
    $this->options = $options;
  }

  /**
   * Returns the method
   * 
   * @return string
   */
  public function method() {
    return $this->method;
  }

  /**
   * Returns the url pattern
   * 
   * @return string
   */
  public function pattern() {
    return $this->pattern;
  }

  /**
   * Returns the assigned action for this route
   * 
   * @return function
   */
  public function action() {
    return $this->action;
  }

  /**
   * Returns the array of params, which are found in the url
   * 
   * @return array
   */
  public function options() {
    return $this->options;
  }

}