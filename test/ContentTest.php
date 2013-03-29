<?php

require_once('lib/bootstrap.php');

class ContentTest extends PHPUnit_Framework_TestCase {
  
  public function testStartAndEnd() {
    
    content::start();
    
    echo 'yay';
    
    $content = content::stop();

    $this->assertEquals('yay', $content);

  }  

  public function testLoad() {

    $content = content::load(TEST_ROOT_ETC . DS . 'content.php', array(
      'var' => 'awesome'
    ));

    $this->assertEquals('My test content is awesome', $content);

  }

  public function testType() {

    $expected = 'Content-type: image/jpeg; charset=utf-8';

    $this->assertEquals($expected, content::type('jpg', 'utf-8', false));

    $expected = 'Content-type: text/plain; charset=utf-8';

    $this->assertEquals($expected, content::type('txt', 'utf-8', false));

  }

}