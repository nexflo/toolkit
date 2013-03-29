<?php

require_once('lib/bootstrap.php');

class UrlTest extends PHPUnit_Framework_TestCase {

  public function __construct() {
    $this->full = 'http://user:password@getkirby.com:80/super/path/?my=var#hash';
  }

  public function testCurrent() {
    $this->assertEquals(null, url::current());
  }
  
  public function testParse() {

    $parsed = url::parse($this->full);

    $this->assertEquals('http', $parsed['scheme']);
    $this->assertEquals('user', $parsed['user']);
    $this->assertEquals('password', $parsed['pass']);
    $this->assertEquals('getkirby.com', $parsed['host']);
    $this->assertEquals('80', $parsed['port']);
    $this->assertEquals('/super/path/', $parsed['path']);
    $this->assertEquals('my=var', $parsed['query']);
    $this->assertEquals('hash', $parsed['fragment']);

  }

  public function testScheme() {
    $this->assertEquals('http', url::scheme($this->full));
  }

  public function testHost() {
    $this->assertEquals('getkirby.com', url::host($this->full));
  }

  public function testPort() {
    $this->assertEquals('80', url::port($this->full));
  }

  public function testPath() {
    $this->assertEquals('/super/path/', url::path($this->full));
  }

  public function testParams() {

    $sample = 'http://getkirby.com/my/awesome/path/param1:value1/param2:value2';
    $params = url::params($sample);

    $this->assertEquals('value1', $params['param1']);
    $this->assertEquals('value2', $params['param2']);
    $this->assertEquals(2, count($params));

  }

  public function testBase() {
    $this->assertEquals('http://getkirby.com', url::base($this->full));
  }

  public function testQuery() {
    $this->assertEquals('my=var', url::query($this->full));
  }

  public function testBuildPath() {     
    $this->assertEquals('my/super/awesome/path', url::buildPath(array('my', 'super', 'awesome', 'path')));
  }

  public function testBuildParams() {     
    $this->assertEquals('param1:value1/param2:value2', url::buildParams(array('param1' => 'value1', 'param2' => 'value2')));
  }

  public function testBuildQuery() {     
    $this->assertEquals('?my=var&myother=var', url::buildQuery(array('my' => 'var', 'myother' => 'var')));
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