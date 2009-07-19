<?php # System Class and Main Handler [axiixc]

class System
{

	public static $application, $arg, $environment;
	private static $_database, $_configuration, $_interface, $_authority, $_user;
	
	private function __construct() {}
	
	public static function Initialize()
	{
		/* Setup Registry */
		self::$_database = new Database();
		self::$_configuration = new Configuration();
		self::$_interface = new InterfaceHandler();
		self::$_authority = new Authority();
		self::$_user = new User();
		
		/* Set base values and cleanup */
		crunch($_GET['app']);
		crunch($_GET['arg']);
		
		self::$application = priority_select($_GET['app'], cfRead('Default Application'), 'System');
		exMethod('System: _application = ' . self::$application);
		self::$arg = $_GET['arg'];
	}
	
	public function Application()
	{
	   return self::$application;
   }
	
	/* Accessors */
	public static function Database()
	{
		return self::$_database;
	}
	
	public static function Configuration()
	{
		return self::$_configuration;
	}
	
	public static function InterfaceHandler()
	{
		return self::$_interface;
	}
	
	public static function Authority()
	{
		return self::$_authority;
	}
	
	public static function User()
	{
		return self::$_user;
	}
	
	/* Environment */
	public static function Read($name)
	{
		crunch($name);
		return self::$environment[$name];
	}
	
	public static function Write($name, $value)
	{
		crunch($name);
		self::$environment[$name] = $value;
	}
	
	public static function Delete($name)
	{
		crunch($name);
		unset(self::$environment[$name]);
	}
	
	public static function Set($name)
	{
		crunch($name);
		return (isset(self::$environment[$name]));
	}
	
	public static function Terminate()
	{
		
	}
	
}

/* Plain Function Accessors (normal name convention) */

function sysRead($key)
{
	return System::Read($key);
}

function sysWrite($key, $value)
{
	System::Write($key, $value);
}

function sysDelete($key)
{
	System::Delete($key);
}

function sysSet($key)
{
	return System::Set($key);
}