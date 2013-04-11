<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Redirects the user to a new URL
 *
 * @param   string    $url The URL to redirect to
 * @param   boolean   $code The HTTP status code, which should be sent (301, 302 or 303)
 * @param   boolean   $send If true, headers will be sent and redirection will take effect
 * @package Kirby
 */
function go($url = false, $code = false, $send = true) {

  if(empty($url)) $url = c::get('url', '/');

  $header = false;

  // send an appropriate header
  if($code) {
    switch($code) {
      case 301:
        $header = 'HTTP/1.1 301 Moved Permanently';
        break;
      case 302:
        $header = 'HTTP/1.1 302 Found';
        break;
      case 303:
        $header = 'HTTP/1.1 303 See Other';
        break;
    }
  }
  
  if($send) {
    // send to new page
    if($header) header($header);
    header('Location:' . $url);
    exit();
  } else {
    return $header;
  }

}

/**
  * Shortcut for r::get()
  *
  * @param   mixed    $key The key to look for. Pass false or null to return the entire request array. 
  * @param   mixed    $default Optional default value, which should be returned if no element has been found
  * @return  mixed
  */  
function get($key = false, $default = null) {
  return r::data($key, $default);
}

/**
 * Get a parameter from the current URI object
 * 
  * @param   mixed    $key The key to look for. Pass false or null to return the entire params array. 
  * @param   mixed    $default Optional default value, which should be returned if no element has been found
  * @return  mixed
 */
function param($key = null, $default = null) {
  return uri::current()->param($key, $default);
}

/**
 * Smart version of return with an if condition as first argument
 * 
 * @param boolean $condition
 * @param string $value The string to be returned if the condition is true
 * @param string $alternative An alternative string which should be returned when the condition is false
 */
function r($condition, $value, $alternative = null) {
  return ($condition) ? $value : $alternative;
}

/**
 * Smart version of echo with an if condition as first argument
 * 
 * @param boolean $condition
 * @param string $value The string to be echoed if the condition is true
 * @param string $alternative An alternative string which should be echoed when the condition is false
 */
function e($condition, $value, $alternative = null) {
  echo r($condition, $value, $alternative);
}

/**
 * Alternative for e()
 * 
 * @see e()
 */
function ecco($condition, $value, $alternative = null) {
  e($condition, $value, $alternative);
}

/**
 * Returns a language string
 * 
 * @param mixed $key
 * @param mixed $default
 * @return string
 */
function l($key = null, $default = null) {
  return l::get($key, $default);
}

/**
 * Shortcut for a::show()
 * 
 * @see a::show()
 * @param mixed $variable Whatever you like to inspect
 */ 
function dump($variable) {
  a::show($variable);
}

/**
 * Generates a single attribute or a list of attributes
 * 
 * @see html::attr();
 * @param string $name mixed string: a single attribute with that name will be generated. array: a list of attributes will be generated. Don't pass a second argument in that case. 
 * @param string $value if used for a single attribute, pass the content for the attribute here
 * @return string the generated html
 */
function attr($name, $value = null) {
  return html::attr($name, $value);
}  

/**
 * Creates safe html by encoding special characters
 * 
 * @param string $text unencoded text
 * @return string
 */
function html($text, $keepTags = true) {
  return html::encode($text, $keepTags);
}

/**
 * Shortcut for html()
 * 
 * @see html()
 */
function h($text, $keepTags = true) {
  return html::encode($text, $keepTags);
}

/**
 * Creates safe xml by encoding special characters
 * 
 * @param string $text unencoded text
 * @return string
 */
function xml($text) {
  return xml::encode($text);
}

/**
 * Converts new lines to html breaks
 * 
 * @param string $text with new lines
 * @return string
 */
function multiline($text) {
  return html::breaks(html::encode($text));
}

/**
 * The widont function makes sure that there are no 
 * typographical widows at the end of a paragraph –
 * that's a single word in the last line
 * 
 * @param string $string
 * @return string
 */
function widont($string = '') {
  return str::widont($string);
}

/**
 * Returns the memory usage in a readable format
 * 
 * @return string
 */
function memory() {
  return f::niceSize(memory_get_usage());
}

/**
 * Determines the size/length of numbers, strings, arrays and files 
 *
 * @param mixed $value 
 * @return int
 */
function size($value) {
  if(is_numeric($value)) return $value; 
  if(is_string($value))  return str::length(trim($value));
  if(is_array($value))   return count($value);
  if(f::exists($value))  return f::size($value) / 1024;
}

/**
 * Generates a gravatar image link
 * 
 * @param string $email
 * @param int $size
 * @param string $default 
 * @return string
 */
function gravatar($email, $size = 256, $default = 'mm') {
  return 'https://gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?d=' . urlencode($default) . '&s=' . $size;  
}