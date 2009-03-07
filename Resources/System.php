<?php # System : Constructor

# Include the base libraries
$system = array(); # This is a nice var
require EXScriptDir() . 'Resources/Library/Dictionary.php';
$lib = array('ErrorCodes', 'MySQL', 'Utilities', 'Filesystem', 'EXConf', 'EXSystem', 'EXBundles', 'UIContent', 
	'UIInterface', 'UIMenus', 'UIJavascript', 'UIMeta', 'UINotifications', 'UISidebar', 'UAActions', 'UAChecks');
foreach($lib as $library) require lib.$library.'.php';

# Load the request by $_GET request type
if($_GET['reqType'] == 'direct') die(EXError(0));
elseif($_GET['reqType'] == 'hidden') die(EXError(0));
else { # Traditional Application request
	
	# Set up the load vars
	$app_id   = EXApp();
	$app_list = FSDirRead(root.'Applications/', true, lower_case_filename);
	$app_path = $app_list[$app_id];
	$app_init = $app_path.'Init.php';
	$app_ref  = $app_path.'Resources.php';
	$app_app  = $app_path.'Application.php';
	
	# Run the initialization and do UA checks
	if($checks['init']) require $app_init;
	if(UALoadState() == UALogin) include(EXLibrary('UALoginPage'));
	elseif(UALoadState() == UAApplication) {
		define('SELF', $app_path, true);
		if(file_exists($app_ref)) require $app_ref;
		require $app_app;
		if(true) null; else UIError("Invalid Application", 'That application is not valid. <a href="?app='.EXDefaultApp().'">Click here</a> return home.');
	} elseif(UALoadState() == override) {
		$system['UI']['content'] = $system['override']['content'];
		$system['UI']['sidebars'] = $system['override']['sidebars'];
	} # Now to the UI
	
	if(!UIDirectEcho()) {
		$ui = rsc.'UI/'.EXSystemUI().'.ui/';
		if(file_exists($ui.'Conf.php')) require $ui.'Conf.php';
		else { $ui = rsc.'UI/System.ui/'; require $ui.'Conf.php'; }
		$interface = $ui.'Interface/' . UIInterface();
		define('UI', 'Resources/UI/'.EXSystemUI().'.ui/', true);
		if(file_exists($ui.'Resources.php')) include $ui.'Resources.php';
		if(file_exists($interface)) require $interface;
		else require $ui.'Interface/' . UIDefaultInterface();
	} # Else do no UI management
} # And done :P