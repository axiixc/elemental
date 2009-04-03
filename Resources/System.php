<?php # Main System Execution Script [axiixc]

# Index check
if(isset($index)) define('root', $index);
else die('<b style="color:red">ERROR:</b> You must operate from the index file.');

# Include the Library
$required_libraries = array('Dictionary', 'BaseUtilities', 'Cache', 'Registry', 'System', 'Log', 'Filesystem', 'MySQL', 'Conf', 'Interface', 'UserAuthentication');
foreach($required_libraries as $library) require_once "Library/$library.php";

# The system resource packages
include package('System');
include package('Developers');

# Aweake Services
if(Conf::read("Use Cache")) Cache::awake();
if(Conf::read("Preload Conf"))
	if(Cache::enabled()) Conf::awake(Cache::fetch("Conf"));
	else Conf::awake();
if($_COOKIE['fix_urls'] == true) Conf::$conf['www-path'] = 'http://axiixcdev.co.cc:8008/Elemental/';

# Register Objects
Registry::register('System', new System);
Registry::register('UAuth', new UserAuthentication);
Registry::register('Interface', new UserInterface);

# Setup User Authentication
Registry::fetch('UAuth')->awake();
if(Conf::read("Development Mode")) Registry::fetch('UAuth')->log_msgs();

# Load application
$app_list = FSDirRead(root.'Applications', true, lower_case_filename);
$app_path = $app_list[strtolower(Registry::fetch('System')->app)];
if(file_exists($app_path.'Application.php')) {
	# Initilization Code
	if(file_exists($app_path.'Initialize.php')) {
		include $app_path.'Initialize.php';
		# Do UAuth finalization here
	} if(file_exists($app_path.'Resources.php')) {
		include $app_path.'Resources.php';
		$class_name = uncrunch(Registry::fetch('System')->app);
		Registry::register('Application', new $class_name);
	} require_once $app_path.'Application.php';
} else {
	Log::write("Application Load: Application file could not be found. Bundle invalid.");
	Registry::fetch('Interface')->error("Application Not Found", "The application you tried to access does not exist. Recheck your URL or <a href=\"index.php\">click here</a> to go to the home page.");
}

# Sleep Services
Conf::sleep();
Cache::sleep();

# Logs and Diagnostics
if(Conf::read("Development Mode")) {
	if($_GET['log'] == 'true') add('<pre class="code">'.Log::read(true).'</pre>');
	if($_GET['get'] == 'true') add('<pre class="code">'.print_r($_GET, true).'</pre>');
	if($_GET['reg'] == 'true') add('<pre class="code">'.Registry::diagnostics(true).'</pre>');
	if($_GET['ua'] == 'true') add('<pre class="code">'.Registry::fetch('UAuth')->diagnostics(true).'</pre>');
	if($_GET['ui'] == 'true') add('<pre class="code">'.Registry::fetch('Interface')->diagnostics(true).'</pre>');
}

# Allow applications to write log at last second
if(Log::output()) add('<pre class="code">'.Log::read(true).'</pre>');

# Load the UI
if(!Registry::fetch("Interface")->direct_echo) {
	include Registry::fetch("Interface")->uinterface();
} # Else do no UI Management