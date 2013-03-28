<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * URL
 * 
 * A bunch of handy methods to work with URLs
 * 
 * @package Kirby
 */
class Url {
  
  /** 
    * Returns the current URL
    * 
    * @return string
    */
  static public function current() {    
    return r::scheme() . '://' . server::get('http_host') . server::get('request_uri');
  }

  /**
    * Shortens a URL
    * It removes http:// or https:// and uses str::short afterwards
    *
    * @param  string  $url The URL to be shortened
    * @param  int     $chars The final number of characters the URL should have
    * @param  boolean $base True: only take the base of the URL. 
    * @param  string  $rep The element, which should be added if the string is too long. Ellipsis is the default.
    * @return string  The shortened URL  
    */  
  static public function short($url, $chars=false, $base=false, $rep='…') {
    $url = str_replace('http://','',$url);
    $url = str_replace('https://','',$url);
    $url = str_replace('ftp://','',$url);
    $url = str_replace('www.','',$url);
    
    if($base) {
      $a = explode('/', $url);
      $url = a::get($a, 0);
    }

    // try to remove the last / after the url
    $url = preg_replace('!(\/)$!', '', $url);

    return ($chars) ? str::short($url, $chars, $rep) : $url;
  }

  /** 
    * Checks if the URL has a query string attached
    * 
    * @param  string  $url
    * @return boolean
    */
  static public function hasQuery($url) {
    return (str::contains($url, '?')) ? true : false;
  }

  /** 
    * Strips the query from the URL
    * 
    * @param  string  $url
    * @return string
    */
  static public function stripQuery($url) {
    return preg_replace('/\?.*$/is', '', $url);
  }

  /** 
    * Strips a hash value from the URL
    * 
    * @param  string  $url
    * @return string
    */
  static public function stripHash($url) {
    return preg_replace('/#.*$/is', '', $url);
  }

  /** 
    * Checks for a valid URL
    * 
    * @param  string  $url
    * @return boolean
    */
  static public function valid($url) {
    return v::url($url);
  }

}
