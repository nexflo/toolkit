<?php

require_once('lib/bootstrap.php');

class ATest extends PHPUnit_Framework_TestCase {
  
  public function __construct() {

    $this->user = array(
      'username' => 'testuser',
      'password' => 'testpassword',
      'email'    => 'test@user.com', 
      'profiles' => array(
        'twitter'  => 'http://twitter.com/testuser',
        'facebook' => 'http://facebook.com/testuser'
      )
    );

    $this->users = array();

    $this->users['userA'] = $this->user;
    $this->users['userA']['username'] = 'peter';

    $this->users['userB'] = $this->user;
    $this->users['userB']['username'] = 'paul';

    $this->users['userC'] = $this->user;
    $this->users['userC']['username'] = 'mary';


  }

  public function testSet() {

    $array = array();

    a::set($array, 'index-1', 'test-1');
    a::set($array, 'index-2', 'test-2');
    a::set($array, 'index-3', 'test-3');

    $this->assertEquals('test-1', $array['index-1']);
    $this->assertEquals('test-2', $array['index-2']);
    $this->assertEquals('test-3', $array['index-3']);

    a::set($array, 'index-1 > subindex-1', 'subtest-1');
    a::set($array, 'index-2 > subindex-2', 'subtest-2');
    a::set($array, 'index-3 > subindex-3', 'subtest-3');

    $this->assertEquals('subtest-1', $array['index-1']['subindex-1']);
    $this->assertEquals('subtest-2', $array['index-2']['subindex-2']);
    $this->assertEquals('subtest-3', $array['index-3']['subindex-3']);

  }

  public function testGet() {

    $this->assertEquals('testuser', a::get($this->user, 'username'));
    $this->assertEquals('http://twitter.com/testuser', a::get($this->user, 'profiles > twitter'));
    $this->assertEquals('http://linkedin.com/testuser', a::get($this->user, 'profiles > linkedin', 'http://linkedin.com/testuser'));

  }

  public function testRemove() {

    $user = $this->user;
    $user = a::remove($user, 'email');

    $this->assertEquals(null, a::get($user, 'email'));

    $user = $this->user;
    $user = a::remove($user, 'test@user.com', false);

    $this->assertEquals(null, a::get($user, 'email'));

  }

  public function testInject() {

    $user = $this->user;
    $user = a::inject($user, 2, array('website' => 'http://mywebsite.com'));

    $this->assertEquals('http://mywebsite.com', $user['website']);

    $user = array_values($user);

    $this->assertEquals('http://mywebsite.com', $user[2]);

  }

  public function testShow() {
    // not really testable
  }

  public function testJson() {
    $this->assertEquals(json_encode($this->user), a::json($this->user));
  }

  public function testXml() {
    // to be tested in the x class
  }

  public function testExtract() {

    $users = $this->users;
    
    $usernames = a::extract($users, 'username');
    $this->assertEquals(array('peter', 'paul', 'mary'), $usernames);

  }

  public function testShuffle() {
    
    $users = $this->users;
    $users = a::shuffle($users);

    // the only testable thing is that keys still exist
    $this->assertTrue(isset($users['userA']));

  }

  public function testFirst() {

    $users = $this->users;
    $user = a::first($users);

    $this->assertEquals('peter', $user['username']);

  }

  public function testLast() {

    $users = $this->users;
    $user = a::last($users);

    $this->assertEquals('mary', $user['username']);

  }

  public function testFill() {

    $users = $this->users;
    $users = a::fill($users, 100);

    $this->assertEquals(100, count($users));

  }

  public function testMissing() {

    $user = $this->user;
    $required = array('username', 'password', 'website');

    $missing = a::missing($user, $required);

    $this->assertEquals(array('website'), $missing);

  }

  public function testSort() {

    $users = $this->users;
    $users = a::sort($users, 'username', 'asc');
    $first = a::first($users);
    $last  = a::last($users);

    $this->assertEquals('mary', $first['username']);
    $this->assertEquals('peter', $last['username']);

  }

}