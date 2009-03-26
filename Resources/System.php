<?php # Main System Execution Script [axiixc]

# Index check
if(isset($index)) define('root', $index);
else die('Index not found.');

# Include the Library
include 'Library/Dictionary.php';
$required_libraries = array('Registry', 'System', 'Log', 'BaseUtilities', 'Filesystem', 'MySQL', 'Conf', 'Interface', 'UserAuthentication');
foreach($required_libraries as $library) require "Library/$library.php";

# Preload the Configuration Table (BETA)
Conf::preload();

# The system resource package
include package('System');

# Register Objects
Registry::register('System', new System);
Registry::register('UAuth', new UserAuthentication);
Registry::register('Interface', new UserInterface);

# Setup User Authentication
Registry::fetch('UAuth')->awake();

# Tests
Registry::fetch('UAuth')->integrity();
Registry::fetch('UAuth')->log_msgs();
Log::write('Application: '.Registry::fetch('System')->app);
Log::write('User Interface: '.Registry::fetch('System')->ui);

# Load application
$app_list = FSDirRead(root.'Applications', true, lower_case_filename);
$app_path = $app_list[strtolower(Registry::fetch('System')->app)];
if(file_exists($app_path.'Application.php')) {
	if(file_exists($app_path.'Initialize.php')) {
		include $app_path.'Initialize.php';
		
	}
}

echo '<html><head><title>Woot</title></head><body style="background:black;color:white;">';
Log::read();
echo '<hr />';
Conf::postload();
echo '<hr />';
Registry::fetch("Interface")->interface_diagnostics();
echo '</body></html>';