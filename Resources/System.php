<?php # System [ axiixc ] : Framework Glue

# include all required files
$required_libs = array('Dictionary', 'BaseUtilities', 'MySQL', 'Registry', 'System', 'UserAuthentication', 'UserInterface', 'Filesystem');
foreach($required_libs as $lib) require_once sprintf('%s/Resources/Library/%s.php', $index_path, $lib);
include EXPackage('System');

# Init system classes
Registry::register('EX', new Elemental());
Registry::register('UI', new UserInterface());
Registry::register('UA', new UserAuthentication());
Registry::register('DB', new MySQL()); # Hint: Support for non MySQL in the pipe

# Setup App Load Environment
$app_list = FSDirRead(root.'Applications/', true, lower_case_filename);
$app_init = $app_list[Registery::fetch('EX')->app].'Init.php';
$app_ref  = $app_list[Registery::fetch('EX')->app].'Resources.php';
$app_app  = $app_list[Registery::fetch('EX')->app].'Application.php';

# Application Load
if (file_exists($app_init)) include $app_init;
if (Registry::fetch('EX')->load_state == 'login') Registry::fetch('UI')->error("Please Login", EXFetchResource('login_page'));
elseif (Registry::fetch('EX')->load_state == UAApplication) {
	define('SELF', $app_path, true);
	if(file_exists($app_ref)) include $app_ref;
	if(file_exists($app_app)) include $app_app;
	else {
		$UI->Error('Invalid Application', 'That application is not valid. <a href="?app='.$EX->DefaultApp.'">Click Here</a> to go the home page.');
		$UI->LoadOverride();
	}
} else if (Registry::fetch('EX')->load_state == override) $UI->loadOverride();

# User Interface Load
if (!Registry::fetch('UI')->direct_echo) {
	$ui = rsc.'UI/'.$EX->UserInterface.'.ui/';
	
	# Conf and Resources
	if(file_exists($ui.'Conf.php')) include $ui.'Conf.php';
	else include rsc.'UI/System.ui/Conf.php';
	if(file_exists($ui.'Resources.php')) include $ui.'Resources.php';
	
	# Interface
	$interface = $ui.'Interface/'.$UI->Interface;
	if(file_exists($interface)) require_once $interface;
	else require $ui.'Interface/'.$UIConf['default-interface'];
} # Else don't manage the UI