<?php

require_once('lib/bootstrap.php');

class RouterTest extends PHPUnit_Framework_TestCase {
 
  public function testRegister() {

    router::register('GET', 'uri', array());
    router::register('POST', 'uri', array());
    router::register('PUT', 'uri', array());
    router::register('DELETE', 'uri', array());

    $routes = router::routes();

    $this->assertTrue(isset($routes['GET']['uri']));
    $this->assertTrue(isset($routes['POST']['uri']));
    $this->assertTrue(isset($routes['PUT']['uri']));
    $this->assertTrue(isset($routes['DELETE']['uri']));

    router::register(array('GET', 'POST'), 'anotheruri', array());

    $routes = router::routes();

    $this->assertTrue(isset($routes['GET']['anotheruri']));
    $this->assertTrue(isset($routes['POST']['anotheruri']));
    
  }

  public function testMatch() {

    uri::current('blog');

    // exact matches
    router::register('GET', 'blog', array());    
        
    $route = router::match();
    
    $this->assertInstanceOf('Route', $route);
    $this->assertEquals('GET', $route->method());
    $this->assertEquals('blog', $route->pattern());
    $this->assertEquals(array(), $route->options());
    $this->assertEquals(array(), $route->action());

    uri::current('blog/2012/12/12');

    // (:all) wildcard
    router::register('GET', 'blog/(:all)', array());    

    $route = router::match();

    $this->assertEquals(array('2012/12/12'), $route->options());

    // remove all existing routes
    router::reset();

    // (:num) wildcard
    router::register('GET', 'blog/(:num)/(:num)/(:num)', array());    

    $route = router::match();

    $this->assertEquals(array('2012', '12', '12'), $route->options());

    // remove all existing routes
    router::reset();

    uri::current('blog/2012/12');

    // (:num?) wildcard
    router::register('GET', 'blog/(:num?)/(:num?)/(:num?)', array());    

    $route = router::match();

    $this->assertEquals(array('2012', '12'), $route->options());

    // remove all existing routes
    router::reset();

    uri::current('blog/category/design');

    // (:alpha) wildcard
    router::register('GET', 'blog/category/(:alpha)', array());    

    $route = router::match();

    $this->assertEquals(array('design'), $route->options());

    // remove all existing routes
    router::reset();

    // (:alpha) wildcard
    router::register('GET', 'blog/category/(:alpha)', array());    

    // full url
    $route = router::match('http://mydomain.com/blog/category/design');

    $this->assertEquals(array('design'), $route->options());

    // relative url
    $route = router::match('/blog/category/design');

    $this->assertEquals(array('design'), $route->options());

    $this->assertEquals(router::route(), $route);
    $this->assertEquals(array('design'), router::options());

  }


}