<?php

require_once('lib/bootstrap.php');

class UserModel extends Model {

  protected $timestamps  = true;
  protected $allowedKeys = array('name', 'email', 'admin', 'group');
  
  protected function insert() {
    $this->primaryKey('test-' . uniqid());
    g::set($this->id, $this->toArray());
    return true;
  }

  protected function update() {
    g::set($this->id, $this->toArray());
    return true;
  }

  public function delete() {
    g::remove($this->id);
    return true;
  }

  protected function validate() {
    $this->v(array(
      'email' => 'email'  
    ));    
  }

}

class ModelTest extends PHPUnit_Framework_TestCase {

  public function __construct() {
    $this->model = new UserModel();
  }

  public function testSettersAndGetters() {

    $this->model->name  = 'Bastian Allgeier';
    $this->model->email = 'bastian.allgeier@gmail.com';
    $this->model->admin = true;

    $this->model->set(array(
      'group' => 'awesome group', 
    ));

    $this->assertEquals('Bastian Allgeier', $this->model->name);
    $this->assertEquals('bastian.allgeier@gmail.com', $this->model->email);
    $this->assertEquals('awesome group', $this->model->group);
    $this->assertTrue($this->model->admin);

  }

  public function testSave() {
  
    $this->model->name  = 'Bastian Allgeier';
    $this->model->email = 'bastian.allgeier@gmail.com';
    $this->model->save();

    // get the model directly from the global store
    $store = g::get($this->model->id);

    $this->assertEquals('Bastian Allgeier', $store['name']);
    $this->assertEquals('bastian.allgeier@gmail.com', $store['email']);

    // test update
    $this->model->group = 'admin';
    $this->model->save();

    $store = g::get($this->model->id);
    $this->assertEquals('admin', $store['group']);

    // test delete
    $this->model->delete();
    $store = g::get($this->model->id);

    $this->assertEquals(null, $store);

  }

  public function testValidate() {

    $this->model->name  = 'Bastian Allgeier';
    $this->model->save();

    $this->assertTrue($this->model->invalid());
    $this->assertFalse($this->model->valid());
    $this->assertTrue(is_array($this->model->errors()));

    $this->assertEquals('The email must be a valid email', $this->model->error('email'));

  }

  public function testSetterException() {

    try {
      $this->model->unallowedKey = 'unallowed value';
    } catch(Exception $e) {
      return;
    } 

    $this->fail('An expected exception has not been raised.');

  }

}