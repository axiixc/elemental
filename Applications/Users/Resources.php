<?php # Users Manager [axiixc] : Application class and direct user class

class Users {
	
	public function __construct() {}
	
	public function display_profile($user_id=null) {
		# Fetch User Info
		if(is_null($user_id)) $user_id = Registry::fetch('UAuth')->user['id'];
		$user = new User($user_id, true);
		
		# Just get it over with
		if($user->private_profile == 1) {
			error("Profile is Private", "This user has chosen to keep their profile private.");
			return null;
		}
		
		# Do the templating [profile-page:profile-page-service-wrapper:profile-page-service:profile-page-service-names]
		$template_profile_page = (!is_null(template('Profile Page'))) ?
			template('Profile Page') :
			<<<EOD
			<h1>%2\$s<div style="font-size:small">%3\$s %5\$s</div></h1>
			<img src="%11\$s" />
			<table>%6\$s</table>
			<fieldset>
				<legend>About Me</legend>
				<div>%8\$s</div>
			</fieldset>
			<blockquote>%9\$s</blockquote>
			-- %7\$s
EOD;
		
		$template_service_wrapper = (!is_null(template('Profile Page Service Wrapper'))) ?
			template('Profile Page Service Wrapper') :
			'<tr><td>%1$s</td><td><ul>%2$s</ul></td></tr>';
		
		$template_service = (!is_null(template('Profile Page Service'))) ?
			template('Profile Page Service') :
			'<li>%1$s</li>';
			
		# A bit odd, should we compensate for this somehow?
		$service_names = (!is_null(template('Profile Page Service Names'))) ?
			template('Profile Page Service Names') :
			array('web' => 'Web', 'aim' => 'AIM', 'skype' => 'Skype', 'msn' => 'MSN', 'gtalk' => 'Google Talk', 'irc' => 'IRC') ;
			
		# Create the Services
		#WEB
		if(count($user->other['web']) > 0) {
			foreach($user->other['web'] as $item) 
				$tmp_web .= sprintf($template_service, "<a href=\"$item\">$item</a>");
			$services .= sprintf($template_service_wrapper, $service_names['web'], $tmp_web);
		}
		
		#AIM
		if(count($user->other['aim']) > 0) {
			foreach($user->other['aim'] as $item)
				$tmp_aim .= sprintf($template_service, "<a href=\"aim:$item\">$item</a>");
			$services .= sprintf($template_service_wrapper, $service_names['aim'], $tmp_aim);
		}
		
		#SKYPE
		if(count($user->other['skype']) > 0) {
			foreach($user->other['skype'] as $item)
				$tmp_skype .= sprintf($template_service, "<a href=\"skype:$item\">$item</a>");
			$services .= sprintf($template_service_wrapper, $service_names['skype'], $tmp_skype);
		}
		
		#MSN
		if(count($user->other['msn']) > 0) {
			foreach($user->other['msn'] as $item)
				$tmp_msn .= sprintf($template_service, "<a href=\"msn:$item\">$item</a>");
			$services .= sprintf($template_service_wrapper, $service_names['msn'], $tmp_msn);
		}
		
		#GTALK
		if(count($user->other['gtalk']) > 0) {
			foreach($user->other['gtalk'] as $item)
				$tmp_gtalk .= sprintf($template_service, "<a href=\"gtalk:$item\">$item</a>");
			$services .= sprintf($template_service_wrapper, $service_names['gtalk'], $tmp_gtalk);
		}
		
		#IRC
		if(count($user->other['irc']) > 0) {
			foreach($user->other['irc'] as $net)
				$tmp_irc .= sprintf($template_service, "<a href=\"irc://{$net['network']}\">{$net['network']} as {$net['handle']}</a>");
			$services .= sprintf($template_service_wrapper, $service_names['irc'], $tmp_irc);
		}
		
		# Write the main page
		add(sprintf(
			$template_profile_page,
			$user->name, #1
			$user->display_name, #2
			$user->first_name, #3
			$user->middle_name, #4
			$user->last_name, #5
			$services, #6
			$user->other['signature'], #7
			$user->other['bio'], #8
			$user->other['quote'], #9
			$user->avatar_small, #10
			$user->avatar_full, #11
			format_date($user->registered, Conf::read('Date Format')), #12
			format_date($user->login, Conf::read('Date Format')) #13
		));
		
		# Disclaimer
		notification(UINotice, "Um, none of the sidebars work ATM. But you probably already figured that out.");
		
		# More Sidebar
		$links[] = array('link' => 'javascript:;', 'name' => 'Message User');
		$links[] = array('link' => 'javascript:;', 'name' => 'Modify Friendship');
		$links[] = array('link' => 'javascript:;', 'name' => 'Block User');
		sidebar('menu', 'content', menu($links, true));
		
		# Friends Sidebar
		sidebar('menu', 'title', "$user->display_name's Friends", 'content', menu(array(array('link' => 'javascript:;', 'name' => "Friends not supported")), true));
		
		$find[] = array('link' => 'javascript:;', 'name' => 'Posts');
		$find[] = array('link' => 'javascript:;', 'name' => 'Pages');
		$find[] = array('link' => 'javascript:;', 'name' => 'Comments');
		$find[] = array('link' => 'javascript:;', 'name' => 'Images');
		$find[] = array('link' => 'javascript:;', 'name' => 'Projects');
		sidebar('menu', 'title', 'Find all...', 'content', menu($find, true));
	}
	
}

