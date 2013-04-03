<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * IP Validator
 * 
 * Checks for a valid ip
 * 
 * @package Kirby
 */
class IpValidator extends Validator {

  public $message = 'The :attribute must be a valid IP';

  public function validate() {
    return filter_var($this->value, FILTER_VALIDATE_IP) !== false;
  }

}