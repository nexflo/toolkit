<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Integer Validator
 * 
 * Checks for a valid integer
 * 
 * @package Kirby Toolkit 
 * @author Bastian Allgeier <bastian@getkirby.com>
 * @link http://getkirby.com
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class IntegerValidator extends Validator {

  public $message = 'The :attribute may only contain integers.';

  public function validate() {
    return filter_var($this->value, FILTER_VALIDATE_INT) !== false;    
  }

}