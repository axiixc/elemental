<?php # Registery [ axiixc : mrmonday ] : Manages everything

final class Registry {
    
	static private $objects = array();
    
	private function __construct() {}
    
	static public function register($name, $object) { 
		if(is_object($object)) {
			if(!isset(self::$registeredObjects[$name])) self::$objects[$name] = $object;
			else self::fetch('UI')->notificationAdd(UIDevMsg, sprintf("Registry::register() could not add %s. [Already exists]", $name));
		} else self::fetch('UI')->notificationAdd(UIDevMsg, sprintf("Registry::register() could not add %s. [Not an object]", $name));
    }

	static public function fetch($name) {
		if(isset(self::$objects[$name])) return self::$objects[$name];
		else self::fetch('UI')->notificationAdd(UIDevMsg, sprintf("Registry::register() could not fetch %s. [Not found]", $name));
	}		

}