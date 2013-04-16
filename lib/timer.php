<?php

// direct access protection
if(!defined('KIRBY')) die('Direct access is not allowed');

/**
 * 
 * Timer
 * 
 * A handy little timer class to do some
 * code profiling
 *
 * @package Kirby
 */
class Timer {

	// global timer store
	protected static $timer  = null;
	
	// sub timers
	protected static $timers = array();

	/**
	 * Starts a timer
	 * 
	 * @param string $key
	 * @return double
	 */
	static public function start($key = null) {

		// universal timer killer
		if(c::get('timer') === false) return false;

		$time = explode(' ', microtime());
		$time = (double)$time[1] + (double)$time[0];
		
		if(is_null($key)) {
			// global timer
			self::$timer = $time;
		} else {
			// sub timer
			self::$timers[$key] = $time;	
		}
		
		// return the start time
		return $time;

	}

	/**
	 * Stops and retrieves a timer
	 * 
	 * @param string $key
	 * @return double
	 */
	static public function stop($key = null) {
		
		// universal timer killer
		if(c::get('timer') === false) return false;
		
		$time  = explode(' ', microtime());
		$time  = (double)$time[1] + (double)$time[0];
		$timer = (is_null($key)) ? self::$timer : a::get(self::$timers, $key);
	
		return round(($time-$timer), 5);
	
	}

}