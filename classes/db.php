<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

// query class
require_once(ROOT_KIRBY_TOOLKIT_CLASSES . DS . 'db' . DS . 'query.php');
require_once(ROOT_KIRBY_TOOLKIT_CLASSES . DS . 'db' . DS . 'connector.php');

/**
 * 
 * DB
 * 
 * The ingenius Kirby DB class
 * 
 * @package Kirby Toolkit
 */
class Db {

  // the connector object, used to connect to the db
  static protected $connector;
  
  // the established connection
  static protected $connection;
  
  // the database type (mysql, sqlite)
  static protected $type;
  
  // the optional prefix for table names
  static protected $prefix;
  
  // the PDO query statement
  static protected $statement;
  
  // the number of affected rows for the last query
  static protected $affected;
  
  // the last insert id
  static protected $lastId;
  
  // the last query
  static protected $lastQuery;
  
  // the last result set
  static protected $lastResult;
  
  // the last error
  static protected $lastError;  
  
  // set to true to throw exceptions on failed queries
  static protected $fail = false;
  
  // an array with all queries which are being made
  static protected $trace = array();

  /**
   * Connects to a database
   * 
   * @param mixed $params This can either be a config key or an array of parameters for the connection
   * @return object 
   */
  static public function connect($params = null) {

    // start the connector
    self::$connector = new DbConnector($params);

    // store the type and prefix
    self::$type   = self::$connector->type();
    self::$prefix = self::$connector->prefix();
        
    // return the established connection
    return self::$connection = self::$connector->connection();
  
  }
  
  /**
   * Returns the currently active connection
   * 
   * @return object
   */
  static public function connection() {
    return (!is_null(self::$connection)) ? self::$connection : self::connect();
  }

  /**
   * Sets the exception mode for the next query
   * 
   * @param boolean $fail
   */
  static public function fail($fail = true) {
    self::$fail = $fail;
  }

  /**
   * Returns the used database type
   * 
   * @return string
   */
  static public function type() {
    self::connection();
    return self::$type;
  }

  /**
   * Returns the used table name prefix
   * 
   * @return string
   */
  static public function prefix() {
    self::connection();
    return self::$prefix;
  }

  /**
   * Escapes a value to be used for a safe query
   * 
   * @param string $value
   * @return string
   */
  static public function escape($value) {
    return substr(self::connection()->quote($value), 1, -1);
  }

  /**
   * Adds a value to the db trace and also returns the entire trace if nothing is specified
   *
   * @param array $data
   * @return array
   */
  static public function trace($data = null) {
    if(is_null($data)) return self::$trace;  
    self::$trace[] = $data;
  }

  /**
   * Returns the number of affected rows for the last query
   * 
   * @return int
   */
  static public function affected() {
    return self::$affected;
  }

  /**
   * Returns the last id if available
   * 
   * @return int
   */
  static public function lastId() {
    return self::$lastId;
  }

  /**
   * Returns the last query
   * 
   * @return string
   */
  static public function lastQuery() {
    return self::$lastQuery;
  }

  /**
   * Returns the last set of results
   * 
   * @return mixed
   */
  static public function lastResult() {
    return self::$lastResult;
  }

  /**
   * Returns the last db error (exception object)
   * 
   * @return object
   */
  static public function lastError() {
    return self::$lastError;
  }

  /**
   * Private method to execute database queries. 
   * This is used by the query() and execute() methods
   * 
   * @param string $query 
   * @param array $bindings
   * @return mixed
   */
  static protected function hit($query, $bindings = array()) {

    // try to prepare and execute the sql
    try {                                  
  
      self::$statement = self::connection()->prepare($query);        
      self::$statement->execute($bindings);  
      
      self::$affected  = self::$statement->rowCount();  
      self::$lastId    = self::connection()->lastInsertId();
      self::$lastError = null;
      
      // store the final sql to add it to the trace later
      self::$lastQuery = self::$statement->queryString;

    } catch(Exception $e) {

      // store the error
      self::$affected  = 0;
      self::$lastError = $e;                  
      self::$lastId    = null;
      self::$lastQuery = $query;

      // only throw the extension if failing is allowed
      if(self::$fail == true) throw $e;

    }

    // add a new entry to the singleton trace array    
    self::trace(array(
      'query'    => self::$lastQuery, 
      'bindings' => $bindings,
      'error'    => self::$lastError
    ));

    // reset some stuff
    self::$fail = false;

    // return true or false on success or failure
    return is_null(self::$lastError);
                
  }

  /**
   * Exectues a sql query, which is expected to return a set of results
   * 
   * @param string $query
   * @param array $bindings
   * @param array $params
   * @return mixed
   */
  static public function query($query, $bindings = array(), $params = array()) {

    $defaults = array(
      'flag'     => null,
      'method'   => 'fetchAll',
      'fetch'    => 'Object',
      'iterator' => 'Collection', 
    );

    $options = array_merge($defaults, $params);

    if(!self::hit($query, $bindings)) return false;

    // define the default flag for the fetch method
    $flags = $options['fetch'] == 'array' ? PDO::FETCH_ASSOC : PDO::FETCH_CLASS; 

    // add optional flags
    if(!empty($options['flag'])) $flags |= $options['flag'];
    
    // set the fetch mode
    if($options['fetch'] == 'array') {
      self::$statement->setFetchMode($flags);
    } else {
      self::$statement->setFetchMode($flags, $options['fetch']);
    }

    // fetch that stuff
    $results = self::$statement->$options['method']();
    
    if($options['iterator'] == 'array') return self::$lastResult = $results;
    return self::$lastResult = new $options['iterator']($results);
  
  }

