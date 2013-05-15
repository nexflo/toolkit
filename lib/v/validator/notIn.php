<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Not In Validator
 * 
 * Checks for a value not contained in a list of values
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class NotInValidator extends Validator {

  public $message = 'The :attribute must not be in: :in';

  public function vars() {
    return array(
      'in' => implode(', ', $this->options)
    );
  }

  public function validate() {    
    return !v::in($this->value, $this->options);
  }

}