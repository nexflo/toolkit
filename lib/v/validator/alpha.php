<?php

/**
 * Alpha Validator
 * 
 * Checks letter-only values
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class AlphaValidator extends Validator {

  public $message = 'The :attribute may only contain letters.';

  public function validate() {
    return v::match($this->value, '/^([a-z])+$/i');    
  }

}