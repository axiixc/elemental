<?php # User Authentication [axiixc]

class Authority
{
	
	private $statements;
	
	private $verified, $reload, $guest;
	private $type, $role, $roles, $username, $user_id;
	private $session_configuration, $session_configuration_hash;
	private $action, $mode;
	private $session, $auth;
	
	public function __construct()
	{
	   // Put all of the queries in one place ;)
	   $this->statements['new_session'] = "INSERT INTO `[database]`.`[prefix]sessions` (`id`, `seed`, `verification_code`, `valid_ip`, `expire_time`, `user`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');";
	   $this->statements['update_time'] = "UPDATE `[database]`.`[prefix]sessions` SET `expire_time` = '%s' WHERE CONVERT(`[prefix]sessions`.`id` USING utf8) = '%s' LIMIT 1;";
	   $this->statements['update_conf'] = "UPDATE `[database]`.`[prefix]sessions` SET `configuration` = '%s' WHERE CONVERT(`[prefix]sessions`.`id` USING utf8) = 'i-d' LIMIT 1;";
	   $this->statements['load_session'] = "SELECT * FROM `[prefix]sessions` WHERE `id` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
	   $this->statements['delete_session'] = "DELETE FROM `[prefix]sessions` WHERE CONVERT(`[prefix]sessions`.`id` USING utf8) = '%s' LIMIT 1";
	   $this->statements['delete_past_sessions'] = "DELETE FROM `[prefix]sessions` WHERE `expire_time` < %s";
	   $this->statements['user_from_id'] = "SELECT *  FROM `[prefix]users` WHERE `id` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
	   $this->statements['user_from_username'] = "SELECT *  FROM `[prefix]users` WHERE `username` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
	   
		// Check for banned users by IP regardless (user check comes later)
		if (in_array($this->sClientIP, cfRead('Banned IPs')))
		{
			die(cfRead('Banned User Error Message'));
		}
		
		// Setup properties
		$this->verified = true; // This is assumed, can be override in Initialize.php
		$this->reload = false;  // Shortcut: Is this a reload or new session
		$this->guest = true;    // Shortcut: Is user a guest
		$_create = true;        // Make new session?
		
		// Diagnostic Defaults
		$this->action = '-- action : not set --';
		$this->mode = '-- mode : not set --';
		
		$this->roles = cfRead('Roles'); // For documentation on roles see section X.X.X
		
		// Setup Session
		$this->session['id'] = mysql_safe($_COOKIE['kSessionID']);
		$this->session['seed'] = mysql_safe($_COOKIE['kSessionSeed']);
		
		// Setup Auth
		$this->auth['attempt'] = (isset($_POST['kAuthAttempt']));
		$this->auth['username'] = mysql_safe($_POST['kAuthUsername']);
		$this->auth['password'] = md5($_POST['kAuthPassword']);
		
		$random_helper = unique_seed();
		
		// Check for possibilit of reloading session
		if (!is_null($this->session['id']))
		{
		   exMethod('Authority: _attemptReload');
			// Load the session
			$_session = query($this->statements['load_session'], $this->session['id']);
			if (is_resource($_session) and mysql_num_rows($_session) == 1)
			{
			   exMethod('Authority: _sessionExists');
				$session = mysql_fetch_assoc($_session);
				// Check for user type
				$session['guest'] = (substr($session['user'], 0, 2) == 'g:');
				
				// Load user (based on type info)
				if (!$session['guest'])
				{
				   exMethod('Authority: _sessionIsGuest');
					$_user = query($this->statements['user_from_id'], substr($session['user'], 2));
					if (is_resource($_user) and mysql_num_rows($_user))
					{
						$session['user'] = mysql_fetch_assoc($_user);
					}
					
					// Check if user is banned
					if ($session['user']['banned'])
					{
						die(cfRead('Banned User Error Message'));
					}
				}
				
				$test['v_Code'] = (md5($this->session['seed'] . $session['seed']) == $session['verification_code']);
				$test['ip'] = (client_ip == $session['valid_ip']);
				$test['expire'] = ($session['expire_time'] > time());
				exMethod(print_r($test, true));
				
				if (md5($this->session['seed'] . $session['seed']) == $session['verification_code'] and 
				   client_ip == $session['valid_ip'] and $session['expire_time'] > time())
				{
					$this->sessionReload($session);
					$_create = false;
				}
			}
		}
		
		// Work with auth attempts
		if ($this->auth['attempt'])
		{
		   exMethod('Authority: _authAttempt');
			$_user = query($this->statements['user_from_username'], $this->auth['username']);
			if (is_resource($_user) and mysql_num_rows($_user) == 1)
			{
				$user = mysql_fetch_assoc($_user);
            exLog("\n" . $this->auth['password'] . "\n" . $user['password']);
				if ($this->auth['password'] == $user['password'])
				{
					$this->sessionCreate($user);
					$_create = false;
				}
			}
		}
		
		// Create New Session ???
		if ($_create)
		{
		   exMethod('Authority: _create');
			$this->sessionCreate();
		}
	}
	
