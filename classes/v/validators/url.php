<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * URL Validator
 * 
 * Checks for a valid URL
 * 
 * @package Kirby
 */
class UrlValidator extends Validator {

  public $message = 'The :attribute must be a valid URL';

  public function validate() {
    return filter_var($this->value, FILTER_VALIDATE_URL) !== false;
  }

}