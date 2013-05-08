<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * String Validator
 * 
 * Checks for a valid string
 * 
 * @package Kirby Toolkit 
 * @author Bastian Allgeier <bastian@getkirby.com>
 * @link http://getkirby.com
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class StringValidator extends Validator {

  public $message = 'The :attribute must be a string';

  public function validate() {
    return is_string($this->value);
  }

}