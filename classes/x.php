<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * XML
 * 
 * The Kirby XML Parser Class
 * 
 * @package Kirby
 */
class X {

  /**
    * Converts an array to a XML string
    * 
    * @param  array   $array The source array
    * @param  string  $tag The name of the root element
    * @param  boolean $head Include the xml declaration head or not
    * @param  string  $charset The charset, which should be used for the header
    * @param  int     $level The indendation level
    * @return string  The XML string
    */
  static public function create($array, $tag = 'root', $head = true, $charset = 'utf-8', $tab = '  ', $level = 0) {
    $result  = ($level==0 && $head) ? '<?xml version="1.0" encoding="' . $charset . '"?>' . "\n" : '';
    $nlevel  = ($level+1);
    $result .= str_repeat($tab, $level) . '<' . $tag . '>' . "\n";
    foreach($array AS $key => $value) {
      $key = str::lower($key);
      if(is_array($value)) {
        $mtags = false;
        foreach($value AS $key2 => $value2) {
          if(is_array($value2)) {
            $result .= self::xml($value2, $key, $head, $charset, $tab, $nlevel);
          } else if(trim($value2) != '') {
            $value2  = (htmlspecialchars($value2) != $value2) ? '<![CDATA[' . $value2 . ']]>' : $value2;
            $result .= str_repeat($tab, $nlevel) . '<' . $key . '>' . $value2 . '</' . $key . '>' . "\n";
          }
          $mtags = true;
        }
        if(!$mtags && count($value) > 0) {
          $result .= static::xml($value, $key, $head, $charset, $tab, $nlevel);
        }
      } else if(trim($value) != '') {
        $value   = (htmlspecialchars($value) != $value) ? '<![CDATA[' . $value . ']]>' : $value;
        $result .= str_repeat($tab, $nlevel) . '<' . $key . '>' . $value . '</' . $key . '>' . "\n";
      }
    }
    return $result . str_repeat($tab, $level) . '</' . $tag . '>' . "\n";
  }

  /**
    * Converts a string to a xml-safe string
    * Converts it to html-safe first and then it
    * will replace html entities to xml entities
    *
    * @param  string  $text
    * @param  boolean $html True: convert to html first
    * @return string
    */  
  static public function encode($string, $html = true) {

    // convert raw text to html safe text
    if($html) $text = html::encode($string, false);

    // convert html entities to xml entities
    return strtr($text, html::entities());

  }

  /**
    * Removes all xml entities from a string
    * and convert them to html entities first
    * and remove all html entities afterwards.
    *
    * @param  string  $string
    * @return string
    */  
  static public function decode($string) {
    // convert xml entities to html entities
    $string = strtr($string, static::entities());
    return html::decode($string);
  }  

  /** 
    * Parses a XML string and returns an array
    * 
    * @param  string  $xml
    * @return mixed
    */
  static public function parse($xml) {

    $xml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$2$3', $xml);
    $xml = @simplexml_load_string($xml, null, LIBXML_NOENT);
    $xml = @json_encode($xml);
    $xml = @json_decode($xml, true);
    return (is_array($xml)) ? $xml : false;

  }

  /**
   * Returns a translation table of xml entities to html entities
   * 
   * @return array
   */
  static public function entities() {
    return array_flip(html::entities());    
  }

}
