<?php

require_once('lib/bootstrap.php');

class HelpersTest extends PHPUnit_Framework_TestCase {
  
  public function testGo() {

    $go = go('http://someurl.com', 301, false);

    $this->assertEquals('HTTP/1.1 301 Moved Permanently', $go);

    $go = go('http://someurl.com', 302, false);

    $this->assertEquals('HTTP/1.1 302 Found', $go);

    $go = go('http://someurl.com', 303, false);

    $this->assertEquals('HTTP/1.1 303 See Other', $go);

    $go = go('http://someurl.com', 304, false);

    $this->assertFalse($go);

  }  

  public function testGet() {
    // already tested in RTest
  }

  public function testParam() {

    // set the current url
    uri::current('http://getkirby.com/param1:value1');
    
    $this->assertEquals('value1', param('param1'));

    // reset the current url
    uri::current(false);

  }

  public function testR() {

    $condition = true;

    $this->assertEquals('yay', r($condition, 'yay', 'nooooo'));

    $condition = false;

    $this->assertEquals('nooooo', r($condition, 'yay', 'nooooo'));
    $this->assertEquals(null, r($condition, 'yay'));

  }

  public function testE() {
    // see testR    
  }

  public function testEcco() {
    // see testR    
  }

  public function testL() {
    // see l::get()
  }

  public function testDump() {
    // see a::show()
  }

  public function testAttr() {
    // see html::attr()
  }

  public function testHtml() {
    // see html::encode()
  }

  public function testXml() {
    // see xml::encode()
  }

  public function testMultiline() {
    // see html::breaks()
  }

  public function testWidont() {
    // see str::widont()
  }

  public function testMemory() {
    // not really a very helpful test
    $this->assertTrue(is_string(memory()));
  }

}