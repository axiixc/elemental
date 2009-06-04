<?php # User Interface [axiixc]

class ui {
	
	/* Variable Hell */
	public $content, $content_override;
	public $interface_keys = array(), $interface_default, $interface_override, $interface_login_window, $interface;
	public $ui, $path, $path_ui, $path_system, $override = false, $direct_echo = false;
	public $title_template, $title_system, $title_application;
	public $tagline, $footer, $description, $keywords;
	public $favicon = null, $apple_icon = null;
	public $sidebars = array(), $sidebar_count = 0;
	public $javascript = array();
	public $stylesheets = array();
	public $templates = array(), $templates_system = array();
	public $noticications = array();
	public $avatar_user, $avatar_user_full, $avatar_system, $avatar_system_full;
	
	private function __construct() {}
	
	public static function awake() {
		self::$ui = conf::read('UI');
		self::$path_ui = root . 'Resources/UI/' . self::$ui . '/';
		
		if(!file_exists($path_ui . '/Info.php')) {
			log::write('User Interface Load [' . self::$ui . '] Failed: User Defined UI Bundle not found. Defaulting to system bundle.')
			self::$ui = 'System';
		} include self::$path_ui . '/Info.php';
		
		$interface_keys = array(
			'1Bar' => '1Bar.php',
			'2Bar' => '2Bar.php',
			'3Bar' => '3Bar.php',
			'Box' => 'Box.php',
			'Blank' => 'Blank.php',
			'Print' => 'Print.php',
			'Mobile' => 'Mobile.php',
			'iPhone' => 'iPhone.php'
		);
		
		self::$interface_keys = array_merge($interface_keys, $Interface['interface_keys']);
		self::$interface_default = (isset($Interface['interface_default'])) ? $Interface['interface_default'] : '2Bar' ;
		self::$interface_override = (isset($Interface['interface_override'])) ? $Interface['interface_override'] : '2Bar' ;
		self::$interface_login_window = (isset($Interface['interface_login_window'])) ? $Interface['interface_login_window'] : 'Box' ;
		self::$interface = (isset($Interface['interface'])) ? $Interface['interface'] : '2Bar' ;
		
		self::$path = conf::read('Web Path').'Resources/UI/' . self::$ui . '/';
		self::$path_system = conf::read('Web Path').'Resources/UI/System/';
		
		self::$title_template = conf::read('Title Template');
		self::$title_system = conf::read('System Title');
		self::$tagline = conf::read('System Tagline');
		self::$footer = conf::read('System Footer');
		self::$description = conf::read('System Description');
		self::$keywords = conf::read('System Keywords');
		
		self::$stylesheets = $Interface['stylesheets'];
		
		self::$favicon = (conf::read('Favicon', bool)) ? conf::read('Favicon') : null ;
		self::$apple_icon = (conf::read('Apple Icon', bool)) ? conf::read('Apple Icon') : null ;
		
		if(is_null(self::$favicon) and isset($Interface['favicon'])) self::$favicon = $Interface['favicon'];
		if(is_null(self::$apple_icon) and isset($Interface['apple_icon'])) self::$favicon = $Interface['apple_icon'];
		
		if(file_exists(self::$path_ui . 'Templates.php')) {
			include self::$path_ui . 'Templates.php';
			self::$templates = $Templates;
		}
		include root . 'Resources/UI/System/Templates.php';
		self::$templates_system = $Templates;
		
		self::$avatar_user = self::$avatar_system = (isset($Interface['avatar_small'])) ? $Interface['avatar_small'] : self::$path_system . 'Images/avatar_small.png';
		self::$avatar_user_full = self::$avatar_system_full = (isset($Interface['avatar_full'])) ? $Interface['avatar_full'] : self::$path_system . 'Images/avatar_large.png';
	}
	
	public static function title($arg = false) {
		if(is_bool($arg)) {			
			if(is_null(self::$title_application)) {
				$output = str_replace('%a', self::$tagline, self::$title_template);
				$output = str_replace('%s', self::$title_system, $output);
			} else {
				$output = str_replace('%a', self::$title_applications, self::$title_template);
				$output = str_replace('%s', self::$title_system, $output);
			}
			
			if($arg) {
				echo "\t<title>$output</title>\n";
			} else {
				return $output;
			}
		} else {
			self::$title_applications = $arg;
		}
	}
	
	public static function favicon() {
		if(!is_null(self::$favicon)) {
			printf('<link rel="icon" href="%s" type="image/x-icon" />'."\n", $favicon);
			printf('<link rel="shortcut icon" href="%s" type="image/x-icon" />'."\n", $favicon);
		}
	}
	
	public static function apple_icon() {
		if(!is_null(self::$apple_icon)) {
			printf('<link rel="apple-touch-icon" href="%s" />'."\n", $favicon);
		}
	}
	
