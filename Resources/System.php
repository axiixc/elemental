<?php # System File [axiixc]

/* Determine Valid Run */
if(isset($index)) define('root', $index, true);
else die('<b style="color:red">ERROR:</b> You must operate from the index file.');

/* Package Include Fix */
include root . 'Resources/Packages/System_Resources/Package.php';
include root . 'Resources/Packages/Developer_Signatures/Package.php';

/* Include Base Libraries [ included in order ] */
$required_libraries = array('Dictionary', 'Language', 'Log', 'Debug', 'Utilities', 'Cache', 
'Registry', 'System', 'Filesystem', 'MySQL', 'Configuration', 'User_Interface', 'User_Authentication');
foreach($required_library as $library) require_once "Library/$library.php";

/* PHP Version Check */
if(version_compare(PHP_VERSION, __ex_php_version))
	trigger_error('PHP Version is below required limits. Your version:'.PHP_VERSION.' Required:'.__ex_php_version.'.', E_USER_ERROR);

/* Awake Services */
lang::awake();
cache::awake();
mysql::awake();
conf::awake();
system::awake();
ui::awake();
auth::awake();

/* Application Loading *//* User Authentication */
$app_list = dir_read(root . 'Applications', true, crunch);
$app_path = $app_list[system::application];
if(file_exists($app_path.'Application.php')) {
	# User Authentication Rules
	if(file_exists($app_path.'Initialize.php'))
		include $app_path.'Initialize.php';
	else define('isVerified', true, true);
	# Only run if passes UAuth Verification
	if(isVerified) {
		# Check and load Language(s)
		foreach(lang::$valid_types as $valid_language)
			if(file_exists($app_path . 'Localizations/' . $valid_language . '.php'))
			include file_exists($app_path . 'Localizations/' . $valid_language . '.php';
		# Check and load Info file ( and set title )
		if(file_exists($app_path . 'Info.php')) {
			include $app_path . 'Info.php';
			if(!is_null($application['name'])) title($application['name']);
		}
		# Check and load Resources
		if(file_exists($app_path . 'Resources.php')) {
			include $app_path . 'Resources.php';
			EX::registerApplication(new (EX::$System->application));
		}
		# Load the base application
		require_once $app_path . 'Application.php';
	} else {
		log::write("Application Load Failed: User Authentication Verification Failed.");
	}
} else {
	log::write("Application Load Failed: Application file could not be found. Bundle Invalid.");
	error('Application Not Found', 'Invalid application bundle. <a href="'.parse_link('ex://System/Home').'">click here</a> to go to the home page.');
}

/* Allow for log/debug commits */
if(log::$output) add(log::read());
if(debug::$output) add(debug::read());

/* Interface */
if(!EX::$Interface->direct_echo) include EX::$Interface->uinterface();

/* Sleep Services [ in reverse order ] */
auth::sleep();
ui::sleep();
conf::sleep();
cache::sleep();