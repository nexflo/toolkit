<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Same Validator
 * 
 * Checks for a value which is the same than the other passed value
 * 
 * @package Kirby
 */
class SameValidator extends Validator {

  public $message = 'The :attribute and :other must be the same';

  public function vars() {
    return array(
      'other' => $this->options
    );
  }

  public function validate() {
    return $this->value == $this->options;  
  }

}