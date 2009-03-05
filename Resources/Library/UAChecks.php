<?php # User Authentication Library : Checks and Actions

# Environment Vars
$auth['environment']['baseRole'] = 'name'; // Acceptable values: name, id
$auth['environment']['loadState'] = UAApplication; // application, login, override
$auth['override']['application'] = EXConfRead('default-application');
$auth['override']['content'] = null;
$auth['verification'] = true; // bool

function UARequireType() {
	global $auth; $types = func_get_args(); $switch = false;
	if(UAVerification() != false) {
		foreach($types as $type) if($type == $auth['type']) $switch = true;
		if($switch) { 
			$auth['environment']['loadState'] = 'application'; 
			UAVerification(true); 
		} elseif ($auth['type'] == UATypeGuest) { 
			$auth['environment']['loadState'] = 'login'; UAVerification(false);
			UINotificationAdd(UIError, 'You must login to use this page.');
		} else {
			$auth['environment']['loadState'] = 'override'; UAVerification(false);
			UINotificationAdd(UIError, 'You do not have full access rights to use this page.');
		} return $switch;
	} else return false;
}

function UARequireRole() {
	global $auth; $roles = func_get_args(); $switch = false;
	if(UAVerification() != false) {
		foreach($roles as $role) if($role == $auth['role']['name'] or $role == $auth['role']['id']) $switch = true;
		if($switch) { 
			$auth['environment']['loadState'] = 'application'; 
			UAVerification(true); 
		} elseif(!$switch and $auth['type'] != UATypeGuest) { 
			$auth['environment']['loadState'] = 'override'; UAVerification(false);
			UINotificationAdd(UIError, 'You do not have sufficent access rights to view this page.');
		} elseif(!$switch and $auth['type'] == UATypeGuest) {
			$auth['environment']['loadState'] = 'login'; UAVerification(false);
			UINotificationAdd(UIError, 'You must login to use this page.');
		} return $switch;
	} else return false;
}

function UAVerification($set=null) {
	global $auth;
	if(is_null($set)) return $auth['verification'];
	else $auth['verification'] = $set;
}

function UAType() {
	global $auth; $types = func_get_args();
	if(count($types) == 0) return $auth['type'];
	else { $switch = false;
		foreach($types as $type) if($type == $auth['type']) $switch = true;
		return $switch;
	}
}

function UARole() {
	global $auth; $roles = func_get_args();
	if(count($roles) == 0) return $auth[$auth['environment']['baseRole']];
	else { $switch = false;
		foreach($roles as $role) if($role == $auth['role']['name'] or $role == $auth['role']['id']) $switch = true;
		return $switch;
	}
}

function UARoleWithName() {
	global $auth; $roles = func_get_args();
	if(count($roles) == 0) return $auth['name'];
	else { $switch = false;
		foreach($roles as $role) if($role == $auth['role']['name'] or $role == $auth['role']['id']) $switch = true;
		return $switch;
	}
}

function UARoleWithID() {
	global $auth; $roles = func_get_args();
	if(count($roles) == 0) return $auth['id'];
	else { $switch = false;
		foreach($roles as $role) if($role == $auth['role']['name'] or $role == $auth['role']['id']) $switch = true;
		return $switch;
	}
}

function UALoadState($switch=null) {
	global $auth;
	if(is_null($switch)) return $auth['environment']['loadState'];
	elseif($switch == UALogin) { $auth['environment']['loadState'] = UALogin; return true; }
	elseif($switch == UAApplication) { $auth['environment']['loadState'] = UAApplication; return true; }
	else return false;
}

function UAKillNow($reason) { # Uber STOP method, Not recommended
	echo 'This session was forcefully killed.';
	if(!is_null($reason)) echo ' Reason: ' . $reason;
	die();
}

function UAGuest() { global $auth; return $auth['guest']; }