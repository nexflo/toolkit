<?php

require_once('lib/bootstrap.php');

class SqlTest extends PHPUnit_Framework_TestCase {

  public function testSelect() {

    $sql = sql::select(array(
      'table'   => 'posts', 
      'join'    => array(
        array(
          'table' => 'users',
          'on'    => 'posts.user = users.id'        
        )
      ),
      'columns' => 'posts.id, posts.title, posts.text', 
      'where'   => 'posts.id > 5',
      'order'   => 'posts.title desc',
      'offset'  => 5,
      'limit'   => 10
    ));

    $expected = "SELECT posts.id, posts.title, posts.text FROM posts INNER JOIN users ON posts.user = users.id WHERE posts.id > 5 ORDER BY posts.title desc LIMIT 5, 10";

    $this->assertEquals($expected, $sql);

  } 

  public function testInsert() {

    $sql = sql::insert(array(
      'table'  => 'posts', 
      'values' => array(
        'title' => 'Super Post', 
        'text'  => 'Super Post Text',
        'user'  => 5
      )
    ));

    $expected = "INSERT INTO posts (title, text, user) VALUES ('Super Post', 'Super Post Text', '5')";

    $this->assertEquals($expected, $sql);

  } 

  public function testUpdate() {

    $sql = sql::update(array(
      'table'  => 'posts', 
      'values' => array(
        'title' => 'Super Post', 
        'text'  => 'Super Post Text',
        'user'  => 5
      ), 
      'where' => "id = '5'"
    ));

    $expected = "UPDATE posts SET title = 'Super Post', text = 'Super Post Text', user = '5' WHERE id = '5'";

    $this->assertEquals($expected, $sql);

  } 

  public function testDelete() {

    $sql = sql::delete(array(
      'table' => 'posts', 
      'where' => "id = '5'"
    ));

    $expected = "DELETE FROM posts WHERE id = '5'";

    $this->assertEquals($expected, $sql);

  } 

  public function testValues() {
    
  }

}