<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Confirmed Validator
 * 
 * Checks for a confirmation field with the same value
 * 
 * @package Kirby
 */
class ConfirmedValidator extends Validator {

  public $message = 'The :attribute must be confirmed';

  public function validate() {
    // check for an existing confirmation field and make sure it matches the current value
    return v::same($this->value, $this->data[$this->attribute . '_confirmation']);
  }

}