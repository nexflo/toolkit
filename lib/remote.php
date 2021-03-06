<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// dependencies
require_once(ROOT_KIRBY_TOOLKIT_LIB . DS . 'remote' . DS . 'response.php');

/**
 * Remote
 * 
 * A handy little class to handle
 * all kind of remote requests 
 * 
 * @package Kirby Toolkit
 */
class Remote {

  // store for the response object
  protected $response = null;
  
  // all options for the request
  protected $options = array();
  
  // all received headers
  protected $headers = array();

  /**
   * Constructor
   * 
   * @param string $url
   * @param array $options
   */
  public function __construct($url, $options = array()) {

    // all defaults for a request
    $defaults = array(
      'method'   => 'GET',
      'url'      => $url,
      'data'     => array(),
      'timeout'  => 10,
      'headers'  => array(),
      'encoding' => 'utf-8',
      'agent'    => null
    );
    
    // set all options
    $this->options = array_merge($defaults, $options);    
      
    // send the request
    $this->send();

  }

  /**
   * Sets up all curl options and sends the request
   *
   * @return object RemoteResponse 
   */
  protected function send() {

    // start a curl request
    $curl = curl_init();

    // curl options
    $params = array(
      CURLOPT_URL             => $this->options['url'],
      CURLOPT_ENCODING        => $this->options['encoding'],
      CURLOPT_CONNECTTIMEOUT  => $this->options['timeout'],
      CURLOPT_TIMEOUT         => $this->options['timeout'],
      CURLOPT_AUTOREFERER     => true,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_FOLLOWLOCATION  => true,
      CURLOPT_MAXREDIRS       => 10,
      CURLOPT_SSL_VERIFYPEER  => false,
      CURLOPT_HEADER          => false,
      CURLOPT_HEADERFUNCTION  => array($this, 'header'),
    );
    
    // add all headers
    if(!empty($this->options['headers'])) $params[CURLOPT_HTTPHEADER] = $this->options['headers'];

    // add the user agent
    if(!empty($this->options['agent'])) $params[CURLOPT_USERAGENT] = $this->options['agent'];

    // do some request specific stuff
    switch(strtolower($this->options['method'])) {
      case 'post':
        $params[CURLOPT_POST]       = true;
        $params[CURLOPT_POSTFIELDS] = http_build_query($this->options['data']);
        break;
      case 'put':

        $params[CURLOPT_PUT]        = true;
        $params[CURLOPT_POSTFIELDS] = http_build_query($this->options['data']);

        // put a file 
        if($this->options['file']) {
          $params[CURLOPT_INFILE]     = fopen($this->options['file'], 'r');
          $params[CURLOPT_INFILESIZE] = f::size($this->options['file']);
        }
    
        break;
      case 'delete':
        $params[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $params[CURLOPT_POSTFIELDS]    = http_build_query($this->options['data']);
        break;
      case 'head':
        $params[CURLOPT_CUSTOMREQUEST] = 'HEAD';
        $params[CURLOPT_POSTFIELDS]    = http_build_query($this->options['data']);
        $params[CURLOPT_NOBODY]        = true;
        break;
    }

    curl_setopt_array($curl, $params);

    $content  = curl_exec($curl);
    $error    = curl_errno($curl);
    $message  = curl_error($curl);
    $info     = curl_getinfo($curl);

    curl_close($curl);

    $this->response = new RemoteResponse();
    $this->response->headers = $this->headers;
    $this->response->error   = $error;
    $this->response->message = $message;
    $this->response->content = $content;
    $this->response->info    = $info;    
  
    return $this->response;

  }

  /**
   * Used by curl to parse incoming headers
   * 
   * @param object $curl the curl connection
   * @param string $header the header line
   * @return int the length of the heade
   */
  protected function header($curl, $header) {
  
    $parts = str::split($header, ':');
    
    if(!empty($parts[0]) && !empty($parts[1])) {
      $this->headers[$parts[0]] = $parts[1];  
    }
  
    return strlen($header);
  
  }

  /**
   * Returns all options which have been 
   * set for the current request
   * 
   * @return array
   */
  public function options() {
    return $this->options;
  }

  /**
   * Returns the response object for 
   * the current request
   * 
   * @return object RemoteResponse
   */
  public function response() {
    return $this->response;
  }

  /**
   * Static method to init this class and send a request
   * 
   * @param string $url
   * @param array $params
   * @return object RemoteResponse
   */
  static public function request($url, $params = array()) {
    $request = new self($url, $params);
    return $request->response();
  }

  /**
   * Static method to send a GET request
   * 
   * @param string $url
   * @param array $params
   * @return object RemoteResponse
   */
  static public function get($url, $params = array()) {

    $defaults = array(
      'method' => 'GET', 
      'data'   => array(),
    );

    $options = array_merge($defaults, $params);
    $query   = http_build_query($options['data']);    
    $url     = (!empty($query) && url::hasQuery($url)) ? $url . '&' . $query : $url . '?' . $query;

    // remove the data array from the options
    unset($options['data']);

    $request = new self($url, $options);
    return $request->response();

  }

  /**
   * Static method to send a POST request
   * 
   * @param string $url
   * @param array $params
   * @return object RemoteResponse
   */
  static public function post($url, $params = array()) {

    $defaults = array(
      'method' => 'POST'
    );

    $request = new self($url, array_merge($defaults, $params));
    return $request->response();

  }

  /**
   * Static method to send a PUT request
   * 
   * @param string $url
   * @param array $params
   * @return object RemoteResponse
   */
  static public function put($url, $params = array()) {

    $defaults = array(
      'method' => 'PUT'
    );

    $request = new self($url, array_merge($defaults, $params));
    return $request->response();

  }

  /**
   * Static method to send a DELETE request
   * 
   * @param string $url
   * @param array $params
   * @return object RemoteResponse
   */
  static public function delete($url, $params = array()) {

    $defaults = array(
      'method' => 'DELETE'
    );

    $request = new self($url, array_merge($defaults, $params));
    return $request->response();

  }

  /**
   * Static method to send a HEAD request
   * 
   * @param string $url
   * @param array $params
   * @return object RemoteResponse
   */
  static public function head($url, $params = array()) {

    $defaults = array(
      'method' => 'HEAD'
    );

    $request = new self($url, array_merge($defaults, $params));
    return $request->response();

  }

  /**
   * Static method to send a HEAD request
   * which only returns an array of headers
   * 
   * @param string $url
   * @param array $params
   * @return array
   */
  static public function headers($url, $params = array()) {
    $request = self::head($url, $params);
    return $request->headers();
  }

}