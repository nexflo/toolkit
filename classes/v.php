<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Validator
 * 
 * Makes input validation easier
 * 
 * @package Kirby
 */
class V {

  /** 
    * Core method to create a new validator
    * 
    * @param  string  $string
    * @param  array   $options
    * @return boolean
    */
  static public function string($string, $options) {
    $format = null;
    $minLength = $maxLength = 0;
    if(is_array($options)) extract($options);

    if($format && !preg_match('/^[' . $format . ']*$/is', $string)) return false;
    if($minLength && str::length($string) < $minLength) return false;
    if($maxLength && str::length($string) > $maxLength) return false;
    return true;
  }

  /** 
    * Checks for two valid, matching strings
    * 
    * @param  string  $string1
    * @param  string  $string2
    * @return boolean
    */
  static public function match($string1, $string2) {
    return ($string1 === $string2) ? true : false;
  }

  /** 
    * Checks for valid date
    * 
    * @param  string  $date
    * @return boolean
    */
  static public function date($date) {
    $time = strtotime($date);
    if(!$time) return false;

    $year  = date('Y', $time);
    $month = date('m', $time);
    $day   = date('d', $time);

    return (checkdate($month, $day, $year)) ? $time : false;

  }

  /** 
    * Checks for valid email address
    * 
    * @param  string  $email
    * @return boolean
    */
  static public function email($email) {
    $regex = '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i';
    return (preg_match($regex, $email)) ? true : false;
  }

  /** 
    * Checks for valid URL
    * 
    * @param  string  $url
    * @return boolean
    */
  static public function url($url) {
    $regex = '/^(https?|ftp|rmtp|mms|svn):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i';
    return (preg_match($regex, $url)) ? true : false;
  }

  /** 
    * Checks for valid filename
    * 
    * @param  string  $string
    * @return boolean
    */
  static public function filename($string) {

    $options = array(
      'format' => 'a-zA-Z0-9_-.',
      'minLength' => 2,
    );

    return self::string($string, $options);

  }

  /**
   * Checks if the value is included in an array of values
   * 
   * @param mixed $string
   * @param array $values
   * @return boolean
   */
  static public function in($string, $values = array()) {
    return in_array($string, $values);
  }

  /**
   * Checks if the value is not included in an array of values
   * 
   * @param mixed $string
   * @param array $values
   * @return boolean
   */
  static public function notIn($string, $values = array()) {
    return !in_array($string, $values);
  }

  /**
   * Checks if the value is a valid ip
   * 
   * @param mixed $string
   * @return boolean
   */
  static public function ip($string) {
    return filter_var($string, FILTER_VALIDATE_IP) !== false;
  }

  /**
   * Checks if the value contains only alpha chars
   * 
   * @param mixed $string
   * @return boolean
   */
  static public function alpha($string) {
    return preg_match('/^([a-z])+$/i', $string);    
  }

  /**
   * Checks if the value contains only numbers
   * 
   * @param mixed $string
   * @return boolean
   */
  static public function num($string) {
    return preg_match('/^([0-9])+$/i', $string);
  }

  /**
   * Checks if the value contains only numbers and chars from a-z
   * 
   * @param mixed $string
   * @return boolean
   */
  static public function alphaNum($string) {
    return preg_match('/^([a-z0-9])+$/i', $string);
  }

}