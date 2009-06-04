<?php # Log [axiixc]

class log {
	
	private static $log = array();
	private static $perm_log = array();
	private static $output = false;
	
	private function __construct() {}
	
	public static function write() {
		$args = func_get_args();
		$string = array_shift($args);
		
		if($string === persistant) {
			$string = array_shift($args);
			if(count($args) > 0) $string = vsprintf($string, $args);
			self::$perm_log[] = array('time' => time(), 'message' => $string);
		}
		
		if(count($args) > 0) $string = vsprintf($string, $args);
		self::$log[] = array('time' => time(), 'message' => $string);
	}
	
	public static function read($return=false, $array=false) {
		self::write("Log Close: ".time());
		$master = template('Log', '<pre class="log">%1$s</pre>');
		$template = template('Log Message', '[%1$03s] %3$s<br />');
		
		if(count(self::$log) > 0) {
			foreach(self::$log as $id => $message)
				$log .= sprintf($template, $id, $message['time'], $message['message']);
		} else {
			$log = sprintf($template, '000', time(), 'Log::read() No log entries.');
		}
		
		$write = sprintf($master, $log);
		
		if($return) {
			return ($array) ? self::$log : $write) ;
		} else {
			echo $write;
		}
	}
	
	public static function output($x=null) {
		if(is_null($x)) return self::$output;
		else self::$output = $x;
	}
	
	public static function clear() {
		if(count(self::$log) > 0) {
			self::$log = array();
		} # No else output (for obvious reasons :P)
	}
	
	public static function sleep() {
		if(count(self::$perm_log) > 0) {
			$ip = client_ip;
			foreach(self::$perm_log as $item) export('Permanent Log', "[{$item['time']}:$ip] {$item['message']}", append);
		}
	}
	
}

# Comment out if you don't want it
Log::write("Log Start: ".time());