  /**
   * Executes a sql query, which is expected to not return a set of results
   * 
   * @param string $query
   * @param array $bindings
   * @return boolean
   */
  static public function execute($query, $bindings = array()) {
    return self::$lastResult = self::hit($query, $bindings);
  }

  /**
   * Sets the current table, which should be queried
   * 
   * @param string $table
   * @return object Returns a DBQuery object, which can be used to build a full query for that table
   */
  static public function table($table) {    
    return new DbQuery(db::prefix() . $table);
  }

  /**
   * Shortcut for select clauses
   * 
   * @param string $table The name of the table, which should be queried
   * @param mixed $columns Either a string with columns or an array of column names
   * @param mixed $where The where clause. Can be a string or an array
   * @param mixed $order 
   * @param int $offset
   * @param int $limit
   * @return mixed
   */
  static public function select($table, $columns, $where = null, $order = null, $offset = 0, $limit = null) {
    return self::table($table)->select($columns)->where($where)->order($order)->offset($offset)->limit($limit)->all();
  }

  /**
   * Shortcut for selecting a single row in a table
   * 
   * @param string $table The name of the table, which should be queried
   * @param mixed $columns Either a string with columns or an array of column names
   * @param mixed $where The where clause. Can be a string or an array
   * @param mixed $order 
   * @param int $offset
   * @param int $limit
   * @return mixed
   */
  static public function first($table, $columns, $where = null, $order = null) {
    return self::table($table)->select($columns)->where($where)->order($order)->first();    
  }

  /**
   * Shortcut for selecting a single row in a table
   * 
   * @see self::first()
   */
  static public function row($table, $columns, $where = null, $order = null) {
    return self::first($table, $column, $where, $order);
  }

  /**
   * Shortcut for selecting a single row in a table
   * 
   * @see self::first()
   */
  static public function one($table, $columns, $where = null, $order = null) {
    return self::first($table, $column, $where, $order);
  }

  /**
   * Returns only values from a single column
   * 
   * @param string $table The name of the table, which should be queried
   * @param mixed $column The name of the column to select from
   * @param mixed $where The where clause. Can be a string or an array
   * @param mixed $order 
   * @param int $offset
   * @param int $limit
   * @return mixed
   */
  static public function column($table, $column, $where = null, $order = null, $limit = null) {
    return self::table($table)->where($where)->order($order)->offset($offset)->limit($limit)->column($column);
  }

  /**
   * Shortcut for inserting a new row into a table
   * 
   * @param string $table The name of the table, which should be queried
   * @param string $values An array of values, which should be inserted
   * @return boolean 
   */
  static public function insert($table, $values) {
    return self::table($table)->insert($values);
  }

  /**
   * Shortcut for updating a row in a table
   * 
   * @param string $table The name of the table, which should be queried
   * @param string $values An array of values, which should be inserted
   * @param mixed $where An optional where clause
   * @return boolean 
   */
  static public function update($table, $values, $where = null) {
    return self::table($table)->where($where)->update($values);
  }

  /**
   * Shortcut for counting rows in a table
   * 
   * @param string $table The name of the table, which should be queried
   * @param string $where An optional where clause
   * @return int
   */
  static public function count($table, $where = null) {
    return self::table($table)->where($where)->count();    
  }

  /**
   * Shortcut for calculating the minimum value in a column
   * 
   * @param string $table The name of the table, which should be queried
   * @param string $column The name of the column of which the minimum should be calculated
   * @param string $where An optional where clause
   * @return mixed
   */
  static public function min($table, $column, $where = null) {
    return self::table($table)->where($where)->min($column);    
  }

  /**
   * Shortcut for calculating the maximum value in a column
   * 
   * @param string $table The name of the table, which should be queried
   * @param string $column The name of the column of which the maximum should be calculated
   * @param string $where An optional where clause
   * @return mixed
   */
  static public function max($table, $column, $where = null) {
    return self::table($table)->where($where)->max($column);    
  }

  /**
   * Shortcut for calculating the average value in a column
   * 
   * @param string $table The name of the table, which should be queried
   * @param string $column The name of the column of which the average should be calculated
   * @param string $where An optional where clause
   * @return mixed
   */
  static public function avg($table, $column, $where = null) {
    return self::table($table)->where($where)->avg($column);    
  }

  /**
   * Shortcut for calculating the sum of all values in a column
   * 
   * @param string $table The name of the table, which should be queried
   * @param string $column The name of the column of which the sum should be calculated
   * @param string $where An optional where clause
   * @return mixed
   */
  static public function sum($table, $column, $where = null) {
    return self::table($table)->where($where)->sum($column);    
  }

}