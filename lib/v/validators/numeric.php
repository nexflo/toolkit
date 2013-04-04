<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Numeric Validator
 * 
 * Checks for a valid numeric value
 * 
 * @package Kirby
 */
class NumericValidator extends Validator {

  public $message = 'The :attribute must be a number';

  public function validate() {
    return is_numeric($this->value);
  }

}