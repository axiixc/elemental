<?php # User Manager [axiixc]

if($_GET['arg'] == 'login') {
	if(Registry::fetch('UAuth')->guest === true) {
		Registry::fetch('Interface')->display_login();
		Registry::fetch('UAuth')->verification = false;
		Registry::fetch('Interface')->notification(UINotice, "Extended login is currently unsupported.");
		Registry::fetch('UAuth')->test_login();
	} else {
		Registry::fetch('Interface')->error('You are already logged in', 'You must logout first to use the login page.');
		Registry::fetch('UAuth')->verification = false;
	}
	#Registry::fetch('Interface')->notification(UIError, "Login and Registration are disabled");
} if($_GET['arg'] == 'logout') {
	$nvmd = false;
	if(Registry::fetch('UAuth')->guest === false) {
		Registry::fetch('Interface')->notification(UINotice, "You have been logged out.");
		$nvmd = true;
	}
	if(Registry::fetch('UAuth')->guest === true or $nvmd === true) {
		Registry::fetch('Interface')->notification(UINotice, "Extended login is currently unsupported.");
		Registry::fetch('Interface')->display_login();
	} else {
		Registry::fetch('Interface')->error('You are already logged in', 'You must logout first to use the login page.');
	}
} if($_GET['arg'] == 'profile') {
	add(Registry::fetch('Application')->display_profile($_GET['id']));
}