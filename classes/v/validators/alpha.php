<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Alpha Validator
 * 
 * Checks letter-only values
 * 
 * @package Kirby
 */
class AlphaValidator extends Validator {

  public $message = 'The :attribute may only contain letters.';

  public function validate() {
    return v::match($this->value, '/^([a-z])+$/i');    
  }

}