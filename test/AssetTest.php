<?php

require_once('lib/bootstrap.php');

class AssetTest extends PHPUnit_Framework_TestCase {

  public function __construct() {
    $this->asset = new Asset(TEST_ROOT_ETC . DS . 'upload' . DS . 'Screen Shot 2013-04-15 at 13.04.33.png');
  }

  public function test() {
    
  }
 
}