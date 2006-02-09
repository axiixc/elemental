<?php # System [ axiixc ] : Main system class

class Elemental {
	
	public $default_ui, $ui, $default_app, $app, $mode_dev, $load_state;
	
	public function __construct() {
		$this->default_ui = $this->confRead('default-ui');
		$this->default_app = strtolower($this->confRead('default-application'));
		if(isset($_GET['app'])) $this->app = strtolower($_GET['app']);
		else $this->app = $this->default_app;
		$this->mode_dev = $this->confRead('mode-development');
	}
	
	public function confRead($key, $count=false) {
		if ($data = fetch('DB')->query("SELECT * FROM `[prefix]conf` WHERE `key` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci")) {
			if($count) return mysql_num_rows($data);
			else {
				$output = mysql_fetch_assoc($data);
				return $output['value'];
			}
		}
	}
	
	public function confWrite($key, $value) {
		if($this->confRead($key, true)) return fetch('DB')->query("INSERT INTO `[database]`.`[prefix]conf` (`key`, `value`) VALUES ('%s', '%s');", $key, $value);
		else return fetch('DB')->query("UPDATE `[database]`.`[prefix]conf` SET `value` = '%s' WHERE CONVERT( `[prefix]conf`.`key` USING utf8 ) = '%s';", $value, $key);
	}
	
	public function confDelete($key) {
		return fetch('DB')->query("DELETE FROM `[prefix]conf` WHERE CONVERT(`[prefix]conf`.`key` USING utf8) = '%s' LIMIT 1", $key);
	}
	
	public function end($error_id) {
		$error_codes = EXFetchResourceResource('error_codes');
		die(sprintf(Registry::fetch('UI')->template('error_code'), $error_code[$error_id], $error_id));
	}
	
}

function EXApplication($item) {
	$app = root."Applications/$item.app/Reference.php";
	if(file_exists($app)) return $app;
	else return nil;
}

function EXPackage($item) {
	$pk = rsc."Packages/$item.pk/Package.php";
	if(file_exists($pk)) return $pk;
	else return nil;
}

function EXLibrary($item) {
	$lib = rsc."Library/$item.php";
	if(file_exists($lib)) return $lib;
	else return nil;
}