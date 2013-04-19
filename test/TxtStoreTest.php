<?php

require_once('lib/bootstrap.php');

class TxtStoreTest extends PHPUnit_Framework_TestCase {

  public function __construct() {

    $this->file = TEST_ROOT_ETC . DS . 'tmp' . DS . 'txtstore.txt';
    $this->data = array(
      'title' => 'Super Title',
      'text'  => 'Lorem ipsum dolor sit amet'
    );

  }

  public function testWrite() {
    $this->assertTrue(txtstore::write($this->file, $this->data));
  }

  public function testRead() {
    
    $this->assertEquals($this->data, txtstore::read($this->file));

    // clean up
    f::remove($this->file);

  }

}
