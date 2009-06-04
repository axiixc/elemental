<?php # Registry [axiixc]

class EX {
	
	private static $objects = array();
	private static $sys_vars = array();
	private $System, $Interface, $UAuth;
	
	private function __construct() {}
	
	private function awake() {
		self::$System = new System();
		self::$interface = new UserInterface();
		self::$UAuth = new UserAuthentication();
	}
	
	/* Objects */
	public static function register($identifier, $object) {
		if(is_object($object)) {
			if(!isset(self::$objects[$identifier])) {
				self::$objects[$identifer] = $object;
			} else {
				log::write("Registry Add Object [$identifier] Failed: Supplied Identifier is already taken.");
			}
		} else {
			log::write("Registry Add Object [$identifier] Failed: Supplied Object is a non-object.");
		}
	}
	
	public static function fetch($identifier) {
		if(isset(self::$objects[$identifier])) {
			return self::$objects[$identifier];
		} else {
			log::write("Registry Read Object [$identifier] Failed: No Object for Identifier.");
		}
	}
	
	/* System Global Variables */
	public static function write($key, $value) {
		self::$sys_vars[$key] = $value;
	}
	
	public static function read($key, $show_error=true) {
		if(isset(self::$sys_vars[$key])) {
			return self::$sys_vars[$key];
		} else {
			if($show_error) log::write("Registry Read System Variable [$key] Failed: No Value for Key.");
			return failure;
		}
	}
	
}

?>