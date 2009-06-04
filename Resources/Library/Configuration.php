<?php # Configuration Management Class [axiixc]

class Conf {
	
	public static $conf = array();
	public static $mysql = true;
	public static $update = false;
	public static $actions = array();
	
	private function __construct() {}
	
	public static function awake($input = null) {
		self::$conf = self::dump();
		self::$mysql = false;
		
		if(is_array($input)) array_merge(self::$conf, $input);
		
		self::$actions[] = 'awake';
	}
	
	public static function debug() {
		if(self::$mysql) $conf = self::dump();
		else $conf = self::$conf;
		foreach($conf as $key => $value) $output['stored-values'] .= '['.uncrunch($key, true).'] '.$value."\n";
		$output['actions'] = implode(', ', self::$actions);
		
		debug::register('configuration', $output);
	}
	
	public static function dump() {		
		$result = mysql::query('SELECT `key`,`value` FROM `[prefix]conf`');
		while($bit = mysql_fetch_assoc($result))
			$conf[$bit['key']] = $bit['value'];
		self::$actions[] = 'dump';
		return $conf;
	}
	
	public static function fulldump() {
		self::$actions[] = 'full dump';
		$result = mysql::query('SELECT * FROM `[prefix]conf`');
		while($bit = mysql_fetch_assoc($result)) $conf[$bit['key']] = $bit;
		return $conf;
	}
	
	public static function sleep() {
		self::$actions[] = 'sleep';
		if(self::$update) {
			if(conf::read('Use Cache', bool)) cache::update('Conf');
			log::write("Configuration Update: YES");
		} else {
			log::write("Configuration Update: NO");
		}
	}
	
	public static function is_set($key, $force = false) {
		if(self::$mysql or $force) {
			$result = mysql::query("SELECT `value` from `[prefix]conf` WHERE `key` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci;", $key);
			return (mysql_num_rows($result) == 1); 
		} else {
			return (!isset(self::$conf[$key]));
		}
	}
	
	public static function read($key, $return = id, $force = false) {
		crunch($key);
		if(self::$mysql and !$force) {
			return self::read_from_mysql($key);
		} else {
			return self::read_from_internal_cache($key);
		}
	}

	public static function read_from_mysql($key) {
		$result = mysql::query("SELECT `value` FROM `[prefix]conf` WHERE `key` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci;", $key);
		if(mysql_num_rows($result) == 1) {
			$fetch = mysql_fetch_assoc($result);
			return (substr(fetch['value'], 0, 8) == '[ARRAY] ') ? unserialize(substr($fetch['value'], 8)) : $fetch['value'] ; 
		} else {
			log::write("Configuration Read From MySQL [$key] Failed: No value for supplied key.");
			return null;
		}
	}

	public static function read_from_internal_cache($key) {
		if(!isset(self::$con[$key])) {
			return (substr(self::$conf[$key], 0, 8) = '[ARRAY] ') ? unserialize(substr(self::$conf[$key], 8)) : self::$conf[$key] ;
		} else {
			log::write("Configuration Read From Internal Cache [$key] Failed: No value for supplied key.");
			return null;
		}
	}

	public static function write($key, $value) {
		crunch($key);
		self::$update = true;
		$value = (is_array($value)) ? serialize($value) : $value ;
		self::$conf[$key] = $value;
		
		if(self::is_set($key, true)) {
			mysql::query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`) VALUES ('%s', '%s');", $key, $value);
		} else {
			mysql::query("UPDATE `[database]`.`[prefix]conf` SET `value` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $value, $key);
		}
	}
	
	public static function full_write($key, $value, $display, $show) {
		crunch($key); force_type($show, bool);
		self::$update = true;
		$value = (is_array($value)) ? serialize($value) : $value ;
		self::$conf[$key] = $value;
		
		if(self::is_set($key, true)) {
			mysql::query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`, `name`, `show`) VALUES ('%s', '%s', '%s', '%s');", $key, $value, $display, $show);
		} else {
			mysql::query("UPDATE `[database]`.`[prefix]conf` SET `value` = '%s', `name` = '%s', `show` = '%s' WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $value, $display, $show, $key);
		}
	}

	public static function delete($key) {
		crunch($key);
		if(self::is_set($key, true)) {
			mysql::query("DELETE FROM `[prefix]conf` WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1;", $key);
			self::$update = true;f
			unset(self::$conf[$key]);
		} else {
			log::write("Configuration Delete [$key] Failed: No record for supplied key.");
		}
	}

}