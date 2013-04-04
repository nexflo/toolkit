<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Alpha Numeric Validator
 * 
 * Checks for letters and numbers
 * 
 * @package Kirby
 */
class AlphaNumericValidator extends Validator {

  public $message = 'The :attribute may only contain letters from a-z and numbers from 0-9.';

  public function validate() {
    return v::match($this->value, '/^[a-z0-9]+$/i');
  }

}