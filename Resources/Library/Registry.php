<?php # Registery [ axiixc : mrmonday ] : Manages everything

final class Registry {
    
	static private $objects = array();
    
	private function __construct() {}
    
	static public function register($name, $object) { 
		if(is_object($object)) {
			if(!isset(self::$registeredObjects[$name])) self::$objects[$name] = $object;
			else return false;
		} else return false;
    }

	static public function fetch($name) {
		if(isset(self::$objects[$name])) return self::$objects[$name];
		else return false;
	}		

}