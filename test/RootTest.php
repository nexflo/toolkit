<?php

require_once('lib/bootstrap.php');

class RootTest extends PHPUnit_Framework_TestCase {
  
  public function testRoot() {

    root('cms', 'test');
    root('cms.lib', '{cms}/lib');

    $this->assertEquals('test/lib', root('cms.lib'));

    root('cms', 'super');

    $this->assertEquals('super/lib', root('cms.lib'));

  }  


}