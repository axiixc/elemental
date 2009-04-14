<?php # User Authentication [axiixc]

/* Add ability to move to selected page by http_reffer when logging in */

class UserAuthentication {
	
	public $conf=array(), $uconf=array(), $user=array(), $role, $type, $guest, $login, $verification;
	private $session, $action, $mode, $limit, $roles;
	
	public function __construct() {
		$this->roles = import("User Roles");
		$this->limit = constant(Conf::read("User Authentication Session Limit"));
		$this->action = 'Not Run';
		$this->mode = 'Not Run';
		$this->verification = true;
		$this->login = false;
		Log::write(print_r($_COOKIE, true));
	}
	
	public function awake() {
		Log::write('AWAKE');
		if(!is_null($_COOKIE['sess_id'])) {
			Log::write('sess_id is not null');
			$session_id = mysql_safe($_COOKIE['sess_id']);
			$session_result = MySQL::query("SELECT * FROM `[prefix]sessions` WHERE `id` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $session_id);
			$session = mysql_fetch_assoc($session_result);
			$user_result = MySQL::query("SELECT * FROM `[prefix]users` WHERE `username` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $session['user']);
			$user_data = mysql_fetch_assoc($user_result);
			
			Log::write(print_r($session, true));
			Log::write(print_r($user_data, true));
			
			# Run checks [ can this be condensed ? ]
			$user = (mysql_num_rows($user_result) == 1) ? true : false ;
			$guest = ($session['guest'] == 1) ? true : false ;
			$expire = ($session['expire'] > time()) ? true : false ;
			if($guest) { # Cookie for GUEST
				$cookie = $this->validate_keys($session['key'], $_COOKIE['sess_id'], $_COOKIE['sess_verify'], null, null);
				$ban = (in_array($session['user'], import('Banned IPs'))) ? true : false ;
			} else { # Cookie for REGISTERED USER
				$cookie = $this->validate_keys($session['key'], $_COOKIE['sess_id'], $_COOKIE['sess_verify'], $user_data['username'], $user_data['password']);
				$ban = ($user_data['type'] == UATypeBan) ? true : false ;
			}
			
			Log::write($session['key']);
			Log::write('CREDS: '.$user_data['username'].$user_data['password']);
			
			Log::write("USER: $user, GUEST: $guest, EXPIRE: $expire, COOKIE: $cookie, BAN: $ban");
			
			if($user and !$guest and $expire and $cookie and !$ban) { # Registered User
				Log::write('Found to be Normal Reload');
				$this->action = 'Reload';
				$this->load_session($user_data, $session);
			} elseif(!$user and $guest and $expire and $cookie and !$ban) { # Guest
				Log::write('Found to be Guest Reload');
				$this->action = 'Reload Guest';
				$this->load_session('guest', $session);
			} elseif($ban) {
				Log::write('Found to be banned');
				$this->action = 'Deny';
				$this->verification = false;
				Registry::fetch('Interface')->error("Banned", "You have been banned from this site.");
			} else { # Destroy and create anew
				Log::write('Found to be Destroy');
				if(mysql_num_rows($session_result) > 0) MySQL::query("DELETE FROM `[prefix]sessions` WHERE CONVERT(`[prefix]sessions`.`id` USING utf8) = '%s' LIMIT 1", $session['id']);
				setcookie('sess_id', null, destroy, '/');
				setcookie('sess_verify', null, destroy, '/');
				$this->action = 'Destroy';
				$this->create_session();
			}
		} else {
			Log::write('sess_id is null');
			$this->action = 'No Cookie';
			$this->create_session();
		}
	}
	
	public function test_login() {
		Log::write('Test Login');
		$this->action = 'Testing Login';
		if($this->create_session())
		if($this->login === true) header('Location: '.Registry::fetch('Interface')->parse_link(Conf::read("Login Redirect")));
		if($this->login === false and $this->guest === false) Registry::fetch('Interface')->notification(UIError, 'Login failed. Check your username and password.');
	}
	
	public function load_session($user, $session) {
		Log::write('load_session invoked');
		if($user == 'guest') {
			Log::write("LOAD SESSION: GUEST");
			$this->conf = unserialize($session['conf']);
			$this->uconf = array();
			$this->user = import('Guest User');
			$this->role = 'guest';
			$this->type = UATypeGuest;
			$this->session = $session['id'];
			$this->guest = true;
			$this->action = 'Guest Reload';
		} else {
			Log::write("LOAD SESSION: USER");
			$this->conf = unserialize($session['conf']);
			$this->uconf = unserialize($user['conf']);
			$this->user = array(
				'id' => $user['id'], 
				'name' => $user['username'], 
				'display-name' => $user['dname'], 
				'first-name' => $user['fname'],
				'middle-name' => $user['mname'], 
				'last-name' => $user['lname'], 
				'email' => $user['email'] );
			$this->role = $user['role'];
			$this->type = $user['type'];
			$this->session = $session['id'];
			$this->guest = false;
			$this->action = 'Reload';
			$this->login = true;
		}
	}
	
	public function create_session() {
		Log::write('create_session invoked');
		
		$limit = $this->limit;
		$do_guest = true;
		
		if($this->action == 'Not Run') return false;
		Log::write('OK guess we keep going');
		
		if(isset($_POST['UAU']) and isset($_POST['UAP'])) {
		/* REGISTERED SESSION CREATION */
			Log::write('Registered session creation, maybe');
		
			# Generate Validation Info
			$username = mysql_safe($_POST['UAU']);
			$password = md5($_POST['UAP']);
			$user = mysql_fetch_assoc(MySQL::query("SELECT * FROM `[prefix]users` WHERE `username` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $username));
			
			// Reload
			if($password == $user['password']) {
				Log::write('RSC yup');
				
				$session = $this->generate_keys(uniqid(), $user['username'], $user['password']);
				$this->conf = import("Default User Conf");
				$this->uconf = unserialize($user['conf']);
				$this->user = array(
					'id' => $user['id'], 
					'name' => $user['username'], 
					'display-name' => $user['dname'], 
					'first-name' => $user['fname'],
					'middle-name' => $user['mname'], 
					'last-name' => $user['lname'], 
					'email' => $user['email'] );
				$this->role = $user['role'];
				$this->type = $user['type'];
				$this->session = $session['id'];
				$this->guest = false;
				$this->mode = 'New';
				$this->login = true;
				
				setcookie('sess_id', $session['id'], $limit, '/');
				setcookie('sess_verify', $session['verify'], $limit, '/');
				MySQL::query("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`, `ip`) VALUES ('%s', '%s', '%s', '%s', '%s', %s, '%s');", $session['id'], $session['key'], $user['username'], serialize($this->conf), $limit, '0', client_ip);
				
				Log::write("KEY: {$session['key']}");
				Log::write("ID: {$session['id']}");
				Log::write("ID: {$_COOKIE['sess_id']}");
				Log::write("VERIFY: {$session['verify']}");
				Log::write("VERIFY: {$_COOKIE['sess_verify']}");
				Log::write('CREDS: '.$user['username'].$user['password']);
				$do_guest = false;
			}
			
		}
		
		if($do_guest) {
		/* GUEST SESSION CREATION */
		
			Log::write('Guest session creation');
			$session = $this->generate_keys(uniqid(), null, null);
			$this->conf = import("Guest Conf");
			$this->uconf = array();
			$this->user = import("Guest User");
			$this->role = 'guest';
			$this->type = UATypeGuest;
			$this->guest = true;
			$this->session = $session['id'];
			$this->mode = 'New Guest';
			
			setcookie('sess_id', $session['id'], $limit, '/');
			setcookie('sess_verify', $session['verify'], $limit, '/');
			MySQL::query("INSERT INTO `[database]`.`[prefix]sessions` (`id`, `key`, `user`, `conf`, `expire`, `guest`, `ip`) VALUES ('%s', '%s', '%s', '%s', '%s', %s, '%s');", $session['id'], $session['key'], client_ip, serialize($this->conf), $limit, '1', client_ip);
			
			Log::write("KEY: {$session['key']}");
			Log::write("ID: {$session['id']}");
			Log::write("ID: {$_COOKIE['sess_id']}");
			Log::write("VERIFY: {$session['verify']}");
			Log::write("VERIFY: {$_COOKIE['sess_verify']}");
		}
		
	}

	private function generate_keys($auth_token, $username, $password) {
		$sess['key'] = md5($auth_token);
		Log::write("KEY {$sess['key']}");
		$sess['id'] = md5($sess['key']);
		Log::write("ID: {$sess['id']}");
		$sess['verify'] = md5($sess['key'].$username.$password.client_ip);
		Log::write("VERIFY {$sess['verify']}");
		return $sess;
	}
	
	private function validate_keys($key, $rSess_id, $rSess_verify, $username, $password) {
		Log::write("VALIDATING");
		Log::write("\tGiven: $key, $username, $password");
		Log::write("\tVKEY {$key}");
		$sess['id'] = md5($key);
		Log::write("\tMADE {$sess['id']}");
		Log::write("\tHAD  {$rSess_id}");
		$sess['verify'] = md5($key.$username.$password.client_ip);
		Log::write("\tMADE {$sess['verify']}");
		Log::write("\tHAD  {$rSess_verify}");
		Log::write("VALIDATION END");
		if($sess['id'] == $rSess_id and $sess['verify'] == $rSess_verify) return true;
		else return false;
	}
	
	public function integrity() {
		$gen = $this->generate_keys(x, 'foo', 'bar');
		if($this->validate_keys($gen['key'], $gen['id'], $gen['verify'], 'foo', 'bar')) return true;
		else return false;
	}
	
	public function log_msgs() {
		Log::write("User Authentication Action: $this->action");
		Log::write("User Authentication Mode: $this->mode");
	}
	
	public function require_type() {
		if($this->type == UATypeAdmin) return true;
		if($this->verification != false) {
			$types = func_get_args();
			foreach($types as $type) $switch = ($type == $this->type) ? true : false ;
			if($switch) {
				$this->verification = true;
			} elseif($type == UATypeGuest) {
				Registry::fetch('Interface')->display_login();
				$this->verification = false;
				Registry::fetch('Interface')->notification(UIError, "You must login to access this page.");
			} else {
				$this->verification = false;
				Registry::fetch('Interface')->error("Permission Denied", "You do not have access rights for this page.");
			} return $switch;
		} else return false;
	}
	
	public function require_role() {
		if($this->type == UATypeAdmin) return true;
		if($this->verification != false) {
			$roles = func_get_args();
			foreach($roles as $role) $switch = (in_array($role, $this->roles)) ? true : false ;
			if($switch) {
				$this->verification = true;
			} elseif($this->type == UATypeGuest) {
				Registry::fetch('Interface')->display_login();
				$this->verification = false;
				Registry::fetch('Interface')->notification(UIError, "You must login to access this page.");
			} else {
				$this->verification = false;
				Registry::fetch('Interface')->error("Permission Denied", "You do not have access rights for this page.");
			} return $switch;
		} else return false;
	}
	
	public function type() {
		$types = func_get_args();
		if(count($types) > 0) {
			foreach($types as $type) $switch = ($type == $this->type) ? true : false ;
			return $swtich;
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
		Registry::fetch('System')->end("Force Kill");
	}
	
	public function read($key) {
		
	}
	
	public function write($key, $value) {
		
	}
	
	public function delete($key) {
		
	}
	
	public function uread($key) {
		
	}
	
	public function uwrite($key, $value) {
		
	}
	
	public function udelete($key) {
		
	}

	public function diagnostics($return=false) {
		$output['cookie-session-id'] = $_COOKIE['sess_id'];
		$output['cookie-session-verifiy'] = $_COOKIE['sess_verify'];
		if(is_null($this->conf) or count($this->conf) == 0) $output['session-config'] = null;
		else $output['session-config'] = print_r($this->conf, true);
		if(is_null($this->uconf) or count($this->uconf) == 0) $output['user-config'] = null;
		else $output['user-config'] = print_r($this->uconf, true);
		$output['user-information'] = print_r($this->user, true);
		$output['role'] = $this->role;
		$output['type'] = $this->type;
		$output['guest-user'] = $this->guest;
		$output['is-logged-in'] = $this->login;
		$output['session-id'] = $this->session;
		$output['load-action'] = $this->action;
		$output['load-mode'] = $this->mode;
		$output['session-time-limit'] = uncrunch(Conf::read("User Authentication Session Limit")).' or until '.$this->limit;
		$output['verification'] = $this->verification;
		$output['roles'] = print_r($this->roles, true);
		$output['key-test'] = $this->integrity();
		return diagnostic($output, $return);
	}
	
}