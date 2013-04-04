<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// dependencies
require_once(ROOT_KIRBY_TOOLKIT_LIB . DS . 'v' . DS . 'validator.php');
require_once(ROOT_KIRBY_TOOLKIT_LIB . DS . 'v' . DS . 'errors.php');

/**
 * 
 * Validation
 * 
 * Runs a set of valdiators against a set of data
 * 
 * @package Kirby
 */
class Validation {

  // the input data
  protected $data = array();
  
  // an array of rules, which should be validated
  protected $rules = array();
  
  // a custom list of error messags for used validators
  protected $messages = array();
  
  // a list of custom attribute names, which should be used for building messages
  protected $attributes = array();
  
  // a list of errors for each attribute
  protected $errors = null;

  /**
   * Constructor
   * 
   * @param array $data A set of data to be validated
   * @param array $rules A set of rules for the validation
   * @param array $messages A set of custom error messages
   * @param array $attributes A set of attribute translations
   */
  public function __construct($data, $rules, $messages = array(), $attributes = array()) {

    $this->data       = $data;
    $this->rules      = $rules;
    $this->messages   = $messages;
    $this->attributes = $attributes;
    $this->errors     = new Collection;

    foreach($rules as $attribute => $methods) {

      foreach($methods as $method => $options) {

        // if the key is used as method name
        if(is_numeric($method)) {
          $method  = $options;
          $options = false;
        }

        // create a new validator and run the validation
        $validator = Validator::create($method, $data, $attribute, $options);

        // add a new error for this validator
        if($validator->failed()) $this->raise($validator);

      }

    }

  }

  /**
   * Returns the entire collection of errors
   * 
   * @return object Collection
   */
  public function errors() {
    return $this->errors;
  }

  /**
   * Returns a specific set of errors for a given attribute
   * 
   * @param string $attribute if not specified the first error will be returned
   * @return object ValidationErrors
   */
  public function error($attribute = null) {
    return (is_null($attribute)) ? $this->errors->first() : $this->errors->get($attribute);
  }

  /**
   * Returns the first message of the first error
   * 
   * @return string
   */
  public function message() {
    return $this->error()->message();
  }

  /**
   * Checks if the validation failed
   * 
   * @return boolean
   */
  public function failed() {
    return $this->errors->count() > 0;
  }

  /**
   * Checks if the validation succeeded
   * 
   * @return boolean
   */
  public function passed() {
    return !$this->failed();
  }

  /**
   * Internal method to raise a new error for an attribute/validator
   * 
   * @param object Validator
   */
  protected function raise(Validator $validator) {

    // get the used validation method from the validator
    $method = $validator->method();

    // try to find a custom attribute name 
    $attribute = a::get($this->attributes, $validator->attribute());
    
    // try to find a custom message for the error
    $message = a::get($this->messages, $method);
    
    // pass custom message and attribute to the validator
    $error = $validator->error($message, $attribute); 

    if(!isset($this->errors->$attribute)) {
      // create a new set of errors if no error exists so far
      $this->errors->$attribute = new ValidationErrors();
      $this->errors->$attribute->$method = $error;
    } else {
      // add a new message to the existing list of errors
      $this->errors->$attribute->$method = $error;
    }

  }

}