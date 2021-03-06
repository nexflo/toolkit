<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Array 
 *
 * This class is supposed to simplify array handling
 * and make it more consistent. 
 * 
 * @package Kirby
 */
class A {

  /**
   * Sets a value for any key or sub key in the array
   * 
   * @param array $array
   * @param string $key Use > notation to acccess deeper nested array keys
   * @param mixed $value
   */
  static public function set(&$array, $key, $value) {

    if(is_null($key)) return $array = $value;

    $keys = str::split($key, '>');

    while(count($keys) > 1) {
      $key = array_shift($keys);

      // If the key doesn't exist at this depth, we will just create an
      // empty array to hold the next value, allowing us to create the
      // arrays to hold the final value.
      if(!isset($array[$key]) or ! is_array($array[$key])) {
        $array[$key] = array();
      }

      $array =& $array[$key];
    
    }
    
    $array[array_shift($keys)] = $value;
  
  }

  /**
   * Gets an element of an array by key
   *
   * @param  array    $array The source array
   * @param  mixed    $key The key to look for
   * @param  mixed    $default Optional default value, which should be returned if no element has been found
   * @return mixed
   */
  static public function get($array, $key, $default = null) {

    if(isset($array[$key])) return $array[$key];
  
    if(str::contains($key, '>')) {
      foreach(str::split($key, '>') as $segment) {
        if(!is_array($array) or !array_key_exists($segment, $array)) return $default;
        $array = $array[$segment];
      }      
      return $array;
    } else {
      return $default;
    }

  }
  
  /**
   * Removes an element from an array
   * 
   * @param  array   $array The source array
   * @param  mixed   $search The value or key to look for
   * @param  boolean $key Pass true to search for an key, pass false to search for an value.   
   * @return array   The result array without the removed element
   */
  static public function remove($array, $search, $key = true) {
    if($key) {
      unset($array[$search]);
    } else {
      $found = false;
      while(!$found) {
        $index = array_search($search, $array);
        if($index !== false) {
          unset($array[$index]);
        } else {
          $found = true;
        }
      }
    }
    return $array;
  }

  /**
   * Alternative for a::remove with $key set to true
   * 
   * @see a::remove() 
   */
  static public function removeKey($array, $search) {
    self::remove($array, $search, true);
  }

  /**
   * Alternative for a::remove with $key set to false
   * 
   * @see a::remove() 
   */
  static public function removeValue($array, $search) {
    self::remove($array, $search, false);
  }

  /**
   * Injects an element into an array
   * 
   * @param  array   $array The source array
   * @param  int     $position The position, where to inject the element
   * @param  mixed   $element The element, which should be injected
   * @return array   The result array including the new element
   */
  static public function inject($array, $position, $element = 'placeholder') {
    $start = array_slice($array, 0, $position);
    $end = array_slice($array, $position);
    return array_merge($start, (array)$element, $end);
  }

  /**
   * Shows an entire array or object in a human readable way
   * This is perfect for debugging
   * 
   * @param  array   $array The source array
   * @param  boolean $echo By default the result will be echoed instantly. You can switch that off here. 
   * @return mixed   If echo is false, this will return the generated array output.
   */
  static public function show($array, $echo = true) {
    if(r::cli()) {
      $output = print_r($array, true) . PHP_EOL;
    } else {
      $output  = '<pre>';
      $output .=  htmlspecialchars(print_r($array, true));
      $output .= '</pre>';
    }
    if($echo == true) echo $output;
    return $output;
  }

  /**
   * Converts an array to a JSON string
   * It's basically a shortcut for json_encode()
   * 
   * @param  array   $array The source array
   * @return string  The JSON string
   */
  static public function json($array) {
    return json_encode((array)$array);
  }

  /**
   * Converts an array to a XML string
   * 
   * @param  array   $array The source array
   * @param  string  $tag The name of the root element
   * @param  boolean $head Include the xml declaration head or not
   * @param  string  $charset The charset, which should be used for the header
   * @param  int     $level The indendation level
   * @return string  The XML string
   */
  static public function xml($array, $tag = 'root', $head = true, $charset = 'utf-8', $tab = '  ', $level = 0) {
    return xml::create($array, $tag, $head, $charset, $tab, $level);
  }

  /**
   * Extracts a single column from an array
   * 
   * @param  array   $array The source array
   * @param  string  $key The key name of the column to extract
   * @return array   The result array with all values from that column. 
   */
  static public function extract($array, $key) {
    $output = array();
    foreach($array AS $a) if(isset($a[$key])) $output[] = $a[ $key ];
    return $output;
  }

  /**
   * Shuffles an array and keeps the keys
   * 
   * @param  array   $array The source array
   * @return array   The shuffled result array
   */
  static public function shuffle($array) {

    $keys = array_keys($array); 
    $new  = array();

    shuffle($keys);

    // resort the array
    foreach($keys as $key) $new[$key] = $array[$key];
    return $new;

  } 

  /**
   * Returns the first element of an array
   *
   * I always have to lookup the names of that function
   * so I decided to make this shortcut which is 
   * easier to remember.
   *
   * @param  array   $array The source array
   * @return mixed   The first element
   */
  static public function first($array) {
    return array_shift($array);
  }

  /**
   * Returns the last element of an array
   *
   * I always have to lookup the names of that function
   * so I decided to make this shortcut which is 
   * easier to remember.
   * 
   * @param  array   $array The source array
   * @return mixed   The last element
   */
  static public function last($array) {
    return array_pop($array);
  }

  /**
   * Fills an array up with additional elements to certain amount. 
   *
   * @param  array   $array The source array
   * @param  int     $limit The number of elements the array should contain after filling it up. 
   * @param  mixed   $fill The element, which should be used to fill the array
   * @return array   The filled-up result array
   */
  static public function fill($array, $limit, $fill='placeholder') {
    if(count($array) < $limit) {
      $diff = $limit-count($array);
      for($x=0; $x<$diff; $x++) $array[] = $fill;
    }
    return $array;
  }

  /**
   * Checks for missing elements in an array
   *
   * This is very handy to check for missing 
   * user values in a request for example. 
   * 
   * @param  array   $array The source array
   * @param  array   $required An array of required keys
   * @return array   An array of missing fields. If this is empty, nothing is missing. 
   */
  static public function missing($array, $required=array()) {
    $missing = array();
    foreach($required AS $r) {
      if(empty($array[$r])) $missing[] = $r;
    }
    return $missing;
  }

  /**
   * Sorts a multi-dimensional array by a certain column
   *
   * @param  array   $array The source array
   * @param  string  $field The name of the column
   * @param  string  $direction desc (descending) or asc (ascending)
   * @param  const   $method A PHP sort method flag or 'natural' for natural sorting, which is not supported in PHP by sort flags 
   * @return array   The sorted array
   */
  static public function sort($array, $field, $direction = 'desc', $method = SORT_REGULAR) {

    $direction = (strtolower($direction) == 'desc') ? SORT_DESC : SORT_ASC;
    $helper    = array();

    foreach($array as $key => $row) {      
      $helper[$key] = (is_object($row)) ? (method_exists($row, $field)) ? str::lower($row->$field()) : str::lower($row->$field) : str::lower($row[$field]);
    }

    // natural sorting    
    if($method === 'natural') {
      natsort($helper);
      if($direction === SORT_DESC) $helper = array_reverse($helper);
    } else if($direction === SORT_DESC) {
      arsort($helper, $method);
    } else {
      asort($helper, $method);
    }

    $result = array();
    
    foreach($helper as $key => $val) {
      $result[$key] = $array[$key];
    }
    
    return $result;
    
  }

}