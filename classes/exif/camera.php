<?php 

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * Small class which hold info about the camera
 * 
 * @package Kirby
 */
class ExifCamera {

  protected $make;
  protected $model;

  /**
   * Constructor
   * 
   * @param string $make
   * @param string $model
   */
  public function __construct($exif) {
    $this->make  = @$exif['Make'];
    $this->model = @$exif['Model'];
  }

  /**
   * Returns the make of the camera
   * 
   * @return string
   */
  public function make() {
    return $this->make;
  }

  /**
   * Returns the camera model
   * 
   * @return string
   */
  public function model() {
    return $this->model;
  }

  /**
   * Returns the full make + model name
   * 
   * @return string
   */
  public function __toString() {
    return trim($this->make . ' ' . $this->model);
  }

}