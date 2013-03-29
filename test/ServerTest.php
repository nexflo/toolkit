<?php

require_once('lib/bootstrap.php');

class ServerTest extends PHPUnit_Framework_TestCase {
  
  public function testGet() {

    $this->assertTrue(is_array(server::get()));
    $this->assertEquals($_SERVER, server::get());

  }

}