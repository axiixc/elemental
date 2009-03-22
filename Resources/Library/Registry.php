<?php

class Registry {
	
	public static $objects;
	public static $environment;
	
	private function __construct() {
	}
	
	public static function register($object_name, $object) {
		$object_name = strtolower($object_name);
		if(is_object($object)) {
			if(isset(self::$objects[$object_name])) Log::write("Registry::register($object_name) Object already registered.");
			else self::$objects[$object_name] = $object;
		} else {
			Log::write("Registry::register($object_name) Not an object.");
		}
	}
	
	public static function fetch($object_name) {
		$object_name = strtolower($object_name);
		if(isset(self::$objects[$object_name])) return self::$objects[$object_name];
		else Log::write("Registry::fetch($object_name) Object not registered.");
	}
	
	public static function write($name, $value) {
		$name = strtolower($name);
		self::$environment[$name] = $value;
	}
	
	public static function read($name) {
		$name = strtolower($name);
		if(isset(self::$environment[$name])) return self::$environment[$name];
		else Log::write("Registry::read($name) Environment variable does not exist.");
	}
	
	public static function delete($name) {
		$name = strtolower($name);
		if(isset(self::$environment[$name])) unset(self::$environment[$name]);
		else Log::write("Registry::delete($name) Environment variable does not exist.");
	}
	
}