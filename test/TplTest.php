<?php

require_once('lib/bootstrap.php');

class TplTest extends PHPUnit_Framework_TestCase {
 
  public function testSetAndGet() {
    
    tpl::set('mykey', 'myvalue');

    $this->assertEquals('myvalue', tpl::get('mykey'));

    tpl::set('mykey', 'myoverwrittenvalue');

    $this->assertEquals('myoverwrittenvalue', tpl::get('mykey'));

    tpl::set(array(
      'mykey1' => 'myvalue1',
      'mykey2' => 'myvalue2'
    ));

    $this->assertEquals('myvalue1', tpl::get('mykey1'));
    $this->assertEquals('myvalue2', tpl::get('mykey2'));

    tpl::remove('mykey1');

    $this->assertEquals(null, tpl::get('mykey1'));

    $this->assertEquals('defaultvalue', tpl::get('mykey1', 'defaultvalue'));

  } 

  public function testLoad() {

    $result = tpl::loadFile(TEST_ROOT_ETC . DS . 'content.php', array('var' => 'fantastic'), $return = true);

    $this->assertEquals('My test content is fantastic', $result);

    c::set('tpl.root', TEST_ROOT_ETC);

    $result = tpl::load('content', array('var' => 'fantastic'), $return = true);

    $this->assertEquals('My test content is fantastic', $result);

  }

}