class User {
	
	public $id;
	public $name;
	public $display_name;
	public $first_name;
	public $middle_name;
	public $last_name;
	public $email;
	public $role;
	public $type;
	public $profile_link;
	public $private_profile;
	public $avatar_full;
	public $avatar_small;
	public $registered;
	public $login;
	public $conf=array();
	public $other=array();
	
	public function __construct($user_id, $light=false) { 
		$result = MySQL::query('SELECT * FROM `[prefix]users` WHERE `id` = %u', $user_id);
		if(mysql_num_rows($result) > 0) {
			$user = mysql_fetch_assoc($result);
			$this->id = $user_id;
			$this->name = $user['username'];
			$this->display_name = $user['dname'];
			$this->first_name = $user['fname'];
			$this->middle_name = $user['mname'];
			$this->last_name = $user['lname'];
			$this->email = $user['email'];
			$this->role = $user['role'];
			$this->type = $user['type'];
			$this->conf = unserialize($user['conf']);
			# Profile Link
			$link = "ex://Users/Profile?id=$this->id";
			$this->profile_link = Registry::fetch('Interface')->parse_link($link);
			if($light) {
				# Other Info
				if(!is_null($user['web'])) $this->other['web'] = unserialize($user['web']);
				if(!is_null($user['aim'])) $this->other['aim'] = explode(',', $user['aim']);
				if(!is_null($user['skype'])) $this->other['skype'] = explode(',', $user['skype']);
				if(!is_null($user['msn'])) $this->other['msn'] = explode(',', $user['msn']);
				if(!is_null($user['gtalk'])) $this->other['gtalk'] = explode(',', $user['gtalk']);
				if(!is_null($user['irc'])) $this->other['irc'] = unserialize($user['irc']);
				if(!is_null($user['signature'])) $this->other['signature'] = $user['signature'];
				if(!is_null($user['bio'])) $this->other['bio'] = $user['bio'];
				if(!is_null($user['quote'])) $this->other['quote'] = $user['quote'];
				$this->private_profile = $user['private'];
				if(is_null($user['favatar'])) $this->avatar_full = Registry::fetch('Interface')->avatar['full'];
				else $this->avatar_full = Registry::fetch('Interface')->parse_link("ex://Media/Avatar/{$user['favatar']}");
				if(is_null($user['savatar'])) $this->avatar_small = Registry::fetch('Interface')->avatar['small'];
				else $this->avatar_small = Registry::fetch('Interface')->parse_link("ex://Media/Avatar/{$user['savatar']}");
				$this->registered = $user['registered'];
				$this->login = $user['login'];
			}
		} else {
			Log::write("User::__construct($user_id) User does not exist.");
		}
	}
	
}