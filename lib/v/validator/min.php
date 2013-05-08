<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Min Validator
 * 
 * Checks for a value with a size at least the min value
 * 
 * @package Kirby Toolkit 
 * @author Bastian Allgeier <bastian@getkirby.com>
 * @link http://getkirby.com
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class MinValidator extends Validator {

  public $message = array(
    'numeric' => 'The :attribute must be at least :min.',
    'string'  => 'The :attribute must be at least :min characters.',
    'file'    => 'The :attribute must be at least :min kilobytes',
    'array'   => 'The :attribute must be at least :min elements'
  );

  public function vars() {
    return array(
      'min' => $this->options
    );
  }

  public function validate() {    
    return size($this->value) >= $this->options;
  }

}