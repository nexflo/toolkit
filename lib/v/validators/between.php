<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Between Validator
 * 
 * Checks for the size of the value being between the first and second argument
 * 
 * @package Kirby
 */
class BetweenValidator extends Validator {

  public $message = array(
    'numeric' => 'The :attribute must be at least :min and less than :max.',
    'string'  => 'The :attribute must be at least :min and less than :max characters.',
    'file'    => 'The :attribute must be at least :min and less than :max kilobytes',
    'array'   => 'The :attribute must be at least :min and less than :max elements'
  );

  public function vars() {
    return array(
      'min' => $this->options[0],
      'max' => $this->options[1]
    );
  }

  public function validate() {    
    return v::min($this->value, $this->options[0]) and v::max($this->value, $this->options[1]);
  }

}