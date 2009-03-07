<?php # User Authentication Library : Initilization

# A lot of this needs to be ported to MySQL, how I'm unsure ATM
# explode() and conf -> Check Again

# Environment Setup
$auth = array(); $auth['session-limit'] = day; $gsession = false; $auth['guest'] = false; $rSeed = uniqid();
$guest_template = array( # Common Info
	'id' => 0, 'name' => 'guest', 'display-name' => 'Guest', 'first-name' => 'Scottsdale', 
	'middle-name' => 'Ann', 'last-name' => 'Prebonic', 'email' => null );
$conf_template = array(); $default_user_conf = null; $default_guest_null = null;

# Setup the Session-ness
if(!is_null($_COOKIE['sess_id'])) { # Attempt to awake old session
	$session_id = EXmySQLSafe($_COOKIE['sess_id']);
	$session_result = EXMySQLQuery("SELECT *  FROM `[prefix]sessions` 
		WHERE `id` = CONVERT(_utf8 '{$sess_id}' USING latin1) COLLATE latin1_swedish_ci");
	$session = mysql_fetch_assoc($session_result);
	
	# Get info on the user
	$user_result = EXMySQLQuery("SELECT * FROM `[prefix]users` 
		WHERE `username` = CONVERT(_utf8 '{$session['user']}' USING latin1) COLLATE latin1_swedish_ci");
	$user = mysql_fetch_assoc($user_result);
	
	# Run a bunch of checks 0.o
	$user   = (mysql_num_rows($user_result)) ? true : false ;
	$guest  = ($session['guest'] == 1) ? true : false ;
	$expire = ($session['expire'] > time()) ? true : false ;
	if($guest) { # Check if is guest
		$cookie = ($_COOKIE['sess_verify'] == md5($session['key'].client_ip)) ? true : false ;
		$ban   = (!in_array($session['user'], explode('#', EXMySQLQuery('banned-ips')))) ? true : false ;
	} else { # Check if not guest
		$cookie = ($_COOKIE['sess_verify'] == md5($session['key'].$user['username'].$user['password'])) ? true : false ;
		$ban   = ($user['type'] == UATypeBan) ? true : false ;
	}
	
	# Validate against check templates (more cases can be added)
	if($user and !$guest and $expire and $cookie and !$ban) { # Reload a registered user
		
		$auth['conf'] = explode(MLDF1, explode(MLDF2, $session['conf']));
		$auth['uconf'] = explode(MLDF1, explode(MLDF2, $user['conf']));
		$auth['user'] = array( # Common Info
			'id' => $user['id'], 'name' => $user['username'], 'display-name' => $user['display-name'], 'first-name' => $user['fname'],
			'middle-name' => $user['mname'], 'last-name' => $user['lname'], 'email' => $user['email'] );
		$auth['role'] = $user['role'];
		$auth['type'] = $user['type'];
		$auth['session'] = $session['id'];
		$auth['guest'] = false;
		$auth['action'] = 'Reload';
		$gsession = false;
		
	} elseif(!$user and $guest and $expire and $cookie and !$ban) { # Reload a guest user	
		
		$auth['uconf'] = $auth['conf'] = explode(MLDF1, explode(MLDF2, $session['conf']));
		$auth['user'] = $guest_template;
		$auth['role'] = UATypeGuest;
		$auth['type'] = UATypeGuest;
		$auth['session'] = $session['id'];
		$auth['guest'] = true;
		$auth['action'] = 'GReload';
		$gsession = false;
		
	} else { # Destroy the current session and make a new one
		
		if(mysql_num_rows($session_result) > 0)
			EXMySQLQuery("DELETE FROM `[prefix]sessions` 
			WHERE CONVERT(`[prefix]sessions`.`id` USING utf8) = '{$sess_id}' LIMIT 1");
		setcookie('sess_id', null, destroy);
		setcookie('sess_user', null, destroy);
		$gsession = true;
		$auth['action'] = 'Destroy';
		
	}
} else { $gsession = true; $auth['action'] = 'CookieVoid'; }

# From here we play god (and create sessions)
if(isset($_POST['UAU']) and isset($_POST['UAP'])) { # Login from a form
	
	$username = EXMySQLSafe($_POST['UAU']); 
	$password = md5($_POST['UAP']);
	$user = mysql_fetch_assoc(EXMySQLQuery("SELECT * FROM `[prefix]users` 
		WHERE `username` = CONVERT(_utf8 '{$username}' USING latin1) COLLATE latin1_swedish_ci"));
	
	if($password == $user['password']) { # Verified! 
	
		$sess_key = md5($rSeed);
		$sess_id = md5($sess_key);
		$sess_user = $user['username'];
		$sess_conf = $conf_template;
		$sess_guest = 0;
		$sess_verify = md5($sess_key.$sess_user.$user['password']);

		$auth['conf'] = $sess_conf;
		$auth['uconf'] = explode(MLDF1, explode(MLDF2, $user['conf']));
		$auth['user'] = array( # Common Info
			'id' => $user['id'], 'name' => $user['username'], 'display-name' => $user['display-name'], 'first-name' => $user['fname'],
			'middle-name' => $user['mname'], 'last-name' => $user['lname'], 'email' => $user['email'] );
		$auth['role'] = $user['role'];
		$auth['type'] = $user['type'];
		$auth['session'] = $sess_id;
		$auth['guest'] = $sess_guest;
		$auth['mode'] = 'New';

		setcookie('sess_id', $sess_id, $auth['session-limit']);
		setcookie('sess_verify', $sess_verify, $auth['session-limit']);
		EXMySQLQuery("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`) 
			VALUES ('$sess_id', '$sess_key', '$sess_user', '$default_user_conf', '{$auth['session-limit']}', $sess_guest);");
	
	} else $gsession = true;
	
} if($gsession) { # Render from guest template
	
	$sess_key = md5($rSeed);
	$sess_id = md5($sess_key);
	$sess_user = $user_template['username'];
	$sess_conf = $conf_template;
	$sess_guest = 1;
	$sess_verify = md5($sess_key.client_ip);

	$auth['conf'] = $auth['uconf'] = $sess_conf;
	$auth['user'] = $user_template;
	$auth['role'] = UATypeGuest;
	$auth['type'] = UATypeGuest;
	$auth['session'] = $sess_id;
	$auth['guest'] = $sess_guest;
	$auth['mode'] = 'GNew';

	setcookie('sess_id', $sess_id, $auth['session-limit']);
	setcookie('sess_verify', $sess_verify, $auth['session-limit']);
	EXMySQLQuery("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`) 
		VALUES ('$sess_id', '$sess_key', '$sess_user', '$default_guest_conf', '{$auth['session-limit']}', $sess_guest);");

} # print_r($session); print_r($auth); # For debug