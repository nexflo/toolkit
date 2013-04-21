<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Password encryption class
 * 
 * @package Kirby Toolkit
 */
class Password {

  /**
   * Generates a salted hash for a plaintext password
   * 
   * @param string $plaintext
   * @return string
   */
  static public function hash($plaintext) {
    $salt = substr(str_replace('+', '.', base64_encode(sha1(str::random(), true))), 0, 22);
    return crypt($plaintext, '$2a$10$' . $salt);
  }

  /**
   * Checks if a password matches the encrypted hash
   * 
   * @param string $plaintext
   * @param string $hash
   * @return boolean
   */
  static public function match($plaintext, $hash) {
    return crypt($plaintext, $hash) === $hash;
  }  

}