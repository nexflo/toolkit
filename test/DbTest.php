<?php

require_once('lib/bootstrap.php');

class DbTest extends PHPUnit_Framework_TestCase {

  public static $database = null;

  public static function setUpBeforeClass() {
    
    self::$database = TEST_ROOT_TMP . DS . 'database.sqlite';

    db::connect(array(
      'database' => self::$database,
      'type'     => 'sqlite'
    ));

    // create a dummy user table, which we can use for our tests
    db::query('

      CREATE TABLE "users" (
      "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
      "username" TEXT UNIQUE ON CONFLICT FAIL NOT NULL,
      "fname" TEXT,
      "lname" TEXT,
      "password" TEXT NOT NULL,
      "email" TEXT NOT NULL
      );

    ');

    // insert some silly dummy data
    db::insert('users', array(
      'username' => 'john',
      'fname'    => 'John',
      'lname'    => 'Lennon', 
      'email'    => 'john@test.com',
      'password' => 'beatles'
    ));

    db::insert('users', array(
      'username' => 'paul',
      'fname'    => 'Paul',
      'lname'    => 'McCartney', 
      'email'    => 'paul@test.com',
      'password' => 'beatles'
    ));

    db::insert('users', array(
      'username' => 'george',
      'fname'    => 'George',
      'lname'    => 'Harrison', 
      'email'    => 'george@test.com',
      'password' => 'beatles'
    ));

  }

  public static function tearDownAfterClass() {  
    // kill the database
    f::remove(self::$database);    
  }

  public function testConnect() {
    $this->assertInstanceOf('PDO', db::connect());
  } 

  public function testConnection() {
    $this->assertInstanceOf('PDO', db::connection());
  } 

  public function testType() {

    $this->assertEquals('mysql', db::type());
    
    db::connect(array(
      'database' => self::$database, 
      'type'     => 'sqlite'
    ));

    $this->assertEquals('sqlite', db::type());

  }

  public function testPrefix() {

    db::connect(array(
      'database' => self::$database,
      'type'     => 'sqlite', 
      'prefix'   => 'myprefix_'
    ));

    $this->assertEquals('myprefix_', db::prefix());

    db::connect(array(
      'database' => self::$database,
      'type'     => 'sqlite'
    ));

  }

  public function testEscape() {

  }

  public function testLastId() {

    $id = db::insert('users', array(
      'username' => 'ringo', 
      'fname'    => 'Ringo',
      'lname'    => 'Starr', 
      'email'    => 'ringo@test.com',
      'password' => 'beatles'
    ));

    $this->assertEquals(4, $id);
    $this->assertEquals($id, db::lastId());
      
  }

  public function testLastResult() {
    $result = db::select('users', '*');
    $this->assertEquals($result, db::lastResult());
  }

  public function testLastError() {
    $result = db::select('nonexisting', '*');
    $this->assertInstanceOf('PDOException', db::lastError());
  }

  public function testQuery() {

    $result = db::query('select * from users where username = :username', array('username' => 'paul'), array('fetch' => 'array', 'iterator' => 'array'));

    $this->assertEquals('paul', $result[0]['username']);

  }

  public function testExecute() {
    
  }

  public function testTable() {
    $this->assertInstanceOf('DbQuery', db::table('users'));
  }

  public function testSelect() {
  
    $result = db::select('users');

    $this->assertEquals(4, $result->count());

    $result = db::select('users', '*', array('username' => 'paul'));

    $this->assertEquals(1, $result->count());

  }

  public function testFirst() {
    $result = db::first('users');
    $this->assertEquals('john', $result->username());
  }

  public function testColumn() {
    $result = db::column('users', 'username');
    $this->assertEquals(array('john', 'paul', 'george', 'ringo'), $result->toArray());
  }

  public function testInsert() {
    // already tested a couple times in the methods before
  }

  public function testUpdate() {
    db::update('users', array('email' => 'ringo@gmail.com'), array('username' => 'ringo'));
    $this->assertEquals('ringo@gmail.com', db::row('users', '*', array('username' => 'ringo'))->email());
  }

  public function testDelete() {
    db::delete('users', array('username' => 'ringo'));
    $this->assertFalse(db::one('users', '*', array('username' => 'ringo')));
  }

  public function testCount() {
    $this->assertEquals(3, db::count('users'));
  }

  public function testMin() {
    $this->assertEquals(1, db::min('users', 'id'));
  }

  public function testMax() {
    $this->assertEquals(3, db::max('users', 'id'));
  }

  public function testAvg() {
    $this->assertEquals(2.0, db::avg('users', 'id'));
  }

  public function testSum() {
    $this->assertEquals(6, db::sum('users', 'id'));
  }

  public function testAffected() {
    db::delete('users');
    $this->assertEquals(3, db::affected());
  }


}