<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * String Validator
 * 
 * Checks for a valid string
 * 
 * @package Kirby
 */
class StringValidator extends Validator {

  public $message = 'The :attribute must be a string';

  public function validate() {
    return is_string($this->value);
  }

}