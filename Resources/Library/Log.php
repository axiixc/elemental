<?php

class Log {
	
	private static $log = array();
	
	private function __construct() {
	}
	
	public static function write($string) {
		self::$log[] = time().'] '.$string;
	}
	
	public static function read() {
		if(count(self::$log) > 0) {
			echo '<pre>';
			foreach(self::$log as $id => $msg) {
				printf("[%03s : %s\n", $id, $msg);
			} echo '</pre>';
		} else {
			echo "<pre>000 ".time()." Log::read() No log entries.</pre>";
		}
	}
	
	public static function clear() {
		if(count(self::$log) > 0) {
			self::$log = array();
		} # No else output (for obvious reasons :P)
	}
	
}