<?php

require_once('lib/bootstrap.php');

class CacheTest extends PHPUnit_Framework_TestCase {

  public function testFileDriver() {

    $cache = Cache::connect('file', array(
      'root' => TEST_ROOT_TMP
    ));
  
    $this->assertInstanceOf('CacheDriver', $cache);
    $this->assertInstanceOf('FileCacheDriver', $cache);

    $this->assertTrue(Cache::set('mykey', 'myvalue'));
    $this->assertEquals('myvalue', Cache::get('mykey'));

    $this->assertTrue(Cache::set('mykey', 'myoverwrittenvalue'));
    $this->assertEquals('myoverwrittenvalue', Cache::get('mykey'));

    $this->assertEquals('mydefaultvalue', Cache::get('mymissingkey', 'mydefaultvalue'));

    $this->assertTrue(Cache::remove('mykey'));
    $this->assertFalse(Cache::exists('mykey'));

    // set and check for a couple values and flush the cache afterwards
    Cache::set('key1', 'val1');
    Cache::set('key2', 'val2');
    Cache::set('key3', 'val3');

    $this->assertEquals('val1', Cache::get('key1'));
    $this->assertEquals('val2', Cache::get('key2'));
    $this->assertEquals('val3', Cache::get('key3'));

    $this->assertTrue(Cache::flush());

    $this->assertFalse(Cache::exists('key1'));
    $this->assertFalse(Cache::exists('key2'));
    $this->assertFalse(Cache::exists('key3'));

    Cache::set('key', 'val', 60);
    
    $this->assertEquals(date('d.m.Y H:i', time()+3600), date('d.m.Y H:i', Cache::expires('key')));
    $this->assertFalse(Cache::expired('key'));

    Cache::flush();

  }
 
  public function testApcDriver() {

    // skip testing until apc.enable_cli problem is fixed :(
    return true;

    $cache = Cache::connect('apc');
  
    $this->assertInstanceOf('CacheDriver', $cache);
    $this->assertInstanceOf('ApcCacheDriver', $cache);

    $this->assertTrue(Cache::set('mykey', 'myvalue'));
    $this->assertEquals('myvalue', Cache::get('mykey'));

    $this->assertTrue(Cache::set('mykey', 'myoverwrittenvalue'));
    $this->assertEquals('myoverwrittenvalue', Cache::get('mykey'));

    $this->assertEquals('mydefaultvalue', Cache::get('mymissingkey', 'mydefaultvalue'));

    $this->assertTrue(Cache::remove('mykey'));
    $this->assertFalse(Cache::exists('mykey'));

    // set and check for a couple values and flush the cache afterwards
    Cache::set('key1', 'val1');
    Cache::set('key2', 'val2');
    Cache::set('key3', 'val3');

    $this->assertEquals('val1', Cache::get('key1'));
    $this->assertEquals('val2', Cache::get('key2'));
    $this->assertEquals('val3', Cache::get('key3'));

    $this->assertTrue(Cache::flush());

    $this->assertFalse(Cache::exists('key1'));
    $this->assertFalse(Cache::exists('key2'));
    $this->assertFalse(Cache::exists('key3'));

    Cache::set('key', 'val', 60);
    
    $this->assertEquals(null, Cache::expires('key'));
    $this->assertFalse(Cache::expired('key'));

    Cache::flush();

  }

  public function testMemcacheDriver() {

    $cache = Cache::connect('memcache');
  
    $this->assertInstanceOf('CacheDriver', $cache);
    $this->assertInstanceOf('MemcacheCacheDriver', $cache);

    $this->assertTrue(Cache::set('mykey', 'myvalue'));
    $this->assertEquals('myvalue', Cache::get('mykey'));

    $this->assertTrue(Cache::set('mykey', 'myoverwrittenvalue'));
    $this->assertEquals('myoverwrittenvalue', Cache::get('mykey'));

    $this->assertEquals('mydefaultvalue', Cache::get('mymissingkey', 'mydefaultvalue'));

    $this->assertTrue(Cache::remove('mykey'));
    $this->assertFalse(Cache::exists('mykey'));

    // set and check for a couple values and flush the cache afterwards
    Cache::set('key1', 'val1');
    Cache::set('key2', 'val2');
    Cache::set('key3', 'val3');

    $this->assertEquals('val1', Cache::get('key1'));
    $this->assertEquals('val2', Cache::get('key2'));
    $this->assertEquals('val3', Cache::get('key3'));

    $this->assertTrue(Cache::flush());

    $this->assertFalse(Cache::exists('key1'));
    $this->assertFalse(Cache::exists('key2'));
    $this->assertFalse(Cache::exists('key3'));

    Cache::set('key', 'val', 60);
    
    $this->assertEquals(null, Cache::expires('key'));
    $this->assertFalse(Cache::expired('key'));

    Cache::flush();

  }

}
