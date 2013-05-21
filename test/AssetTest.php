<?php

require_once('lib/bootstrap.php');

class AssetTest extends PHPUnit_Framework_TestCase {

  public function __construct() {

    $this->file  = TEST_ROOT_ETC . DS . 'images' . DS . 'Screen Shot 2013-04-15 at 13.04.33.png';
    $this->url   = 'http://superdomain.com/Screen Shot 2013-04-15 at 13.04.33.png';
    $this->asset = new Asset($this->file, $this->url);
  }

  public function testURL() {
    $this->assertEquals($this->url, $this->asset->url());
  }

  public function testRoot() {
    $this->assertEquals($this->file, $this->asset->root());
  }
 
  public function testHash() {
    //$this->assertEquals('40f7a2a1f0983b197308c39d49a63836', $this->asset->hash());
  }

  public function testFilename() {
    $this->assertEquals('Screen Shot 2013-04-15 at 13.04.33.png', $this->asset->filename());    
  }

  public function testDir() {
    $this->assertEquals(dirname($this->file), $this->asset->dir());    
  }

  public function testDirname() {
    $this->assertEquals('images', $this->asset->dirname());    
  }

  public function testName() {
    $this->assertEquals('Screen Shot 2013-04-15 at 13.04.33', $this->asset->name());    
  }

  public function testExtension() {
    $this->assertEquals('png', $this->asset->extension());    
  }

  public function testType() {
    $this->assertEquals('image', $this->asset->type());    
  }

  public function testIs() {
    $this->assertTrue($this->asset->is('png'));    
    $this->assertTrue($this->asset->is('image/png'));    
    $this->assertFalse($this->asset->is('jpg'));    
  }

  public function testModified() {
    $this->assertEquals(filemtime($this->file), $this->asset->modified());        
  }

  public function testExists() {
    $this->assertTrue($this->asset->exists());        
  }

  public function testIsReadable() {
    $this->assertEquals(is_readable($this->file), $this->asset->isReadable());        
  }

  public function testIsWritable() {
    $this->assertEquals(is_writable($this->file), $this->asset->isWritable());        
  }

  public function testSize() {
    $this->assertEquals(274023, $this->asset->size());        
  }

  public function testNiceSize() {
    $this->assertEquals('267.6 kb', $this->asset->niceSize());        
  }

  public function testMime() {
    $this->assertEquals('image/png', $this->asset->mime());        
  }

  public function testExif() {
    $this->assertInstanceOf('Exif', $this->asset->exif());        
  }

  public function testDetails() {
    $this->assertEquals(getimagesize($this->file), $this->asset->details());        
  }

  public function testDimensions() {
    $this->assertInstanceOf('Dimensions', $this->asset->dimensions());        
  }

  public function testWidth() {
    $this->assertEquals(1230, $this->asset->width());        
  }

  public function testHeight() {
    $this->assertEquals(962, $this->asset->height());        
  }

  public function testRatio() {
    $this->assertEquals(1.2785862785863, $this->asset->ratio());        
  }

  public function testHeader() {
    $this->assertEquals('Content-type: image/png', $this->asset->header($send = false));        
  }

  public function testToString() {
    $this->assertEquals('<a href="http://superdomain.com/Screen Shot 2013-04-15 at 13.04.33.png">http://superdomain.com/Screen Shot 2013-04-15 at 13.04.33.png</a>', (string)$this->asset);
  }

}