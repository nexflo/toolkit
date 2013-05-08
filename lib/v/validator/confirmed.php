<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Confirmed Validator
 * 
 * Checks for a confirmation field with the same value
 * 
 * @package Kirby Toolkit 
 * @author Bastian Allgeier <bastian@getkirby.com>
 * @link http://getkirby.com
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class ConfirmedValidator extends Validator {

  public $message = 'The :attribute must be confirmed';

  public function validate() {
    // check for an existing confirmation field and make sure it matches the current value
    return v::same($this->value, $this->data[$this->attribute . '_confirmation']);
  }

}