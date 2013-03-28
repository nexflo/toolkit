<?php

require_once('lib/bootstrap.php');

class UrlTest extends PHPUnit_Framework_TestCase {

  public function testCurrent() {
    // not testable on cli
  }
  
  public function testShort() {
    
    $sample = 'http://getkirby.com';
    $long   = 'http://getkirby.com/docs/example/1/2/3';
      
    // without shortening
    $this->assertEquals('getkirby.com', url::short($sample, false));

    // zero chars
    $this->assertEquals('getkirby.com', url::short($sample, 0));
    
    // with shortening
    $this->assertEquals('getki…', url::short($sample, 5));

    // with different ellipsis character
    $this->assertEquals('getki---', url::short($sample, 5, false, '---'));

    // only keeping the base
    $this->assertEquals('getkirby.com', url::short($long, false, true));

  }

  public function testHasQuery() {

    $sample = 'http://getkirby.com/?var=value';

    $this->assertTrue(url::hasQuery($sample));

    $sample = 'http://getkirby.com';

    $this->assertFalse(url::hasQuery($sample));

  }

  public function testStripQuery() {

    $sample = 'http://getkirby.com/?var=value';

    $this->assertEquals('http://getkirby.com/', url::stripQuery($sample));

  }

  public function testStripHash() {

    $sample = 'http://getkirby.com/#myhash';

    $this->assertEquals('http://getkirby.com/', url::stripHash($sample));

  }

  public function testValid() {

     $sample = 'http://getkirby.com/?var=value';

    $this->assertTrue(url::valid($sample));

    $sample = 'alksdhakshkajhkda';

    $this->assertFalse(url::valid($sample));

  }


}