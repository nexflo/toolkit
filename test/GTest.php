<?php

require_once('lib/bootstrap.php');

class GTest extends PHPUnit_Framework_TestCase {
  
  public function __construct() {

    $GLOBALS['testvar'] = 'testvalue';

  }

  public function testGet() {
    
    $this->assertEquals('testvalue', g::get('testvar'));
    $this->assertEquals('defaultvalue', g::get('nonexistentvar', 'defaultvalue'));

  }  

  public function testSet() {

    g::set('anothervar', 'supervalue');
    g::set('testvar', 'overwrittenvalue');

    $this->assertEquals('supervalue', g::get('anothervar'));
    $this->assertEquals('overwrittenvalue', g::get('testvar'));

    g::set(array(
      'var1' => 'value1',
      'var2' => 'value2'
    ));

    $this->assertEquals('value1', g::get('var1'));
    $this->assertEquals('value2', g::get('var2'));

  }

}