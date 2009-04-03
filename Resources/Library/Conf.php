<?php

class Conf {
	
	public static $conf = array();
	public static $mysql = true;
	public static $update = false;
	
	private function __construct() {}
	
	public static function awake($array=false) {
		if($array !== false and is_array($array)) {
			self::$conf = $array;
		} else {
			$result = MySQL::query("SELECT * FROM `[prefix]conf`");
			while($bit = mysql_fetch_assoc($result)) self::$conf[$bit['key']] = $bit['value'];
			foreach(self::$conf as $key => $value) if(substr($value, 0, 8) == '[ARRAY] ') self::$conf[$key] = unfold($value);
			self::$mysql = false;	
		}
	}
	
	public static function diagnostics($return=false) {
		return diagnostic(self::$conf, $return);
	}
	
	public static function dump() {
		return array('conf' => self::$conf, 'mysql' => self::$mysql, 'update' => self::$update);
	}
	
	public static function sleep() {
		if(self::$update) {
			Cache::update("Conf");
			Log::write("Conf::is_update(true) Cache updated.");
		} else Log::write("Conf::is_update(false) Cache untouched.");
	}
	
	public static function is_set($key, $force_mysql=false) {
		if(self::$mysql and !$force_mysql) {
			
			$result = MySQL::query("SELECT `value` FROM `[prefix]conf` WHERE `key` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci;", $key);
			return (mysql_num_rows($result) == 1) ? true : false ;
		} else  return (!is_null(self::$conf[$key])) ? true : false ;
	}

	public static function read($key, $force_mysql=false) {
		$key = crunch($key);
		if(self::$mysql and !$force_mysql) return self::read_from_mysql($key);
		else return self::read_from_cache($key);
	}
	
	private static function read_from_cache($key) {
		if(!is_null($key)) {
			if(!is_null(self::$conf[$key])) {
				return self::$conf[$key];
			} else {
				Log::write("Conf::read($key, cache) No match for supplied key.");
				return null;
			}
		} else {
			Log::write("Conf::read(cache) Key cannot be null.");
			return null;
		}
	}
	
	private static function read_from_mysql($key) {
		if(!is_null($key)) {
			$result = MySQL::query("SELECT `value` FROM `[prefix]conf` WHERE `key` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci;", $key);
			if(mysql_num_rows($result) == 1) {
				$fetch = mysql_fetch_assoc($result);
				if(substr($fetch['value'], 0, 8) == '[ARRAY] ') $fetch['value'] = unfold($fetch['value']);
				return $fetch['value'];
			} else {
				Log::write("Conf::read($key, mysql) No match for supplied key.");
				return null;
			}
		} else {
			Log::write("Conf::read(mysql) Key cannot be null.");
			return null;
		}
	}
	
	public static function write($key, $value) {
		$key = crunch($key);
		self::$update = true;
		if(is_array($value)) $value = '[ARRAY] '.fold($value);
		if(!is_null($key)) {
			if(self::is_set($key, true)) { # Insert
				MySQL::query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`, `name`, `show`) VALUES ('%s', '%s', '%s', '%s');", $key, $value, null, null);
				if(!self::$mysql) self::$conf[$key] = $value;
			} else { # Update
				MySQL::query("UPDATE `[database]`.`[prefix]conf` SET `value` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $value, $key);
				if(!self::$mysql) self::$conf[$key] = $value;
			}
		} else {
			Log::write("Conf::write($key, $value) Key cannot be null.");
		}
	}
	
	public static function fullwrite($key, $value, $display, $show) {
		$key = crunch($key);
		self::$update = true;
		if(is_array($value)) $value = '[ARRAY] '.fold($value);
		if(!is_null($key)) {
			if(self::is_set($key, true)) { # Insert
				MySQL::query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`, `name`, `show`) VALUES ('%s', '%s', '%s', '%s');", $key, $value, $display, $show);
				if(!self::$mysql) self::$conf[$key] = $value;
			} else { # Update
				MySQL::query("UPDATE `[database]`.`[prefix]conf` SET `key` = '%s', `value` = '%s', `name` = '%s', `show` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $key, $value, $display, $show);
				if(!self::$mysql) self::$conf[$key] = $value;
			}
		} else {
			Log::write("Conf::fullwrite($key, $value, $display, $show) Key cannot be null.");
		}
	}
	
	public static function delete($key) {
		$key = crunch($key);
		self::$update = true;
		if(!self::is_set($key, true)) {
			MySQL::query("DELETE FROM `[prefix]conf` WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $key);
			if(!self::$mysql) unset(self::$conf[$key]);
		} else {
			Log::write("Conf::delete($key) No match for supplied key.");
		}
	}
	
}