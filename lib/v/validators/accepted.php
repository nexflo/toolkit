<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Accepted Validator
 * 
 * Checks for an activated checkbox input 
 * 
 * @package Kirby
 */
class AcceptedValidator extends Validator {

  public $message = 'The :attribute must be accepted';

  public function validate() {
    return v::in($this->value, array('yes', '1', 'on'));
  }

}