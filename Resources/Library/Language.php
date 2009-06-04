<?php # Localization Class [axiixc]

class Language {
	
	public static $valid_types = array();
	protected static $localizations = array();
	
	public static function __construct($types) {
		$valid_types = conf::read('System Localization');
	}
	
	public static function add($type, $localization) {
		crunch($type);
		if(in_array($type, $this->valid_types)) {
			if(is_array($localization)) {
				crunch_keys($localization);
				$this->localizations = array_merge($this->localizations, $localization);
			} else {
				log::write("Language Add Localization Failed: Localization must be an array.");
			}
		}
	}
	
	public static function read($identifier) {
		crunch($identifier);
		if(isset($this->localizations[$identifier])) {
			return $this->localizations[$identifier];
		} else {
			log::write("Language Read Localization [$identifier] Failed: Identifier not found.")
		}
	}
	
	public static function debug() {
		$debug['Types'] = '[ '.implode(' : ', $this->valid_types).' ]';
		foreach($this->localizations as $identifier => $value) {
			$debug['Strings'] .= "[$identifier] = '$value']\n";
		}
		
		debug::register('Language', $debug);
	}
	
}