	# Rethink how CSS works with Interface bundles
	public static function css() {
		$internal_path = root . 'Resources/UI/' . self::$ui . '/Style/';
		$web_path = self::$path . 'Style/';
		
		if(file_exists($internal_path . 'Base.css')) self::stylesheet($web_path . 'Base.css');
		elseif(file_exists($internal_path . 'Base.php')) self::stylesheet($web_path . 'Base.php');
		else log::write("Interface Include CSS Warning: No base stylesheet");
		
		if(file_exists($internal_path . $interface . '.css')) self::stylesheet($web_path . $interface . '.css');
		elseif(file_exists($internal_path . $interface . '.php')) self::stylesheet($web_path . $interface . '.php');
		else log::write("Interface Include CSS Warning: No interface stylesheet");
	}
	
	public static function stylesheet($file) {
		if($file === true)
			foreach(self::$stylesheets as $sheet)
			echo '<link rel="stylesheet" type="text/css" href="' . $sheet . '" />' . "\n";
			
		else
			self::$stylesheets[] = $file;
	}
	
	public static function meta() {
		echo '<meta name="description" content="' . self::$description . '" /><meta name="keywords" content="' . self::$keywords . '" />';
	}
	
	public static function head() {
		self::title();
		self::css();
		self::stylesheet();
		self::favicon();
		self::apple_icon();
		self::meta();
		self::javascript();
	}
	
	public static function js_add($javascript, $location = head) {
		if(in_array($location, array(head, body, body_bottom))) self::$javascript['raw'][$location] .= $javascript;
		else log::write("Javascript Add [$location] Failed: Invalid location.");
	}
	
	public static function js_include($file, $location = head) {
		if(in_array($file, array(head, body, body_bottom))) self::$javascript['include'][$location][] = $file;
		else log::write("Javascript Include [$location] Failed: Invalid location.");
	}
	
	public static function js_onload($javascript) {
		if(substr($javascript, -1) == ';') self::$javascript[onload] .= $javascript;
		else self::$javascript['onload'] .= $javascript . ';';
	}
	
	public static function javascript($location) {
		switch ($location) {
			case onload:
				echo self::$javascript[onload];
				break;
			
			case body:
				echo self::$javascript['raw'][body];
				foreach(self::$javascript['include'][body] as $script) echo '<script src="' . $script . '"></script>';
				break;
				
			case body_bottom:
				echo self::$javascript['raw'][body_bottom];
				foreach(self::$javascript['include'][body_bottom] as $script) echo '<script src="' . $script . '"></script>';
				break;
				
			default: # AKA Head
				echo self::$javascript['raw'][head];
				foreach(self::$javascript['include'][head] as $script) echo '<script src="' . $script . '"></script>';
				break;
		}
	}
	
	public static function notification() {
		$args = func_get_args();
		# some cleanup to deal with badly made shortcut
		foreach($args as $key => $value) if(is_null($value)) unset($args[$key]);
		
		if(count($args)) == 0 or $args[0] == notification) { # Echo All
			foreach(self::$notifications[error] as $item) printf(self::template('Notification UIError', ''), $item);
			foreach(self::$notifications[notice] as $item) printf(self::template('Notification UINotice', ''), $item);
			return;
		} elseif(count($args) == 1) { # Echo <Type : 0>
			if(in_array($args[0], array(error, notice))) foreach(self::$notifications[$args[0]] as $item) printf(self::template('Notification ' . $args[0], ''), $item);
			else log::write("Notification Read [{$args[0]}] Failed: Invalid notification type.");
			return;
		} else { # Set <Type : 0> to <String : 1> with <Format : XXX>
			if(in_array($args[0], array(error, notice))) {
				$type = array_shift($args); $string = array_shift($args);
				self::$notifications[$type][] = vsprintf($string, $args);
			} else log::write("Notification Set [{$args[0]}] Failed: Invalid notification type.");
		}	
	}
	
	public static function notification_count() {
		foreach(func_get_args() as $type) $total += count(self::$notifications[$type]);
		return $total;
	}
	
	public static function error($title, $message) {
		self::$override = true;
		self::$content_override = sprintf(self::template('Notification Error', ''), $title, $message);
	}
	
	public static function menu($input, $pre = null, $item = null, $post = null, $pre2 = null) {
		if(is_string($input)) { # Fetch by name
			crunch($input);
			$result = query("SELECT *  FROM `[prefix]menus` WHERE `name` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $input);
			if(mysql_num_rows($result) == 1) $menu = mysql_fetch_assoc($result);
			else log::write("Interface Read Menu [$input] Failed: No menu with supplied name.");
		} elseif(is_array($input)) {
			$navigation = $input;
		} else { # Fetch menu by ID
			
			/** THIS IS WHERE YOU FELL ASLEEP **/
			
	}
	
	public static function parse_link() {}
	
	public static function parse_links() {}
	
	public static function is_page() {}
	
	public static function sidebar() {}
	
	public static function template() {}
	
	public static function user_interface() {}
	
	public static function content() {}
	
	public static function debug() {}
	
}

function add() {}

function js() {}

function js_include() {}

function js_onload() {}

function path() {}

function title() {}

function parse_link() {}

function parse_links() {}

function sidebar() {}

function notification() {}

function menu() {}

function template() {}

function error() {}

function free_cookie() {
	notification(notice, "You won a free cookie!");
}