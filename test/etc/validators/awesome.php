<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

class AwesomeValidator extends Validator {

  public $message = 'The :attribute must be awesome';

  public function validate() {
    return $this->value == 'awesome';
  }

}