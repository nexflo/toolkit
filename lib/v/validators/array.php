<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Array Validator
 * 
 * Checks for arrays
 * 
 * @package Kirby
 */
class ArrayValidator extends Validator {

  public $message = 'The :attribute must be an array';

  public function validate() {
    return is_array($this->value);
  }

}