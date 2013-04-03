<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Email Validator
 * 
 * Checks for a valid email address
 * 
 * @package Kirby
 */
class EmailValidator extends Validator {

  public $message = 'The :attribute must be a valid email';

  public function validate() {
    return filter_var($this->value, FILTER_VALIDATE_EMAIL) !== false;
  }

}