	private function sessionCreate($user = null)
	{
		$this->sessionDestroy();
		
		if (is_null($user)) // Creating a guest user
		{
		   exMethod('Authority->sessionCreate(guest)');
			$_user = query($this->statements['user_from_username'], 'guest');
			if (is_resource($_user) and mysql_num_rows($_user) == 1)
			{
				$user = mysql_fetch_assoc($_user);
			}
			$session['user'] = 'g:' . client_ip;
			$this->guest = true;
		}
		else
		{
		   exMethod("Authority->sessionCreate($user)");
			$session['user'] = 'u:' . $user['id'];
			$this->guest = false;
		}
		
		$session['id'] = md5(time() . unique_seed());
		$session['seed']['client'] = md5(unique_seed() . $user['password']);
		$session['seed']['server'] = md5(unique_seed() . $user['id']);
		$session['verification_code'] = md5($session['seed']['client'] . $session['seed']['server']);
		$session['ip'] = client_ip;
		$session['expire_time'] = time() + cfRead('Session Expire Increment');
		
		$this->reload = false;
		$this->type = $user['type'];
		$this->role = $user['role'];
		$this->username = $user['username'];
		$this->user_id = $user['id'];
		
		$this->session_configuration_hash = md5(serialize($this->session_configuration));
		
		setCookie('kSessionID', $session['id'], $session['expire_time'], '/');
		setCookie('kSessionSeed', $session['seed']['client'], $session['expire_time'], '/');
		
		query($this->statements['new_session'], $session['id'], $session['seed']['server'], $session['verification_code'], $session['ip'], $session['expire_time'], $session['user']);
	}
	
	private function sessionReload($session)
	{
	   exMethod('Authority->sessionReload()');
		$this->sessionDestroy();
		
		$session['expire_time'] = time() + cfRead('Session Expire Increment');
		
		$this->reload = true;
		$this->type = $session['user']['type'];
		$this->role = $session['user']['role'];
		$this->username = $session['user']['username'];
		$this->user_id = $session['user']['id'];
		$this->guest = $session['guest'];
		
		$this->session_configuration = unserialize($session['configuration']);
		$this->session_configuration_hash = md5($session['configuration']);
		
		setCookie('kSessionID', $this->session['id'], $session['expire_time'], '/');
		setCookie('kSessionSeed', $this->session['seed'], $session['expire_time'], '/');
		
		query($this->statements['update_time'], $this->session['id'], $session['expire_time']);
	}
	
	private function sessionClose()
	{
	   exMethod('Authority->sessionClose()');
	   if (md5(serialize($this->session_configuration)) == $this->session_configuration_hash) // Update Configuration
	   {
	      query($this->statements['update_conf'], $this->session['id'], serialize($this->session_configuration));
      }
   }
	
	public function sessionDestroy($id = null)
	{
	   exMethod('Authority->sessionDestroy()');
	   if (!is_null($id))
	   {
	      query($this->statements['delete_session'], $id);
      }

		// Cleanup expired sessions
		query($this->statements['delete_past_sessions'], time());
	
		setCookie('kSessionID', null, destroy, '/1/2/3/4/5/4/3/2/1/invalid/path');
		setCookie('kSessionSeed', null, destroy, '/1/2/3/4/5/4/3/2/1/invalid/path');
   }
   
   public function showLogin()
   {
      System::InterfaceHandler()->page_type = override;
      System::InterfaceHandler()->content_override = template('Login Form', 
      '<form method="POST">
         <input type="hidden" name="kAuthAttempt" value="1" />
         <input type="text" name="kAuthUsername" />
         <input type="password" name="kAuthPassword" />
         <input type="submit" value="Submit" />
      </form>');
   }
   
   public function configurationRead($key)
   {
      crunch($key);
      if ($this->set($key))
      {
         return $this->session_configuration[$key];
      }
   }
   
   public function configurationWrite($key, $value)
   {
      crunch($key);
      $this->session_configuration[$key] = $value;
   }
   
   public function configurationDelete($key)
   {
      crunch($key);
      if ($this->set($key))
      {
         unset($this->session_configuration[$key]);
      }
   }
   
   public function configurationSet($key)
   {
      return (array_key_exists($key, $this->session_configuration));
   }
   
   public function requireType()
   {
      exMethod("Authority->requireType(): Type is $this->type");
      $types = func_get_args();
      if (is_array($types[0]))
      {
         $types = $types[0];
      }
      
      foreach ($types as $type)
      {
         if ($type == $this->type)
         {
            if (!$this->verified)
            {
               return false;
            }
            else
            {
               return true;
            }
         }
      }
      
      $this->verified = false;
      return false;
   }
   
   public function requireRole()
   {
      exMethod("Authority->requireRole(): Role is $this->role");
      $roles = func_get_args();
      if (is_array($roles[0]))
      {
         $roles = $roles[0];
      }
      
      foreach ($roles as $role)
      {
         if ($role == $this->role)
         {
            if (!$this->verified)
            {
               return false;
            }
            else
            {
               return true;
            }
         }
      }
      
      $this->verified = false;
      return false;
   }
   
   public function requireReassert()
   {
      // Not implimented
      exLog('Authority->requireReassert(): Unsupport as of this build');
   }
   
   public function forceVerificationFail()
   {
      // There is no forceVerificationPass() for security reaons
      $this->verified = false;
   }
   
   // Enforce the read-only polocy ;)
   public function __get($key)
   {
       return $this->$key;
   }

   public function __set($key, $value)
   {
      // Nope, sorry
   }
   
   public function __destruct()
   {
      $this->sessionClose();
   }
}

/* Plain Function Accessors (normal name convention) */

function auRead($key)
{
   return System::Authority()->configurationRead($key);
}

function auWrite($key, $value)
{
   System::Authority()->configurationWrite($key, $value);
}

function auDelete($key)
{
   System::Authority()->configurationDelete($key);
}

function auSet($key)
{
   return System::Authority()->configurationSet($key);
}

function auRequireType()
{
   System::Authority()->requireType(func_get_args());
}

function auRequireRole()
{
   System::Authority()->requireRole(func_get_args());
}

function auRequireReassert()
{
   System::Authority()->requireReassert();
}

function auForceVerificationFail()
{
   System::Authority()->forceVerificationFail();
}