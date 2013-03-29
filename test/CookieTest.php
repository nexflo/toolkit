<?php

ob_start();

require_once('lib/bootstrap.php');

class CookieTest extends PHPUnit_Framework_TestCase {

  public function testMethods() {
    
    cookie::set('testcookie', 'testvalue', 1000);

    $this->assertTrue(isset($_COOKIE['testcookie']));
    $this->assertTrue(cookie::exists('testcookie'));
    $this->assertEquals('testvalue', cookie::get('testcookie'));

    cookie::remove('testcookie');

    $this->assertFalse(isset($_COOKIE['testcookie']));
    
  }  

}