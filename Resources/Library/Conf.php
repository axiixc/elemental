<?php # Configuration Manipulation Class [axiixc]

class Conf {
	
	public static $conf = array();
	public static $mysql = true;
	public static $update = false;
	public static $actions = array();
	
	private function __construct() {}
	
	public static function awake($array=false) {
		if($array !== false and is_array($array)) {
			foreach($array as $x) self::$conf[$x['key']] = $x['value'];
			self::$mysql = false;
		} else {
			$result = MySQL::query("SELECT `key`,`value` FROM `[prefix]conf`");
			while($bit = mysql_fetch_assoc($result)) self::$conf[$bit['key']] = $bit['value'];
			#foreach(self::$conf as $key => $value) if(substr($value, 0, 8) == '[ARRAY] ') self::$conf[$key] = unserialize($value);
			self::$mysql = false;	
		} self::$conf['www-path'] = 'http://carbon.local/Elemental/';
		self::$actions[] = 'AWAKE';
	}
	
	public static function diagnostics($return=false) {	
		foreach(self::$conf as $key => $value) {
			$output[$key] = self::read($key);
			if(is_array($output[$key])) $output[$key] = print_r($output[$key], true);
		} $output['actions'] = print_r(self::$actions, true);
		return diagnostic($output, $return);
	}
	
	public static function dump() {
		self::$actions[] = 'DUMP';
		return array('conf' => self::$conf, 'mysql' => self::$mysql, 'update' => self::$update);
	}
	
	public static function fulldump() {
		self::$actions[] = 'FULLDUMP';
		$result = MySQL::query("SELECT * FROM `[prefix]conf`");
		while($bit = mysql_fetch_assoc($result)) $return[$bit['key']] = $bit;
		return $return;
	}
	
	public static function sleep() {
		self::$actions[] = 'SLEEP';
		if(self::$update) {
			Cache::update("Conf");
			Log::write("Conf::is_update(true) Cache updated.");
		} else Log::write("Conf::is_update(false) Cache untouched.");
	}
	
	public static function is_set($key, $force_mysql=false) {
		self::$actions[] = "ISSET $key, $force_mysql";
		if(self::$mysql or $force_mysql) {
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
		self::$actions[] = "RFC $key";
		if(!is_null($key)) {
			if(!is_null(self::$conf[$key])) {
				if(substr(self::$conf[$key], 0, 8) == '[ARRAY] ') {
					self::$conf[$key] = unserialize(substr(self::$conf[$key], 8));
				} return self::$conf[$key];
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
		self::$actions[] = "RFM $key";
		if(!is_null($key)) {
			$result = MySQL::query("SELECT `value` FROM `[prefix]conf` WHERE `key` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci;", $key);
			if(mysql_num_rows($result) == 1) {
				$fetch = mysql_fetch_assoc($result);
				if(substr($fetch['value'], 0, 8) == '[ARRAY] ') $fetch['value'] = unserialize(substr($fetch['value'], 8));
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
		self::$actions[] = "WRITE $key => $value";
		$key = crunch($key);
		self::$update = true;
		if(!self::$mysql) self::$conf[$key] = $value;
		if(is_array($value)) $value = '[ARRAY] '.serialize($value);
		if(!is_null($key)) {
			if(!self::is_set($key, true)) { # Insert
				MySQL::query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`) VALUES ('%s', '%s');", $key, $value);
			} else { # Update
				MySQL::query("UPDATE `[database]`.`[prefix]conf` SET `value` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $value, $key);
			}
		} else {
			Log::write("Conf::write($key, $value) Key cannot be null.");
		} return true;
	}
	
	public static function fullwrite($key, $value, $display, $show) {
		self::$actions[] = "FULLWRITE $key => $value, $display, $show";
		$key = crunch($key);
		self::$update = true;
		if(is_array($value)) $value = '[ARRAY] '.serialize($value);
		if(!is_null($key)) {
			if(!self::is_set($key, true)) { # Insert
				MySQL::query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`, `name`, `show`) VALUES ('%s', '%s', '%s', '%s');", $key, $value, $display, $show);
				if(!self::$mysql) self::$conf[$key] = $value;
			} else { # Update
				MySQL::query("UPDATE `[database]`.`[prefix]conf` SET `value` = '%s', `name` = '%s', `show` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $value, $display, $show, $key);
				if(!self::$mysql) self::$conf[$key] = $value;
			}
		} else {
			Log::write("Conf::fullwrite($key, $value, $display, $show) Key cannot be null.");
		}
	}
	
	public static function delete($key) {
		self::$actions[] = "DELETE $key";
		Log::write($key);
		$key = crunch($key);
		self::$update = true;
		if(self::is_set($key, true)) {
			MySQL::query("DELETE FROM `[prefix]conf` WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $key);
			if(!self::$mysql) unset(self::$conf[$key]);
			$update = true;
		} else {
			Log::write("Conf::delete($key) No match for supplied key.");
		}
	}
	
}