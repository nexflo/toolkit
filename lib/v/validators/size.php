<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Size Validator
 * 
 * Checks the size of the value
 * 
 * @package Kirby
 */
class SizeValidator extends Validator {

  public $message = array(
    'numeric' => 'The :attribute must be :size.',
    'file'    => 'The :attribute must be :size kilobyte.',
    'string'  => 'The :attribute must be :size characters.',
    'array'   => 'The :attribute must be :size elements.',
  );

  public function vars() {
    return array(
      'size' => $this->options
    );
  }

  public function validate() {
    return size($this->value) == $this->options;
  }

}