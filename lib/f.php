<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * File
 * 
 * This class makes it easy to 
 * create/edit/delete files
 * 
 * @package Kirby
 */
class F {

  /**
   * Checks if a file exists
   * 
   * @param string $file
   * @return boolean
   */
  static public function exists($file) {
    return file_exists($file);
  }

  /**
   * Safely requires a file if it exists
   */
  static public function load($file) {
    if(file_exists($file)) require_once($file);
  }
  
  /**
   * Creates a new file
   * 
   * @param  string  $file The path for the new file
   * @param  mixed   $content Either a string or an array. Arrays will be converted to JSON. 
   * @param  boolean $append true: append the content to an exisiting file if available. false: overwrite. 
   * @return boolean 
   */  
  static public function write($file,$content,$append=false){
    if(is_array($content)) $content = a::json($content);
    $mode = ($append) ? FILE_APPEND | LOCK_EX : LOCK_EX;
    if(file_put_contents($file, $content, $mode)) return true;
    return false;
  }

  /**
   * Appends new content to an existing file
   * 
   * @param  string  $file The path for the file
   * @param  mixed   $content Either a string or an array. Arrays will be converted to JSON. 
   * @return boolean 
   */  
  static public function append($file,$content){
    return self::write($file,$content,true);
  }
  
  /**
   * Reads the content of a file
   * 
   * @param  string  $file The path for the file
   * @param  mixed   $parse if set to true, parse the result with the passed method. See: "str::parse()" for more info about available methods. 
   * @return mixed 
   */  
  static public function read($file, $parse = false) {
    $content = f::exists($file) ? file_get_contents($file) : null;
    return ($parse) ? str::parse($content, $parse) : $content;
  }

  /**
   * Moves a file to a new location
   * 
   * @param  string  $old The current path for the file
   * @param  string  $new The path to the new location
   * @return boolean 
   */  
  static public function move($old, $new) {
    if(!f::exists($old)) return false;
    return rename($old, $new);
  }

  /**
   * Copy a file to a new location.
   *
   * @param  string  $file
   * @param  string  $target
   * @return boolean
   */
  static public function copy($file, $target) {
    return copy($file, $target);
  }

  /**
   * Deletes a file
   * 
   * @param  string  $file The path for the file
   * @return boolean 
   */  
  static public function remove($file) {
    return (file_exists($file) && is_file($file) && !empty($file)) ? unlink($file) : false;
  }

  /**
   * Gets the extension of a file
   * 
   * @param  string  $file The filename or path
   * @param  string  $extension Set an optional extension to overwrite the current one
   * @return string 
   */  
  static public function extension($file, $extension = false) {

    // overwrite the current extension
    if($extension) return self::name($file) . '.' . $extension;

    // return the current extension
    return pathinfo($file, PATHINFO_EXTENSION);
  
  }

  /**
   * Extracts the filename from a file path
   * 
   * @param  string  $file The path
   * @return string 
   */  
  static public function filename($name) {
    $name = basename($name);
    $name = url::stripQuery($name);
    $name = preg_replace('!\:.*!i', '', $name);
    $name = preg_replace('!\#.*!i', '', $name);
    return $name;
  }

  /**
   * Extracts the name from a file path or filename without extension
   * 
   * @param  string  $file The path or filename
   * @return string 
   */  
  static public function name($name) {
    $name = self::filename($name);
    $dot  = strrpos($name, '.');
    return ($dot) ? substr($name, 0, $dot) : $name;
  }

  /**
   * Just an alternative for dirname() to stay consistent
   * 
   * @param  string  $file The path
   * @return string 
   */  
  static public function dirname($file = __FILE__) {
    return dirname($file);
  }

  /**
   * Returns the size of a file.
   * 
   * @param  string  $file The path
   * @param  boolean $nice True: return the size in a human readable format
   * @return mixed
   */    
  static public function size($file, $nice = false) {
    clearstatcache();
    $size = filesize($file);
    return ($nice) ? self::niceSize($size) : $size;
  }

  /**
   * Converts an integer size into a human readable format
   * 
   * @param  int $size The file size or a file path
   * @return string
   */    
  static public function niceSize($size) {
    
    // file mode
    if(!is_int($size) && file_exists($size)) {
      $size = self::size($size);
    }

    // make sure it's an int
    $size = (int)$size;
    
    // avoid errors for invalid sizes
    if($size <= 0) return '0 kb';
    
    // available units
    $unit = array('b','kb','mb','gb','tb','pb', 'eb', 'zb', 'yb');
    
    // the math magic
    return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . a::get($unit, $i, '?');
  
  }

  /**
   * Get the file's last modification time.
   *
   * @param string $file
   * @return int
   */
  static public function modified($file) {
    return filemtime($file);
  }

  /**
   * Returns the mime type of a file
   * 
   * @param string $file
   * @return string
   */
  static public function mime($file) {

    // Fileinfo is prefered if available
    if(function_exists('finfo_file')) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime  = finfo_file($finfo, $file);
      finfo_close($finfo);
      return $mime;
    } 

    // for older versions with mime_content_type go for that. 
    if(function_exists('mime_content_type') && $mime = @mime_content_type($file) !== false) {
      return $mime;
    } 

    // guess the matching mime type by extension
    $info = a::get(c::get('mimes'), f::extension($file), null);

    // if there are more than one applicable mimes for the extension, return the first
    if(is_array($info)) return a::first($info);

    // return what's left
    return $info;

  }
    
  /**
   * Sanitize a filename to strip unwanted special characters
   * 
   * @param  string $string The file name
   * @return string
   */    
  static public function safeName($string) {
    $name      = f::name($string);
    $extension = f::extension($string);
    $end       = (!empty($extension)) ? '.' . str::slug($extension) : '';
    return str::slug($name) . $end;
  }

  /**
   * Checks if the file is writable
   * 
   * @param string $file
   * @return boolean
   */
  static public function writable($file) {
    return is_writable($file);
  }

  /**
   * Checks if the file is readable
   * 
   * @param string $file
   * @return boolean
   */
  static public function readable($file) {
    return is_readable($file);
  }

}
