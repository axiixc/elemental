<?php # Main System Execution Script [axiixc]

# Index check
if(isset($index)) define('root', $index);
else die('<b style="color:red">ERROR:</b> You must operate from the index file.');

# Include the Library
$required_libraries = array('Dictionary', 'Utilities', 'Cache', 'Registry', 'System', 'Log', 'Filesystem', 'MySQL', 'Conf', 'Interface', 'UserAuthentication');
foreach($required_libraries as $library) require_once "Library/$library.php";

# The system resource packages
include package('System');
include package('Developers');

# Awake Services and Setup
if(Conf::read("Use Cache")) Cache::awake();
if(Conf::read("Preload Conf"))
	if(Cache::enabled()) Conf::awake(Cache::fetch("Conf"));
	else Conf::awake();
if(client_ip == '::1') Conf::$conf['www-path'] = 'http://localhost/Elemental/';
$_GET['arg'] = crunch($_GET['arg']);

# Register Objects
Registry::register('System', new System);
Registry::register('UAuth', new UserAuthentication);
Registry::register('Interface', new UserInterface);

# Setup User Authentication
Registry::fetch('UAuth')->awake();

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
	Registry::fetch('Interface')->error("Application Not Found", "The application you tried to access does not exist. Recheck your URL or <a href=\"ex://System/Home\">click here</a> to go to the home page.");
}

# Logs and Diagnostics [ clear : log : mysql : get : reg : cache : conf : ua : ui ]
if(Conf::read("Development Mode")) {
	if(!Registry::fetch('Interface')->override) {
		if($_GET['clear'] == 'true') Registry::fetch('Interface')->content = null;
		if($_GET['log'] == 'true') add(Log::read(true));
		if($_GET['mysql'] == 'true') add(MySQL::diagnostics(true));
		if($_GET['get'] == 'true') add(diagnostic($_GET, true));
		if($_GET['reg'] == 'true') add(Registry::diagnostics(true));
		if($_GET['cache'] == 'true') add(Cache::diagnostics(true));
		if($_GET['conf'] == 'true') add(Conf::diagnostics(true));
		if($_GET['ua'] == 'true') add(Registry::fetch('UAuth')->diagnostics(true));
		if($_GET['ui'] == 'true') add(Registry::fetch('Interface')->diagnostics(true));
	} else {
		if($_GET['clear'] == 'true') Registry::fetch('Interface')->content_override = null;
		if($_GET['log'] == 'true') Registry::fetch('Interface')->content_override .= Log::read(true);
		if($_GET['mysql'] == 'true') Registry::fetch('Interface')->content_override .= MySQL::diagnostics(true);
		if($_GET['get'] == 'true') Registry::fetch('Interface')->content_override .= diagnostic($_GET, true);
		if($_GET['reg'] == 'true') Registry::fetch('Interface')->content_override .= Registry::diagnostics(true);
		if($_GET['cache'] == 'true') Registry::fetch('Interface')->content_override .= Cache::diagnostics(true);
		if($_GET['conf'] == 'true') Registry::fetch('Interface')->content_override .= Conf::diagnostics(true);
		if($_GET['ua'] == 'true') Registry::fetch('Interface')->content_override .= Registry::fetch('UAuth')->diagnostics(true);
		if($_GET['ui'] == 'true') Registry::fetch('Interface')->content_override .= Registry::fetch('Interface')->diagnostics(true);
	}
}

# Allow applications to add log to content at last second
if(Log::output()) add(Log::read(true));

# Load the UI (this used to be 10 lines)
if(!Registry::fetch("Interface")->direct_echo) include Registry::fetch("Interface")->uinterface();

# Sleep Services
Conf::sleep();
Cache::sleep();
Log::sleep();