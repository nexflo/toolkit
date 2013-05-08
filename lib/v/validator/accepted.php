<?php

/**
 * Accepted Validator
 * 
 * Checks for an activated checkbox input 
 * 
 * @package Kirby Toolkit 
 * @author Bastian Allgeier <bastian@getkirby.com>
 * @link http://getkirby.com
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class AcceptedValidator extends Validator {

  public $message = 'The :attribute must be accepted';

  public function validate() {
    return v::in($this->value, array('yes', '1', 'on'));
  }

}