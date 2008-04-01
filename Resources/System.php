<?php # Main System Execution Script [axiixc]

# Index check
if(isset($index)) define('root', $index);
else die('Index and Web Root not found.');

# Include the Library
$required_libraries = array('Dictionary', 'Cache', 'Registry', 'System', 'Log', 'BaseUtilities', 'Filesystem', 'MySQL', 'Conf', 'Interface', 'UserAuthentication');
foreach($required_libraries as $library) require_once "Library/$library.php";

# The system resource packages
include package('System');
include package('Developers');

# Cacheing and Preloading [beta]
if(Conf::read("Use Cache")) Cache::awake();
if(Conf::read("Preload Conf"))
	if(Cache::enabled()) Conf::preload(Cache::fetch("Conf"));
	else Conf::preload();

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
	} if(file_exists($app_path.'Resources.php')) include $app_path.'Resources.php';
	require_once $app_path.'Application.php';
} else {
	Log::write("Application Load: Application file could not be found. Bundle invalid.");
	Registry::fetch('Interface')->error("Application Not Found", "The application you tried to access does not exist. Recheck your URL or <a href=\"index.php\">click here</a> to go to the home page.");
}

if(Conf::read("Development Mode")) {
	if($_GET['log'] == true) add('<code>'.Log::read().'</code>');
	if($_GET['get'] == true) add('<code>'.print_r($_GET, true).'</code>');
	if($_GET['diag'] == true) add('<code>'..'</code>')
}

# Log via app
if(Log::output()) add('<div class="log">'.Log::read(true).'</div>');

# Load the UI
if(!Registry::fetch("Interface")->direct_echo) {
	include Registry::fetch("Interface")->uinterface();
} # Else do no UI Management