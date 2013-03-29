<?php

require_once('lib/bootstrap.php');

class FTest extends PHPUnit_Framework_TestCase {
  
  public function __construct() {
    $this->contentFile = TEST_ROOT_ETC . DS . 'content.php';
    $this->tmpFile = TEST_ROOT_TMP . DS . 'testfile.txt';
  }

  public function testExists() {
    $this->assertTrue(f::exists($this->contentFile));
  }

  public function testWrite() {
    $this->assertTrue(f::write($this->tmpFile, 'my content'));    
  }

  public function testAppend() {
    $this->assertTrue(f::append($this->tmpFile, ' is awesome'));        
  }

  public function testRead() {
    $this->assertEquals('my content is awesome', f::read($this->tmpFile));    
  }

  public function testMove() {
    $this->assertTrue(f::move($this->tmpFile, TEST_ROOT_TMP . DS . 'moved.txt'));
  }

  public function testCopy() {
    $this->assertTrue(f::copy(TEST_ROOT_TMP . DS . 'moved.txt', TEST_ROOT_TMP . DS . 'copied.txt'));
  }

  public function testRemove() {
    $this->assertTrue(f::remove(TEST_ROOT_TMP . DS . 'moved.txt'));
    $this->assertTrue(f::remove(TEST_ROOT_TMP . DS . 'copied.txt'));
  }

  public function testExtension() {
    $this->assertEquals('php', f::extension($this->contentFile));
    $this->assertEquals('content.txt', f::extension($this->contentFile, 'txt'));
  }

  public function testFilename() {
    $this->assertEquals('content.php', f::filename($this->contentFile));
  }

  public function testName() {
    $this->assertEquals('content', f::name($this->contentFile));
  }

  public function testDirname() {
    $this->assertEquals(dirname($this->contentFile), f::dirname($this->contentFile));
  }

  public function testSize() {
    $this->assertEquals(37, f::size($this->contentFile));
    $this->assertEquals('37 b', f::size($this->contentFile, true));
  }

  public function testNiceSize() {
    $this->assertEquals('37 b', f::niceSize($this->contentFile));
    $this->assertEquals('37 b', f::niceSize(37));
  }

  public function testModified() {
    $this->assertEquals(filemtime($this->contentFile), f::modified($this->contentFile));
  }

  public function testMime() {
    $this->assertEquals('text/plain', f::mime($this->contentFile));
  }

  public function testSafeName() {
    $name     = 'Süper -invølid_fileßnamé!!!.jpg';
    $expected = 'sueper-involid-filessname.jpg';

    $this->assertEquals($expected, f::safeName($name));
  } 

  public function testWritable() {
    $this->assertEquals(is_writable($this->contentFile), f::writable($this->contentFile));
  }

  public function testReadable() {
    $this->assertEquals(is_readable($this->contentFile), f::readable($this->contentFile));
  }

}