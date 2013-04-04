<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Different Validator
 * 
 * Checks for two different values
 * 
 * @package Kirby
 */
class DifferentValidator extends Validator {

  public $message = 'The :attribute and :other must be different';

  public function vars() {
    return array(
      'other' => $this->options
    );
  }

  public function validate() {
    return $this->value != $this->options;  
  }

}