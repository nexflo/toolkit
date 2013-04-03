<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * In Validator
 * 
 * Checks for a value contained in a list of values
 * 
 * @package Kirby
 */
class InValidator extends Validator {

  public $message = 'The :attribute must be in: :in';

  public function vars() {
    return array(
      'in' => implode(', ', $this->options)
    );
  }

  public function validate() {    
    return in_array($this->value, $this->options);
  }

}