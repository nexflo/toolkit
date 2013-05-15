<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Required Validator
 * 
 * Checks for a required value in the set of passed data
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class RequiredValidator extends Validator {

  public $message = 'The :attribute is required';

  public function validate() {
    return !empty($this->data[$this->attribute]);
  }

}