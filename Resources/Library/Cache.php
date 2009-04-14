<?php

class Cache {
	
	private static $cache = array();
	private static $resources = array();
	private static $enabled = false;
	
	private function __construct() {}
	
	public static function awake() {
		if(self::$enabled) Log::write("Cache::awake() Cache has already been loaded.");
		else {
			self::$cache = import("Cache File");
			self::$resources = import("Cache Resources");
			self::$enabled = true;
		}
	}
	
	public static function sleep() {
		/* TBD */
	}
	
	public static function enabled() {
		return self::$enabled;
	}
	
	public static function fetch($id) {
		if(self::$enabled) {
			$id = crunch($id);
			if(isset(self::$cache[$id])) return self::$cache[$id];
			else {
				Log::write("Cache::fetch($id) No cache for given id.");
				return false;
			}
		} else {
			Log::write("Cache::fetch($id) Cache not loaded.");
			return false;
		}
	}
	
	public static function register($id, $sql) {
		self::$resources[$id] = $sql;
		register_resource("Cache Resources", self::$resources);
	}
	
	public static function update($items=true) {
		if($items === true) {
			foreach(self::$resources as $resource => $sql) {
				$result = MySQL::query($sql);
				while($bit = mysql_fetch_assoc($result)) $cache[$resource][] = $bit;
			} register_resource("Cache File", $cache);
		} else {
			foreach(explode(',', $items) as $rsc) {
				$rsc = crunch(trim($rsc));
				if(isset(self::$resources[$rsc])) {
					if(isset($cache[$rsc])) unset($cache[$rsc]);
					$result = MySQL::query(self::$resources[$rsc]);
					while($bit = mysql_fetch_assoc($result)) $cache[$rsc][] = $bit;
				} else Log::write("Cache::update($rsc) Resource not found in Cache Resources.");
			} register_resource("Cache File", $cache);
		}
	}
	
	public static function diagnostics($return=false) {
		foreach(self::$cache as $name => $value) {
			$output[$name] = print_r($value, true);
		} $output['resources'] = print_r(self::$resources, true);
		return diagnostic($output, $return);
	}
	
	public static function is_resource() {
		if(count(func_get_args()) > 0) {
			foreach(func_get_args() as $id) $switch = (isset(self::$resource[$id])) ? true : false ;
			return $switch;
		} else {
			Log::write("Cache::is_resource() Requires at least one argument, none given.");
			return false;
		}
	}
	
	public static function is_available() {
		if(count(func_get_args()) > 0) {
			foreach(func_get_args() as $id) $switch = (isset(self::$cache[$id])) ? true : false ;
			return $switch;
		} else {
			Log::write("Cache::is_available() Requires at least one argument, none given.");
			return false;
		}
	}
	
}