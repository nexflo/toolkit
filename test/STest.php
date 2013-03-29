<?php

require_once('lib/bootstrap.php');

class STest extends PHPUnit_Framework_TestCase {
  
  public function testStart() {
    
    s::start();

    // start again to check if 
    // only one session will be started correctly. 
    s::start();

    $this->assertTrue(isset($_SESSION));

  }  

  public function testId() {

    $this->assertTrue(is_string(s::id()));

  }

  public function testSet() {

    s::set('testvar', 'testvalue');

    $this->assertEquals('testvalue', $_SESSION['testvar']);

    s::set('testvar', 'overwrittenvalue');

    $this->assertEquals('overwrittenvalue', $_SESSION['testvar']);

    s::set(array(
      'var1' => 'value1',
      'var2' => 'value2'
    ));

    $this->assertEquals('value1', $_SESSION['var1']);
    $this->assertEquals('value2', $_SESSION['var2']);

  }

  public function testGet() {

    s::set('testvar', 'testvalue');

    $this->assertEquals('testvalue', s::get('testvar'));
    $this->assertEquals('defaultvalue', s::get('nonexistent', 'defaultvalue'));

    $this->assertEquals($_SESSION, s::get());

  }

  public function testRemove() {

    s::remove('testvar');

    $this->assertFalse(isset($_SESSION['testvar']));

  }

  public function testDestroy() {

    s::destroy();

    $this->assertFalse(isset($_SESSION));

  }

  public function testRestart() {

    s::start();
    s::set('testvar', 'testvalue');

    s::restart();

    $this->assertTrue(isset($_SESSION));
    $this->assertFalse(isset($_SESSION['testvar']));

  }

}