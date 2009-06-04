<?php # Debug Output [axiixc]

class debug {
	
	private static $items;
	private static $identifiers;
	
	private function __construct() {}
	
	public static function register($identifier, $content, $force = false) {
		crunch($identifier);
		self::$items[$identifier] = $content;
		if($force) self::$identifiers[] = $identifier;
	}
	
	public static function force($identifier) {
		array_merge(self::$identifiers, array($identifier));
	}
	
	public static function trigger() {
		crunch($_GET['debug']);
		$show = array_merge(self::$identifiers, explode(':', $_GET['debug']));
		
		foreach($show as $identifier) :(self::format(self::$items[$identifier]));
	}
	
	protected static function format($identifier, $array) {
		$template_null = template('Diagnostic NULL', '<span style="color:yellow">NULL</span>');
		$template_true = template('Diagnostic TRUE', '<span style="color:green">TRUE</span>');
		$template_false = template('Diagnostic FALSE', '<span style="color:red">FALSE</span>');
		$template_item = template('Diagnostic Item', '<span style="color:#2C68C1;">[%s]</span>&nbsp;%s<br />');
		$template = template('Diagnostic', '<pre class="log"><h1>%1$s</h1>%2$s</pre>');
		
		foreach($array as $name => $value) {
			if(is_null($value)) $value = 'NULL';
			if($value === true) $value = 'TRUE';
			if($value === false) $value = 'FALSE';
			$value = str_replace('NULL', $template_null, $value);
			$value = str_replace('TRUE', $template_true, $value);
			$value = str_replace('FALSE', $template_false, $value);
			
			$x .= sprintf($template_item, uncrunch($name, false), $value);
		}
		
		return sprintf($template, uncrunch($identifier, false), $x);
	}
	
}