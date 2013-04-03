<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Filename Validator
 * 
 * Checks for a valid filename format
 * 
 * @package Kirby
 */
class FilenameValidator extends Validator {

  public $message = 'The :attribute must be a valid filename';

  public function validate() {
    return v::match($this->value, '/^[a-z0-9@._-]+$/i') and v::min($this->value, 2);
  }

}