<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// dependencies
require_once(ROOT_KIRBY_TOOLKIT_CLASSES . DS . 'v' . DS . 'validation.php');

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
   * Runs a full validation for an entire set of data and rules
   * 
   * @param array $data
   * @param array $rules specify a set of rules for validation
   * @param array $messages Overwrite default validation messages for each method here
   * @param array $attributes Overwrite default attribute names
   * @return object Validation
   */
  static public function all($data, $rules = array(), $messages = array(), $attributes = array()) {
    return new Validation($data, $rules, $messages, $attributes);
  }

  /** 
   * Checks if the value matches a regular expression
   * 
   * @param  string   $value
   * @param  string   $format
   * @return boolean
   */
  static public function match($value, $format) {
    return Validator::create('match', $value, null, $format)->passed();
  }

  /** 
   * Checks for two valid, matching values
   * 
   * @param  string  $value
   * @param  string  $other
   * @return boolean
   */
  static public function same($value, $other) {
    return Validator::create('same', $value, null, $other)->passed();
  }

  /** 
   * Checks for two different values
   * 
   * @param  string  $value
   * @param  string  $other
   * @return boolean
   */
  static public function different($value, $other) {
    return Validator::create('different', $value, null, $other)->passed();
  }

  /** 
   * Checks for valid date
   * 
   * @param  string  $value
   * @return boolean
   */
  static public function date($value) {
    return Validator::create('date', $value, null)->passed();
  }

  /** 
   * Checks for valid email address
   * 
   * @param  string  $value
   * @return boolean
   */
  static public function email($value) {
    return Validator::create('email', $value, null)->passed();
  }

  /** 
   * Checks for valid URL
   * 
   * @param  string  $value
   * @return boolean
   */
  static public function url($value) {
    return Validator::create('url', $value, null)->passed();
  }

  /** 
   * Checks for valid filename
   * 
   * @param  string  $value
   * @return boolean
   */
  static public function filename($value) {
    return Validator::create('filename', $value, null)->passed();
  }

  /**
   * Checks for an activated checkbox value
   * 
   * @param string $value
   * @return boolean
   */
  static function accepted($value) {
    return Validator::create('accepted', $value, null)->passed();
  }

  /**
   * Checks for a min size/count/length of a given value
   * The value may be a string, array or file
   * 
   * @param mixed $value 
   * @param int $min
   * @return boolean
   */
  static public function min($value, $min) {
    return Validator::create('min', $value, null, $min)->passed();
  }

  /**
   * Checks for a max size/count/length of a given value
   * The value may be a string, array or file
   * 
   * @param mixed $value 
   * @param int $max
   * @return boolean
   */
  static public function max($value, $max) {
    return Validator::create('max', $value, null, $max)->passed();
  }

  /**
   * Checks if the size/count/length of a given value
   * is between a minimum and maximumn value. 
   * The value may be a string, array or file
   * 
   * @param mixed $value 
   * @param int $min
   * @param int $max
   * @return boolean
   */
  static public function between($value, $min, $max) {
    return Validator::create('between', $value, null, array($min, $max))->passed();
  }

  /**
   * Checks if the value is included in an array of values
   * 
   * @param mixed $value
   * @param array $values
   * @return boolean
   */
  static public function in($value, $values = array()) {
    return Validator::create('in', $value, null, $values)->passed();
  }

  /**
   * Checks if the value is not included in an array of values
   * 
   * @param mixed $value
   * @param array $values
   * @return boolean
   */
  static public function notIn($value, $values = array()) {
    return Validator::create('notIn', $value, null, $values)->passed();
  }

  /**
   * Checks if the value is a valid ip
   * 
   * @param mixed $value
   * @return boolean
   */
  static public function ip($value) {
    return Validator::create('ip', $value, null)->passed();
  }

  /**
   * Checks if the value contains only alpha chars
   * 
   * @param mixed $value
   * @return boolean
   */
  static public function alpha($value) {
    return Validator::create('alpha', $value, null)->passed();
  }

  /**
   * Checks if the value contains only numbers
   * 
   * @param mixed $value
   * @return boolean
   */
  static public function numeric($value) {
    return Validator::create('numeric', $value, null)->passed();
  }

  /**
   * Checks if the value contains only numbers and chars from a-z
   * 
   * @param mixed $value
   * @return boolean
   */
  static public function alphaNumeric($value) {
    return Validator::create('alphaNumeric', $value, null)->passed();
  }

  /**
   * Checks for a valid integer value
   * 
   * @param int $value
   * @return boolean
   */
  static public function integer($value) {
    return Validator::create('integer', $value, null)->passed();
  }

  /**
   * Compares the size of the value with a given size
   * 
   * @param mixed $value
   * @param int $size
   * @return boolean
   */
  static public function size($value, $size) {
    return Validator::create('size', $value, null, $size)->passed();
  }

}