<?php

class Registry {
	
	public static $objects = array();
	public static $environment = array();
	
	private function __construct() {}
	
	public static function register($object_name, $object) {
		$object_name = crunch($object_name);
		if(is_object($object)) {
			if(isset(self::$objects[$object_name])) Log::write("Registry::register($object_name) Object already registered.");
			else self::$objects[$object_name] = $object;
		} else {
			Log::write("Registry::register($object_name) Not an object.");
		}
	}
	
	public static function fetch($object_name) {
		$object_name = crunch($object_name);
		if(isset(self::$objects[$object_name])) return self::$objects[$object_name];
		else Log::write("Registry::fetch($object_name) Object not registered.");
	}

	public static function destroy($object_name) {
		$object_name = crunch($object_name);
		if(isset(self::$objects[$object_name])) {
			unset(self::$objects[$object_name]);
		} else {
			Log::write("Registry::destroy($object_name) Not an object.");
		}
	}
	
	public static function write($name, $value) {
		$name = crunch($name);
		self::$environment[$name] = $value;
	}
	
	public static function read($name) {
		$name = crunch($object_name);
		if(isset(self::$environment[$name])) return self::$environment[$name];
		else Log::write("Registry::read($name) Environment variable does not exist.");
	}
	
	public static function delete($name) {
		$name = crunch($object_name);
		if(isset(self::$environment[$name])) unset(self::$environment[$name]);
		else Log::write("Registry::delete($name) Environment variable does not exist.");
	}
	
	public static function diagnostics($return=false) {
		foreach(self::$objects as $name => $foo) {
			$name = uncrunch($name);
			$output['Objects'] .= "$name ";
		} foreach(self::$environment as $name => $value) {
			$name = uncrunch($name);
			$output['Environment'] .= "<br />  => <span style=\"color:#7096C2\">$name</span> : $value";
		} return diagnostic($output, $return);
	}
	
}