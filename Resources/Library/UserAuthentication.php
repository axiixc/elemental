<?php

class UserAuthentication {
	
	public $conf, $uconf, $user, $role, $type, $guest;
	private $session, $action, $mode, $limit, $application, $content, $verification, $roles;
	
	public function __construct() {
		$this->roles = import("User Roles");
		$this->limit = Conf::read("User Authentication Session Limit");
		$this->action = 'Not Run';
		$this->mode = 'Not Run';
	}
	
	public function awake() {
		if(!is_null($_COOKIE['sess_id'])) {
			$session_id = mysql_safe($_COOKIE['sess_id']);
			$session_result = MySQL::query("SELECT * FROM `[prefix]sessions` WHERE `id` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $session_id);
			$session = mysql_fetch_assoc($session_result);
			$user_result = MySQL::query("SELECT * FROM `[prefix]users` WHERE `username` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $session['user']);
			$user = mysql_fetch_assoc($user_result);
			
			# Run checks [ can this be condensed ? ]
			$user = (mysql_num_rows($user_result)) ? true : false ;
			$guest = ($session['guest'] == 1) ? true : false ;
			$expire = ($session['expire'] > time()) ? true : false ;
			if($guest) {
				$cookie = $this->validate_keys($session['key'], $_COOKIE['sess_id'], $_COOKIE['sess_verify'], null, null);
				$cookie = ($_COOKIE['sess_verify'] == md5($session['key'].client_ip)) ? true : false ;
				$ban = (in_array($session['user'], import('Banned IPs'))) ? true : false ;
			} else {
				$cookie = $this->validate_keys($session['key'], $_COOKIE['sess_id'], $_COOKIE['sess_verify'], $user['usename'], $user['password']);
				$ban = ($user['type'] == UATypeBan) ? true : false ;
			}
			
			if($user and !$guest and $expire and $cookie and !$ban) { # Registered User
				$this->action = 'Reload';
				$this->load_session($user, $session);
			} else if(!$user and $guest and $expire and $cookie and !$ban) { # Guest
				$this->action = 'Reload Guest';
				$this->load_session('guest', $session);
			} else { # Destroy and create anew
				if(mysql_num_rows($session_result) > 0) MySQL::query("DELETE FROM `[prefix]sessions` WHERE CONVERT(`[prefix]sessions`.`id` USING utf8) = '%s' LIMIT 1", $sess_id);
				setcookie('sess_id', null, destroy);
				setcookie('sess_verify', null, destroy);
				$this->action = 'Destroy';
				$this->create_session();
			}
		} else {
			$this->action = 'No Cookie';
			$this->create_session();
		}
	}
	
	private function load_session($user, $session) {
		if($user == 'guest') {
			$this->conf = unfold($session['conf']);
			$this->uconf = array();
			$this->user = import('Guest User');
			$this->role = 'guest';
			$this->type = UATypeGuest;
			$this->session = $session['id'];
			$this->guest = true;
			$this->action = 'Guest Reload';
		} else {
			$this->conf = unfold($session['conf']);
			$this->uconf = unfold($user['conf']);
			$this->user = array(
				'id' => $user['id'], 
				'name' => $user['username'], 
				'display-name' => $user['display-name'], 
				'first-name' => $user['fname'],
				'middle-name' => $user['mname'], 
				'last-name' => $user['lname'], 
				'email' => $user['email'] );
			$this->role = $user['role'];
			$this->type = $user['type'];
			$this->session = $session['id'];
			$this->guest = false;
			$this->action = 'Reload';
		}
	}
	
	private function create_session() {
		$limit = constant($this->limit);
		
		if(isset($_POST['UAU']) and isset($_POST['UAP'])) {
		/* REGISTERED SESSION CREATION */
		
			# Generate Validation Info
			$username = mysql_safe($_POST['UAU']);
			$password = md5($_POST['UAP']);
			$user = mysql_fetch_assoc(MySQL::query("SELECT * FROM `[prefix]users` WHERE `username` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $username));
			
			// Reload
			if($password == $user['password']) {
				
				$session = $this->generate_keys(uniqid(), $user['username'], $password);
				$this->conf = import("Default User Conf");
				$this->uconf = unfold($user['conf']);
				$this->user = array(
					'id' => $user['id'], 
					'name' => $user['username'], 
					'display-name' => $user['display-name'], 
					'first-name' => $user['fname'],
					'middle-name' => $user['mname'], 
					'last-name' => $user['lname'], 
					'email' => $user['email'] );
				$this->role = $user['role'];
				$this->type = $user['type'];
				$this->session = $session['id'];
				$this->mode = 'New';
				
				setcookie('sess_id', $session['id'], $limit);
				setcookie('sess_verify', $session['verify'], $limit);
				MySQL::query("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`) VALUES ('%s', '%s', '%s', '%s', '%s', %s);", $session['id'], $session['key'], $user['username'], $this->conf, $limit, '0');
				
			} //--> END RELOAD
			
		} else {
		/* GUEST SESSION CREATION */
			$session = $this->generate_keys(uniqid(), null, null);
			$this->conf = import("Guest Conf");
			$this->uconf = array();
			$this->user = import("Guest User");
			$this->role = 'guest';
			$this->type = UATypeGuest;
			$this->session = $session['id'];
			$this->mode = 'New Guest';
			
			setcookie('sess_id', $session['id'], $limit);
			setcookie('sess_verify', $session['verify'], $limit);
			MySQL::query("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`) VALUES ('%s', '%s', '%s', '%s', '%s', %s);", $session['id'], $session['key'], client_ip, $this->conf, $limit, '1');
		}
		
	}
	
	private function generate_keys($auth_token, $username, $password) {
		$sess['key'] = md5(uniqid());
		$sess['id'] = md5($sess['key']);
		$sess['verify'] = md5($sess['key'].$username.$password.client_ip);
		return $sess;
	}
	
	private function validate_keys($key, $rSess_id, $rSess_verify, $username, $password) {
		$sess['id'] = md5($key);
		$sess['verify'] = md5($key.$username.$password.client_ip);
		if($sess['id'] == $rSess_id and $sess['verify'] == $rSess_verify) return true;
		else return false;
	}
	
	public function integrity() {
		$gen = $this->generate_keys(uniqid(), foo, bar);
		if($this->validate_keys($gen['key'], $gen['id'], $gen['verify'], foo, bar)) Log::write('User Authentication Session Integrity: Pass');
		else Log::write('User Authentication Session Integrity: Fail');
	}
	
	public function log_msgs() {
		Log::write("User Authentication Action: $this->action");
		Log::write("User Authentication Mode: $this->mode");
	}
	
	public function requireType() {
		if($this->verification != false) {
			$types = func_get_args();
			foreach($types as $type) $switch = ($type == $this->type) ? true : false ;
			if($switch) {
				$this->load_state = 'application';
				$this->verification = true;
			} elseif($type == UATypeGuest) {
				$this->load_state = 'login';
				$this->verification = false;
				
				#YOU FELL ASLEEP HERE
				#TODO fix from here down
				#UI LIBRARY
				Registry::fetch('Interface')->notificationAdd(UIError, "You must login to access this page");
				// Condense the notificationAdd to just notification
				// notification(); will return array of all notifications
				// notification(UIError); will return array of all UIError notifications
				// notification(UINotification, true); will echo as <div class="UIError/UINotice">Msg</div>
				// notification(UINotice, false); will return count of UINotice
				// notification(UIError, "Hello"); well add a UIError
				// Special Syntax: [[APPLICATION]] - Default Home Application
				// Special Syntax: [[LOGIN]] Login window
				// Special Syntax: [[]]
			} else {
				Registry::fetch('EX')->load_state = 'override';
				$this->verification = false;
				Registry::fetch('UI')->error("Permission Denied", "You do no have access rights for this page.");
			} return $switch;
		} else return false;
	}
	
	public function requireRole() {
		if($this->verification != false) {
			$roles = func_get_args();
			foreach($roles as $role) $switch = (in_array($role, $this->roles)) ? true : false ;
			if($switch) {
				Registry::fetch('EX')->load_state = 'application';
				$this->verification = true;
			} elseif($type == UATypeGuest) {
				Registry::fetch('EX')->load_state = 'login';
				$this->verification = false;
				Registry::fetch('UI')->notificationAdd(UIError, "You must login to access this page");
			} else {
				Registry::fetch('EX')->load_state = 'override';
				$this->verification = false;
				Registry::fetch('UI')->error("Permission Denied", "You do no have access rights for this page.");
			} return $switch;
		}
	}
	
	public function type() {
		$types = func_get_args();
		if(count($types) > 0) {
			foreach($types as $type) $switch = ($type == $this->type) ? true : false ;
			return $switch;
		} else return $this->type;
	}
	
	public function role() {
		$roles = func_get_args();
		if(count($roles) > 0) {
			foreach($roles as $role) $switch = (in_array($role, $this->roles)) ? true : false ;
			return $switch;
		} else return $this->role;
	}
	
	private function killSession() {
		Registry::fetch('EX')->end("Force Kill");
	}
	
}