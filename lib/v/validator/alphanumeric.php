<?php

/**
 * Alpha Numeric Validator
 * 
 * Checks for letters and numbers
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class AlphaNumericValidator extends Validator {

  public $message = 'The :attribute may only contain letters from a-z and numbers from 0-9.';

  public function validate() {
    return v::match($this->value, '/^[a-z0-9]+$/i');
  }

}