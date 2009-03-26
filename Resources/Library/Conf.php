<?php

class Conf {
	
	private static $conf = array();
	private static $mysql = true;
	
	private function __construct() {
	}
	
	public static function preload() {
		$result = MySQL::query("SELECT * FROM `[prefix]conf`");
		while($bit = mysql_fetch_assoc($result)) self::$conf[$bit['key']] = $bit['value'];
		self::$mysql = false;
	}
	
	public static function postload() {
		echo "<pre>";
		foreach(self::$conf as $key => $value) {
			$key = uncrunch($key);
			echo "<span style=\"color:#2C68C1\">[$key]</span> $value<br />";
		}
		echo "</pre>";
	}
	
	public static function read($key, $supress=false) {
		if(self::$mysql) return self::read_from_mysql($key, $supress);
		else return self::read_from_cache($key, $supress);
	}
	
	private static function read_from_cache($key, $supress=false) {
		$key = crunch($key);
		if(!is_null($key)) {
			if(!is_null(self::$conf[$key])) {
				return self::$conf[$key];
			} else {
				if(!$supress) Log::write("Conf::read($key, cache) No match for supplied key.");
				return null;
			}
		} else {
			if(!$supress) Log::write("Conf::read(cache) Key cannot be null.");
			return null;
		}
	}
	
	private static function read_from_mysql($key, $supress=false) {
		$key = crunch($key);
		if(!is_null($key)) {
			$result = MySQL::query("SELECT `value` FROM `[prefix]conf` WHERE `key` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci;", $key);
			if(mysql_num_rows($result) == 1) {
				$fetch = mysql_fetch_assoc($result);
				return $fetch['value'];
			} else {
				if(!$supress) Log::write("Conf::read($key, mysql) No match for supplied key.");
				return null;
			}
		} else {
			if(!$supress) Log::write("Conf::read(mysql) Key cannot be null.");
			return null;
		}
	}
	
	public static function write($key, $value) {
		$key = crunch($key);
		if(!is_null($key)) {
			if(is_null(self::read($key, true))) { # Insert
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
		if(!is_null($key)) {
			if(is_null(self::read($key, true))) { # Insert
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
		if(!is_null(self::read($key, true))) {
			MySQL::query("DELETE FROM `[prefix]conf` WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $key);
			if(!self::$mysql) unset(self::$conf[$key]);
		} else {
			Log::write("Conf::delete($key) No match for supplied key.");
		}
	}
	
}