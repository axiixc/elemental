<?php

class Conf {
	
	private function __construct() {
	}
	
	public static function read($key, $supress=false) {
		$key = crunch($key);
		if(!is_null($key)) {
			$result = MySQL::query("SELECT `value` FROM `[prefix]conf` WHERE `key` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci;", $key);
			if(mysql_num_rows($result) == 1) {
				$fetch = mysql_fetch_assoc($result);
				return $fetch['value'];
			} else {
				if(!$supress) Log::write("Conf::read($key) No match for supplied key.");
				return null;
			}
		} else {
			if(!$supress) Log::write("Conf::read() Key cannot be null.");
			return null;
		}
	}
	
	public static function write($key, $value) {
		$key = crunch($key);
		if(!is_null($key)) {
			if(is_null(self::read($key, true))) { # Insert
				MySQL::query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`, `name`, `show`) VALUES ('%s', '%s', '%s', '%s');", $key, $value, null, null);
			} else { # Update
				MySQL::query("UPDATE `[database]`.`[prefix]conf` SET `value` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $value, $key);
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
			} else { # Update
				MySQL::query("UPDATE `[database]`.`[prefix]conf` SET `key` = '%s', `value` = '%s', `name` = '%s', `show` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $key, $value, $display, $show);
			}
		} else {
			Log::write("Conf::fullwrite($key, $value, $display, $show) Key cannot be null.");
		}
	}
	
	public static function delete($key) {
		$key = crunch($key);
		if(!is_null(self::read($key, true))) {
			MySQL::query("DELETE FROM `[prefix]conf` WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $key);
		} else {
			Log::write("Conf::delete($key) No match for supplied key.");
		}
	}
	
}