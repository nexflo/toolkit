<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// dependencies
require_once(dirname(__FILE__) . DS . 'router' . DS . 'route.php');

/**
 * Router
 * 
 * The router makes it possible to react 
 * on any incoming URL scheme
 * 
 * Partly inspired by Laravel's router
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Router {
  
  // the matched route if found
  static protected $route = null;

  // all registered routes
  static protected $routes = array(
    'GET'    => array(),
    'POST'   => array(),
    'PUT'    => array(),
    'DELETE' => array()
  );

  // The wildcard patterns supported by the router.
  static protected $patterns = array(
    '(:num)'     => '([0-9]+)',
    '(:alpha)'   => '([a-zA-Z]+)',
    '(:any)'     => '([a-zA-Z0-9\.\-_%=]+)',
    '(:all)'     => '(.*)',
  );

  // The optional wildcard patterns supported by the router.
  static protected $optional = array(
    '/(:num?)'   => '(?:/([0-9]+)',
    '/(:alpha?)' => '(?:/([a-zA-Z]+)',
    '/(:any?)'   => '(?:/([a-zA-Z0-9\.\-_%=]+)',
    '/(:all?)'   => '(?:/(.*)',
  );

  /**
   * Resets all registered routes
   */
  static public function reset() {
    self::$route  = null;
    self::$routes = array(
      'GET'    => array(),
      'POST'   => array(),
      'PUT'    => array(),
      'DELETE' => array()
    );    
  }

  /**
   * Returns the found route
   * 
   * @return mixed
   */
  static public function route() {
    return self::$route;
  }

  /**
   * Returns the options array from the current route
   * 
   * @return array
   */
  static public function options() {
    if($route = self::route()) return $route->options();
  }

  /**
   * Adds a new route
   * 
   * @param string|array $methods GET, POST, PUT, DELETE
   * @param string $uri The url pattern, which should be matched
   * @param mixed $action An array of parameters for this route
   */
  static public function register($methods, $pattern, $action) {
    foreach((array)$methods as $method) {
      self::$routes[$method][$pattern] = $action;
    }
  }

  static public function get($pattern, $action) {
    self::register('GET', $pattern, $action);
  }

  static public function post($pattern, $action) {
    self::register('POST', $pattern, $action);
  }

  static public function put($pattern, $action) {
    self::register('PUT', $pattern, $action);
  }

  static public function delete($pattern, $action) {
    self::register('DELETE', $pattern, $action);
  }

  /**
   * Returns all added routes
   * 
   * @param string $method
   * @return array
   */
  static public function routes($method = null) {
    return is_null($method) ? self::$routes : self::$routes[$method];
  }

  /**
   * Iterate through every route to find a matching route.
   *
   * @param  string  $url Optional url to match against
   * @return Route
   */
  static public function match($url = null) {

    $url    = is_null($url) ? uri::current()->path()->toString() : trim(url::path($url), '/');
    $method = r::method();
    $routes = self::$routes[$method];

    foreach($routes as $pattern => $action) {

      // handle exact matches
      if($pattern == $url) {
        return self::$route = new Route($method, $pattern, $action, array());
      }

      // We only need to check routes with regular expression since all others
      // would have been able to be matched by the search for literal matches
      // we just did before we started searching.
      if(!str::contains($pattern, '(')) continue;
      
      $preg = '#^'. self::wildcards($pattern) . '$#u';

      // If we get a match we'll return the route and slice off the first
      // parameter match, as preg_match sets the first array item to the
      // full-text match of the pattern.
      if(preg_match($preg, $url, $parameters)) {
        return self::$route = new Route($method, $pattern, $action, array_slice($parameters, 1));
      }    
    
    }
  
  }

  /**
   * Translate route URI wildcards into regular expressions.
   *
   * @param  string  $uri
   * @return string
   */
  static protected function wildcards($pattern) {
      
    $search  = array_keys(self::$optional);
    $replace = array_values(self::$optional);

    // For optional parameters, first translate the wildcards to their
    // regex equivalent, sans the ")?" ending. We'll add the endings
    // back on when we know the replacement count.
    $pattern = str_replace($search, $replace, $pattern, $count);

    if($count > 0) $pattern .= str_repeat(')?', $count);
    
    return strtr($pattern, self::$patterns);
  
  }

}