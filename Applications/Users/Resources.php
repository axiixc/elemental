<?php # Users Manager [axiixc] : Application class and direct user class

class Users {
	
	public function __construct() {
		
	}/* foo bar */
	
	public function display_profile($user_id=null) {
		# Fetch User Info
		if(is_null($user_id)) $user_id = Registry::fetch('UAuth')->user['id'];
		$user = new User($user_id);
	
		# Just get it over with
		if($user->private == 1) {
			Registry::fetch('Interface')->error("Profile is Private", "This user has chosen to keep their profile private.");
			return null;
		}
		
		# Do the templating
		$template_profile_page = (!is_null(Registry::fetch('Interface')->template('Profile Page'))) ?
			Registry::fetch('Interface')->template('Profile Page') :
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
		$template_service_wrapper = (!is_null(Registry::fetch('Interface')->template('Profile Page Service Wrapper'))) ?
			Registry::fetch('Interface')->template('Profile Page Service Wrapper') :
			'<tr><td>%s</td><td><ul>%s</ul></td></tr>';
		
		$template_service = (!is_null(Registry::fetch('Interface')->template('Profile Page Service'))) ?
			Registry::fetch('Interface')->template('Profile Page Service') :
			'<li>%s</li>';
			
		$service_names = (!is_null(Registry::fetch('Interface')->template('Profile Page Service Names'))) ?
			unserialize(Registry::fetch('Profile Page Service Names')) :
			array('web' => 'Web', 'aim' => 'AIM', 'skype' => 'Skype', 'msn' => 'MSN', 'gtalk' => 'Google Talk', 'irc' => 'IRC') ;
			
		# Create the Services
		#WEB
		if(count($user->other['web']) > 0) foreach($user->other['web'] as $item) $tmp_web .= sprintf($template_service, "<a href=\"$item\">$item</a>");
		$service[] = sprintf($template_service_wrapper, $service_names['web'], $tmp_web);
		
		#AIM
		if(count($user->other['aim']) > 0) foreach($user->other['aim'] as $item) $tmp_aim .= sprintf($template_service, "<a href=\"aim:$item\">$item</a>");
		$service[] = sprintf($template_service_wrapper, $service_names['aim'], $tmp_aim);
		
		#SKYPE
		if(count($user->other['skype']) > 0) foreach($user->other['skype'] as $item) $tmp_skype .= sprintf($template_service, "<a href=\"skype:$item\">$item</a>");
		$service[] = sprintf($template_service_wrapper, $service_names['skype'], $tmp_skype);
		
		#MSN
		if(count($user->other['msn']) > 0) foreach($user->other['msn'] as $item) $tmp_msn .= sprintf($template_service, "<a href=\"msn:$item\">$item</a>");
		$service[] = sprintf($template_service_wrapper, $service_names['msn'], $tmp_msn);
		
		#GTALK
		if(count($user->other['gtalk']) > 0) foreach($user->other['gtalk'] as $item) $tmp_gtalk .= sprintf($template_service, "<a href=\"gtalk:$item\">$item</a>");
		$service[] = sprintf($template_service_wrapper, $service_names['gtalk'], $tmp_gtalk);
		
		#IRC
		if(count($user->other['irc']) > 0) foreach($user->other['irc'] as $net) $tmp_irc .= sprintf($template_service, "<a href=\"irc://{$net['network']}\">{$net['network']} as {$net['handle']}</a>");
		$service[] = sprintf($template_service_wrapper, $service_names['irc'], $tmp_irc);
		
		# Consoliate with above actions
		foreach($service as $_service) $services .= $_service;
		
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
		
		# More Sidebar
		$links[] = array('link' => Registry::fetch('Interface')->parse_link("ex://Users/Message?new&id=$user->id"), 'name' => 'Message User');
		$links[] = array('link' => Registry::fetch('Interface')->parse_link("ex://Users/Friendship&id=$user->id"), 'name' => 'Modify Friendship');
		$links[] = array('link' => Registry::fetch('Interface')->parse_link("ex://Users/Block&id=$user->id"), 'name' => 'Block User');
		Registry::fetch('Interface')->sidebar('menu', 'content', Registry::fetch('Interface')->menu($links, true));
		
		# Friends Sidebar
		Registry::fetch('Interface')->sidebar('menu', 'title', "$user->display_name's Friends", 'content', Registry::fetch('Interface')->menu(array(array('link' => 'javascript:;', 'name' => "$user->display_name has no friends")), true));
		
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
	
	public function __construct($user_id) { 
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
			# Other Info
			$this->other = array(
				'web' => unserialize($user['web']),
				'aim' => explode(',', $user['aim']),
				'skype' => explode(',', $user['skype']),
				'msn' => explode(',', $user['msn']),
				'gtalk' => explode(',', $user['gtalk']),
				'irc' => unserialize($user['irc']),
				'signature' => $user['sig'],
				'bio' => $user['bio'],
				'quote' => $user['quote']
			);
			$this->private_profile = $user['private'];
			$this->avatar_full = Registry::fetch('Interface')->parse_link("ex://Media/Avatar/{$user['favatar']}");
			$this->avatar_small = Registry::fetch('Interface')->parse_link("ex://Media/Avatar/{$user['savatar']}");
			$this->registered = $user['registered'];
			$this->login = $user['login'];
		} else {
			Log::write("User::__construct($user_id) User does not exist.");
		}
	}
	
}