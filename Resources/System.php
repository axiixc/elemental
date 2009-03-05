<?php # System : Constructor
# error_reporting( E_ALL | E_STRICT );
# Set up some nice vars
$system = array();

# Include the base libraries
require EXScriptDir() . 'Resources/Library/Dictionary.php';
$lib = array('ErrorCodes', 'MySQL', 'Utilities', 'Filesystem', 'EXConf',
	'EXSystem', 'EXBundles', 'UIContent', 'UIInterface', 'UIMenus', 'UIJavascript', 
	'UIMeta', 'UINotifications', 'UISidebar', 'UAActions', 'UAChecks');
foreach($lib as $library) require lib.$library.'.php';

# Load the request by $_GET request type
if($_GET['reqType'] == 'direct') die(EXError(0));
elseif($_GET['reqType'] == 'hidden') die(EXError(0));
else { # Traditional Application request
	# Set up the environment vars
	$app_id   = EXSystemApp();
	$app_list = FSDirRead(root.'Applications/', true, lower_case_filename);
	$app_path = $app_list[$app_id];
	$app_init = $app_path . 'Init.php';
	$app_ref  = $app_path . 'Reference.php';
	$app_app  = $app_path . 'Application.php';
	$checks['init'] = (file_exists($app_init)) ? true : false ;
	$checks['ref']  = (file_exists($app_ref))  ? true : false ;
	$checks['app']  = (file_exists($app_app))  ? true : false ;
	# print_r($checks);
	# Run the initialization and do UA checks
	if($checks['init']) require $app_init;
	if(UALoadState() == UALogin) include(EXLibrary('UALoginPage'));
	elseif(UALoadState() == UAApplication) {
		if($checks['ref']) require $app_ref;
		if($checks['app']) require $app_app;
		else UIError("Invalid Application", 'That application is not valid. <a href="?app='.EXConfRead('default-app').'">Click here</a> return home.');
	} elseif(UALoadState() == override) {
		$system['UI']['content'] = $system['override']['content'];
		$system['UI']['sidebars'] = $system['override']['sidebars'];
	} # Now to the UI
	
	if(!UIDirectEcho()) {
		$ui = rsc.'UI/'.EXSystemUI().'.ui/';
		if(file_exists($ui.'Conf.php')) require $ui.'Conf.php';
		else { $ui = rsc.'UI/System.ui/'; require $ui.'Conf.php'; }
		$interface = $ui.'Interface/' . UIInterface();
		if(file_exists($interface)) require $interface;
		else require $ui.'Interface/' . UIDefaultInterface();
	} else { EXLog('Mannaged UI'); } # Else do no UI management
} # And done :P