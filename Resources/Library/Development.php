<?php

class Development
{
	
	private static $log = array(), $debug = array(), $active = array();
	
	private function __construct() {}
	
	public static function logWrite($string)
	{
		self::$log[] = array((mTimeGet() - $starttime), $string);
	}
	
	public static function logDump()
	{
		$template = template('Log Message', '[%1$0.3f] %2$s'."\n");
		$base_template = template('Log Base', '<pre class="log">'."\n".'%s</pre>');
		
		foreach(self::$log as $message)
		{
			$output_temp .= sprintf($template, $message[0], $message[1]);
		}
		return sprintf($base_template, $output_temp);
	}
	
	public static function logAdd()
	{
		add(self::logDump());
	}
	
	public static function debugWrite($name, $data)
	{
		crunch($name);
		self::$debug[$name] = $data;
	}
	
	public static function debugActivate($name)
	{
		crunch($name);
		self::$activate[] = $name;
	}
	
	public static function debugAdd()
	{
		foreach($active as $item)
		{
			add(self::debugFormat($debug[$item]));
		}
	}
	
	private static function debugFormat($array)
	{
		$template = template('Debug Item', '[%1$s] %2$s');
		$base_template = template('Debug Base', '<pre class="debug">%s</pre>');
		foreach($array as $name => $info)
		{
			$output_temp .= sprintf($template, $name, $info);
		}
		return sprintf($base_template, $output_temp);
	}
	
}

/* Plain Function Accessors (special case name convention) */

function exLog()
{
	$args = func_get_args();
	$string = array_shift($args);
	Development::logWrite(vsprintf($string, $args));
}

function exMethod()
{
   if (exMethod)
   {
      $string = array_shift(func_get_args());
      $string = '> ' . $string;
      Development::logWrite(vsprintf($string, $args));
   }   
}

function debug($name, $data)
{
	Development::debugWrite($name, $data);
}