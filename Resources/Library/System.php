<?php

class system {
	
	public static $applications;
	public static $libraries;
	public static $packages;
	
	public static function awake() {
		self::$applications = dir_read(root . 'Applications', true, crunch);
		self::$libraries = dir_read(root . 'Resources/Libraries', true, crunch, -4);
		self::$packages = dir_read(root . 'Resources/Packages', true, crunch);
	}
	
	public static function application() {
		return (isset($_GET['app'])) ? strtolower($_GET['app']) : strtolower(conf::read('Application')) ;
	}
	
	public static function debug() {
		$output['application'] = self::application();
		$output['override'] = self::$override;
		debug::register('system', $output);
	}
	
	public function kill($reason=null) {
		log::write(true, "System Killed: %s", $reason);
		log::sleep();
		die('Session was killed');
	}
	
}

function package($identifier) {
	crunch($identifier);
	if(file_exists(system::$packages[$identifier] . 'Package.php')) {
		return system::$package[$identifier] . 'Package.php';
	} else if(file_exists(system::$package[$identifier])) {
		log::write("Package Include [$identifier] Failed: Not a valid package.");
		return nil;
	} else {
		log::write("Package Include [$identifier] Failed: Package not found.");
		return nil;
	}
}

function library($identifier) {
	crunch($identifier);
	if(file_exists(system::$libraries[$identifier] . '.php')) {
		return system::$libraries[$identifier] . '.php';
	} else {
		log::write("Library Include [$identifier] Failed: Library not found.");
		return nil;
	}
}

function application($identifier) {
	crunch($identifier);
	if(file_exists(system::$applications[$identifier] . 'Resources.php')) {
		return system::$applications[$identifier] . 'Resources.php';
	} else if(file_exists(system::$applications[$identifier])) {
		log::write("Application Resource Include [$identifier] Failed: Application bundle not found.");
		return nil;
	} else {
		log::write("Application Resource Include [$identifier] Failed: Application resource file not found.");
		return nil;
	}
}