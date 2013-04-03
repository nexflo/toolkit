<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Template
 *
 * This is Kirby's super minimalistic 
 * template engine. It loads and fills 
 * templates. Who would have thought that
 * 
 * @package Kirby
 */
class Tpl {

  // all global variables which will be passed to the templates
  static public $data = array();

  /**
   * Sets a new template variable
   * 
   * @param string $key
   * @param string $value
   */
  static public function set($key, $value=false) {
    if(is_array($key)) {
      self::$data = array_merge(self::$data, $key);
    } else {
      self::$data[$key] = $value;
    }
  }

  /**
   * Returns a template variable by key
   * 
   * @param string $key
   * @param string $default
   * @return mixed
   */
  static public function get($key = null, $default = null) {
    if(is_null($key)) return (array)self::$data;
    return a::get(self::$data, $key, $default);       
  }

  /**
   * Loads a template and returns its output
   * 
   * @param string $template The name of the template. The template must be located within the template folder (root.templates)
   * @param string $vars Additional template vars to pass to the template
   * @param boolean $return true: html will be returned, false: html will be echoed directly
   * @return string
   */
  static public function load($template = 'default', $data = array(), $return = false) {    
    $file = c::get('tpl.root') . DS . $template . '.php';
    return self::loadFile($file, $data, $return);
  }
  
  /**
   * Loads a specific template file and returns its output
   * 
   * @param string $file The full root to the template file
   * @param string $vars Additional template vars to pass to the template
   * @param boolean $return true: html will be returned, false: html will be echoed directly
   * @return string
   */
  static public function loadFile($file, $data = array(), $return = false) {

    // check for an existing template file
    if(!file_exists($file)) return false;

    // always make sure to work with an array
    if(!is_array($data)) $data = array();

    return content::load($file, array_merge(self::$data, $data), $return);

  }

}
