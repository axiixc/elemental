<?php # User Manager Initilization [axiixc]

if($_GET['arg'] == 'logout') {
	setcookie('sess_verify', null, destroy, '/');
	setcookie('sess_id', null, destroy, '/');
	$nvmd = false;
	if(Registry::fetch('UAuth')->guest === false) {
		Registry::fetch('Interface')->notification(UINotice, "You have been logged out.");
		$nvmd = true;
	}
	Registry::fetch('UAuth')->create_session();
	Registry::fetch('UAuth')->login = false;
}

$guest_actions = array('login', 'logout', 'register', 'lost-password');
if(!Conf::read("Require Auth for Profiles")) $guest_actions[] = 'profile';

if(!in_array($_GET['arg'], $guest_actions)) {
	# Deny any guests
	Registry::fetch('UAuth')->require_type(UATypeBasic);
}