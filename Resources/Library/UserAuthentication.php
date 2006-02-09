<?php # User Authentication [ axiixc ] : Initilization, Checks and Public Methods

class UserAuthentication {
	
	public $conf, $uconf, $user, $role, $type, $guest;
	private $session, $action, $mode, $session_limit, $application, $content, $verification, $roles;
	
	public function __construct() {
		
		/* Initial Environment Setup */
		$this->session_limit = day; #SQL
		$rSeed = uniqid();
		$guest_template = EXFetchResource('guest_template');
		$default_guest_conf = EXFetchResource('default_guest_conf');
		$default_user_conf = EXFetchResource('default_user_conf');
		$this->roles = EXFetchResource('user_roles');
		
		/* Checking for old sessions */
		if(!is_null($_COOKIE['sess_id'])) { # Attempt to awake from old session
			$session_id = EXMySQLSafe($_COOKIE['sess_id']);
			$session_result = fetch('DB')->query("SELECT * FROM `[prefix]session` WHERE `id` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $session_id);
			$session = mysql_fetch_assoc($session_result);
			$user_result = fetch('DB')->query("SELECT * FROM `[prefix]users` WHERE `username` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $session['user']);
			$user = mysql_fetch_assoc($user_result);
			
			# Run checks [ can this be condensed ? ]
			$user = (mysql_num_rows($user_result)) ? true : false ;
			$guest = ($session['guest'] == 1) ? true : false ;
			$expire = ($session['expire'] > time()) ? true : false ;
			if($guest) {
				$cookie = ($_COOKIE['sess_verify'] == md5($session['key'].client_ip)) ? true : false ;
				$ban = (in_array($session['user'], EXFetchResource('banned_ips'))) ? true : false ;
			} else {
				$cookie = ($_COOKIE['sess_verify'] == md5($session['key'].$user['username'].$user['password'])) ? true : false ;
				$ban = ($user['type'] == UATypeBan) ? true : false ;
			}
			
			# conf, uconf, user, role, type, session, guest, action, gsession
			# Validate against check templates (are more needed?)
			if($user and !$guest and $expire and $cookie and !$ban) {
				$this->conf = EXUnfold($session['conf']);
				$this->uconf = EXUnfold($user['conf']);
				$this->user = array( # Common Info
					'id' => $user['id'], 'name' => $user['username'], 'display-name' => $user['display-name'], 'first-name' => $user['fname'],
					'middle-name' => $user['mname'], 'last-name' => $user['lname'], 'email' => $user['email'] );
				$this->role = $user['role'];
				$this->type = $user['type'];
				$this->session = $session['id'];
				$this->guest = false;
				$this->action = 'Reload';
				$gsession = false;
			} else if(!$user and $guest and $expire and $cookie and !$ban) {
				$this->conf = EXUnfold($session['conf']);
				$this->uconf = array();
				$this->user = $guest_template;
				$this->role = 'guest';
				$this->type = UATypeGuest;
				$this->session = $session['id'];
				$this->guest = true;
				$this->action = 'Guest Reload';
				$gsession = false;
			} else {
				if(mysql_num_rows($session_result) > 0) fetch('DB')->query("DELETE FROM `[prefix]sessions` WHERE CONVERT(`[prefix]sessions`.`id` USING utf8) = '%s' LIMIT 1", $sess_id);
				setcookie('sess_id', null, destroy);
				setcookie('sess_verify', null, destroy);
				$gsession = true;
				$this->action = 'Destroy';	
			}
			
		} else { 
			$gsession = true; 
			$this->action= 'Bad Cookie'; 
		}
		
		/* Creating New Sessions */
		if(isset($_POST['UAU']) and isset($_POST['UAP'])) {
			$username = EXMySQLSafe($_POST['UAU']);
			$password = md5($_POST['UAP']);
			$user = mysql_fetch_assoc(fetch('DB')->query("SELECT * FROM `[prefix]users` WHERE `username` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $username));
			
			if($password == $user['password']) {
				# Setup environment
				$sess_key = md5($rSeed);
				$sess_id = md5($sess_key);
				$sess_verify = md5($sess_key.$sess_user.$user['password']);
				
				# Load into class
				$this->conf = $default_user_conf;
				$this->uconf = EXUnfold($user['conf']);
				$this->user = array( # Common Info
					'id' => $user['id'], 'name' => $user['username'], 'display-name' => $user['display-name'], 'first-name' => $user['fname'],
					'middle-name' => $user['mname'], 'last-name' => $user['lname'], 'email' => $user['email'] );
				$this->role = $user['role'];
				$this->type = $user['type'];
				$this->session = $session_id;
				$this->guest = false;
				$this->mode = 'New';
				
				setcookie('sess_id', $sess_id, $this->session_limit);
				setcookie('sess_verify', $sess_verify, $this->session_limit);
				fetch('DB')->query("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`) VALUES ('%s', '%s', '%s', '%s', '%s', %s);", $sess_id, $sess_key, $user['username'], $this->conf, $this->session_limit, 0);
			} else $gsession = true;
		} if($gsession) { # Render from guest template
			# Setup environment
			$sess_key = md5($rSeed);
			$sess_id = md5($sess_key);
			$sess_verify = md5($sess_key.client_ip);
			
			# Load into class
			$this->conf = $default_guest_conf;
			$this->uconf =array();
			$this->user = $guest_template;
			$this->role = 'guest';
			$this->type = UATypeGuest;
			$this->session = $session_id;
			$this->guest = true;
			$this->mode = 'New Guest';
			
			setcookie('sess_id', $sess_id, $this->session_limit);
			setcookie('sess_verify', $sess_verify, $this->session_limit);
			fetch('DB')->query("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`) VALUES ('%s', '%s', '%s', '%s', '%s', %s);", $sess_id, $sess_key, client_ip, $this->conf, $this->session_limit, 1);
		}
	}
	
	public function requireType() {
		if($this->verification != false) {
			$types = func_get_args();
			foreach($types as $type) $switch = ($type == $this->type) ? true : false ;
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