<?php # Main System Execution Script [axiixc]

#error_reporting(E_ALL);

# Index check
if(isset($index)) define('root', $index);
else die('<b style="color:red">ERROR:</b> You must operate from the index file.');

# System Package (fixed for some PHP configurations)
include(root . 'Resources/Packages/System/Package.php');
include(root . 'Resources/Packages/Developers/Package.php');

# Include the Library
$required_libraries = array('Dictionary', 'Utilities', 'Cache', 'Registry', 
'System', 'Log', 'Filesystem', 'MySQL', 'Conf', 'Interface', 'UserAuthentication');
foreach($required_libraries as $library) require_once "Library/$library.php";

# Awake Services and Setup
if(Conf::read("Use Cache")) Cache::awake();
Conf::awake();
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
	
	if(file_exists($app_path.'Initialize.php'))
		include $app_path.'Initialize.php';
	
	if(Registry::fetch('UAuth')->verification and file_exists($app_path.'Info.php')) {
		include $app_path.'Info.php';
		if(!is_null($application['name'])) title($application['name']);
	}
	
	if(Registry::fetch('UAuth')->verification and file_exists($app_path.'Resources.php')) {
		include $app_path.'Resources.php';
		$class_name = uncrunch(Registry::fetch('System')->app);
		Registry::register('Application', new $class_name);
	}
	
	if(Registry::fetch('UAuth')->verification)
		require_once $app_path.'Application.php';
	
} else {
	Log::write("Application Load: Application ({$_GET['app']}) could not be found. Bundle invalid.");
	Registry::fetch('Interface')->error("Application Not Found", "Invalid application bundle. <a href=\"ex://System/Home\">click here</a> to go to the home page.");
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

# Allow for last minute log
if(Log::output()) add(Log::read(true));

# User Interface Setup
if(!Registry::fetch("Interface")->direct_echo) include Registry::fetch("Interface")->uinterface();

# Sleep Services
if(Conf::read("Development Mode")) Cache::update();
Conf::sleep();
Cache::sleep();
Log::sleep();