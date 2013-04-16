<?php

require_once('lib/bootstrap.php');

class UploadTest extends PHPUnit_Framework_TestCase {
 
  protected function upload() {

    $name = 'Screen Shot 2013-04-15 at 13.04.33.png';
    $file = TEST_ROOT_ETC . DS . 'upload' . DS . $name;

    $_FILES['file'] = array(
       'name'     => $name,
       'tmp_name' => $file,
       'type'     => f::mime($file),
       'size'     => f::size($file),
       'error'    => 0
    );

  }

  public function testUpload() {

    $this->upload();

    $upload = new Upload('file', TEST_ROOT_ETC . DS . 'upload' . DS . ':safeName.:extension');

    $this->assertFalse($upload->failed());
    $this->assertEquals(null, $upload->error());
    $this->assertEquals(null, $upload->message());
    $this->assertEquals(f::size($_FILES['file']['tmp_name']), $upload->size());
    $this->assertEquals('Screen Shot 2013-04-15 at 13.04.33', $upload->name());
    $this->assertEquals('screen-shot-2013-04-15-at-13-04.33', $upload->safeName());
    $this->assertEquals('png', $upload->extension());
    $this->assertTrue(f::exists(TEST_ROOT_ETC . DS . 'upload' . DS . 'screen-shot-2013-04-15-at-13-04.33.png'));

    f::remove($upload->file());

  }

}