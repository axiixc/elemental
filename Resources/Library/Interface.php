<?php # User Interface Management [axiixc]

class UserInterface {
	
	/* Variable Hell */
	public $content;
	public $content_override;
	public $interface_keys = array();
	public $ui;
	public $path;
	public $default_interface;
	public $interface_override;
	public $login_window_interface;
	public $interface;
	public $title_template;
	public $system_title;
	public $app_title;
	public $tagline;
	public $footer;
	public $description;
	public $keywords;
	public $favicon;
	public $apple_icon;
	public $override = false;
	public $direct_echo = false;
	public $sidebar_counter = 0;
	public $javascript = array();
	public $notifications = array();
	public $sidebars = array();
	public $templates = array();
	public $system_templates = array();
	
	public function __construct($ui=null) {
		# Check for UI Init type
		if(is_null($ui)) $this->ui = Conf::read('UI');
		else $this->ui = $ui;
		# Check Validity
		if(!file_exists(root."Resources/UI/{$this->ui}/")) {
			$this->ui = 'System';
			Log::write("Load UI: User defined UI bundle could not be found. Defaulting to System bundle.");
		}
		include root.'Resources/UI/'.$this->ui.'/Conf.php';
		$this->path = Conf::read("WWW Path").'Resources/UI/'.$this->ui.'/';
		# END FIX
		$this->default_interface = $this->interface = $this->interface_override = $Interface['default_interface'];
		$this->login_window_interface = (isset($Interface['login_window'])) ? $Interface['login_window'] : '1Bar' ;
		$this->interface_keys = array_merge(import("Interface Keys"), $Interface['interface_keys']);
		$this->title_template = Conf::read("Head Title Format");
		$this->system_title = Conf::read("Title");
		$this->tagline = Conf::read("Tagline");
		$this->footer = Conf::read("Footer");
		$this->description = Conf::read("Description");
		$this->keywords = Conf::read("Keywords");
		$this->favicon = Conf::read("Favicon");
		$this->apple_icon = Conf::read("Apple Icon");
		# Convert $_GET vars to notices/errors
		if(isset($_GET['error'])) foreach(explode('[%@%]', $_GET['error']) as $msg) $this->notification(UIError, $msg);
		if(isset($_GET['notice'])) foreach(explode('[%@%]', $_GET['notice']) as $msg) $this->notification(UINotice, $msg);
		# Load Templates
		include(root.'Resources/UI/System/Templates.php');
		$this->system_templates = $templates;
		if(file_exists(root.'Resources/UI/'.$this->ui.'/Templates.php')) {
			include root.'Resources/UI/'.$this->ui.'/Templates.php';
			$this->templates = $templates;
		}
	}
	
	/* Meta */
	public function title($return=false) {
		if(!is_null($this->title)) {
			$output = str_replace('%a', $this->title, $this->head_template);
			$output = str_replace('%t', $this->system_title, $output);
		} else $output = $this->system_title;
		if($return) return $output;
		else printf("\t<title>%s</title>\n", $output);
	}
	
	public function favicon() {
		if($this->favicon) $favicon = $this->favicon;
		else {
			$path = "Resources/UI/{$this->ui}/Images/favicon.";
			if(file_exists($path.".png")) $favicon = $path."png";
			elseif(file_exists($path.".ico")) $favicon = $path."ico";
			elseif(file_exists($path.".gif")) $favicon = $path."gif";
			else $favicon = null;
		}
		printf('<link rel="icon" href="%s" type="image/x-icon" />'."\n", $favicon);
		printf('<link rel="shortcut icon" href="%s" type="image/x-icon" />'."\n", $favicon);
	}
	
	public function appleicon() {
		if($this->apple_icon) $icon = $this->apple_icon;
		else {
			$path = "Resources/UI/{$this->ui}/Images/appleicon.png";
			if(file_exists($path)) $icon = $path;
			else $icon = null;
		} printf('<link rel="apple-touch-icon" href="%s" />'."\n", $favicon);
	}
	
	public function css() {
		$iPath = root."Resources/UI/{$this->ui}/Style/";
		$path = $this->path."Style/";
		
		if(file_exists($iPath."Base.css")) printf('<link rel="stylesheet" type="text/css" href="%s" />'."\n", $path."Base.css");
		elseif(file_exists($iPath."Base.php")) printf('<link rel="stylesheet" type="text/css" href="%s" />'."\n", $path."Base.php");
		else Log::write("Interface::css() No Base.(css|php) found.");
		
		if($this->override) $interface = $this->interface_override; else $interface = $this->interface;
		if(file_exists($iPath.$this->interface.".css")) printf('<link rel="stylesheet" type="text/css" href="%s" />'."\n", $path.$interface.".css");
		elseif(file_exists($iPath.$this->interface.".php")) printf('<link rel="stylesheet" type="text/css" href="%s" />'."\n", $path.$interface.".php");
		else Log::write("Interface::css() No $this->interface.(css|php) found.");
	}
	
	public function meta() {
		printf('<meta name="description" content="%s" />'."\n", $this->description);
		printf('<meta name="keywords" content="%s" />'."\n", $this->keywords);
	}
	
	public function head() {
		$this->title();
		$this->css($b, $i);
		$this->favicon();
		$this->meta();
		$this->javascript();
	}
	
	/* Javascript */
	public function js_add($js, $head=true) {
		if($head) $this->javascript['head'] .= $js."\n";
		else $this->javascript['body'] .= $js."\n";
	}
	
	public function js_include($file, $head=true) {
		if($head) $this->javascript['include_head'][] .= $file;
		else $this->javascript['include_body'][] .= $file;
	}
	
	public function javascript($head=true) {
		if($head) {
			if(!is_null($this->javascript['head'])) printf('<script type="text/javascript">%s</script>', $this->javascript_head);
			if(count($this->javascript['include_head']) > 0)
				foreach($this->javascript['include_head'] as $file) printf('<script src="%s" type="text/javascript"></script>', $file);
		} else {
			if(!is_null($this->javascript['body'])) printf('<script type="text/javascript">%s</script>', $this->javascript_body);
			if(count($this->javascript['include_body']) > 0)
				foreach($this->javascript['include_body'] as $file) printf('<script src="%s" type="text/javascript"></script>', $file);
		}
	}
	
	/* Notifications */
	public function notification() {
		$args = func_get_args();
		if(count($args) == 0 or $args[0] == UINotification) { # Echo All
			foreach($this->notifications[UIError] as $item) printf($this->template("Notification UIError"), $item);
			foreach($this->notifiactions[UINotice] as $item) printf($this->template("Notification UINotice"), $item);
			return true;
		} elseif(count($args) == 1) { # Echo <0type>
			foreach($this->notifications[$args[0]] as $item) printf($this->template("Notification {$args[0]}"), $item);
			return true;
		} elseif(count($args) == 2) { # Return <0type> or Set <1string> to <0type>
			if($args[1] === true) { # Return <0type>
				if($args[0] == UINotification) { # Return All
					foreach($this->notifications[UIError] as $item) $x .= sprintf($this->template("Notification UIError"), $item);
					foreach($this->notifiactions[UINotice] as $item) $x .= sprintf($this->template("Notification UINotice"), $item);
					return $x;
				} else { # Return <0type>
					foreach($this->notifications[$args[0]] as $item) $x .= sprintf($this->template("Notification {$args[0]}"), $item);
					return $x;
				}
			} else { # Set <1string> to <0type>
				$this->notifications[$args[0]][] = $args[1];
				return true;
			}
		} else { # Unknown
			Log::write("Interface::notifiaction({$args[0]}, {$args[1]}, {$args[2]}) Invalid Syntax, cannot have more than two arguments. See manual for more details.");
		}
	}
	
	public function notification_count($type=UINotification) {
		# Count Errors
		if($type == UIError or $type == UINotification) $error_count = count($this->notifications[UIError]);
		else $error_count = 0;

		# Count Notices
		if($type == UINotice or $Type == UINotification) $notice_count = count($this->notifications[UINotice]);
		else $notice_count = 0;

		return $error_count + $notice_count;
	}

	public function error($title, $message) {
		$this->override = true;
		$this->interface_override = 'Box';
		#if(preg_match('^"ex://([0-9a-zA-Z/-_]+)"^')) preg_replace('^"ex://([0-9a-zA-Z/-_]+)"^', $this->parse_link($))
		$this->content_override = sprintf($this->template("Notification Error"), $title, $message);
	}
	
	/* Menus */
	public function menu($input, $pre=null, $item=null, $post=null, $pre2=null) {
		if(is_string($input)) { # Fetch menu by name
			$input = crunch($input);
			$result = MySQL::query("SELECT *  FROM `[prefix]menus` WHERE `name` = CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $input);
			if(mysql_num_rows($result) == 1) {
				$menu = mysql_fetch_assoc($result);
			} else {
				Log::write("Interface::menu($input) Menu not found with name.");
			}
		} elseif(is_array($input)) { # Given menu array
			$navigation = $input;
		} else { # Fetch menu by id
			$result = MySQL::query("SELECT *  FROM `[prefix]menus` WHERE `id` = %s ORDER BY `rank` ASC", $input);
			if(mysql_num_rows($result) == 1) {
				$menu = mysql_fetch_assoc($result);
			} else {
				Log::write("Interface::menu($input) Menu not found with id.");
			}
		} if(!isset($navigation)) { # Generate navigation from $menu
			$result = MySQL::query("SELECT *  FROM `[prefix]navigation` WHERE `menu` = %s ORDER BY `rank` ASC", $menu['id']);
			while($row = mysql_fetch_assoc($result)) $navigation[] = $row;
		} # $navigation now complete
		
		if($pre === true) { # Return
			if(is_null($pre2)) $pre = $this->template("Menu Pre");
			if(is_null($item)) $item = $this->template("Menu Item");
			if(is_null($post)) $post = $this->template("Menu Post");
			# Build the output
			$output = $pre;
			foreach($navigation as $link) {
				if($link['name'] == 'SESSION_LOGINOUT' and $link['link'] == 'SESSION_LOGINOUT') {
					if(Registry::fetch('UAuth')->login == true) {
						$link['name'] = 'Logout';
						$link['link'] = 'ex://Users/Logout';
					} else {
						$link['name'] = 'Login';
						$link['link'] = 'ex://Users/Login';
					}
				}
				$lnk = $this->parse_link($link['link']);
				# Sense for CURRENT goes here
				$output .= sprintf($item, $lnk, $link['name'], $current);
			} $output .= $post;
			return $output;
		} else { # Echo
			if(is_null($pre)) $pre = $this->template("Menu Pre");
			if(is_null($item)) $item = $this->template("Menu Item");
			if(is_null($post)) $post = $this->template("Menu Post");
			# Build the output
			$output = $pre;
			foreach($navigation as $link) {
				if($link['name'] == 'SESSION_LOGINOUT' and $link['link'] == 'SESSION_LOGINOUT') {
					if(Registry::fetch('UAuth')->login == true) {
						$link['name'] = 'Logout';
						$link['link'] = 'ex://Users/Logout';
					} else {
						$link['name'] = 'Login';
						$link['link'] = 'ex://Users/Login';
					}
				}
				$lnk = $this->parse_link($link['link']);
				# Sense for CURRENT goes here
				$output .= sprintf($item, $lnk, $link['name'], $current);
			} $output .= $post;
			echo $output;
		}
	}
	
	/* TODO: Add file_exists() style link checking */
	public function parse_link($link) {
		if(substr($link, 0, 5) == 'ex://') {
			if (preg_match("^ex://([0-9a-zA-Z]+)/([0-9a-zA-Z_./]+)([0-9a-zA-Z_./?&=]+)^", $link, $bits) == 1) {
				if($bits[1] == 'Interface') return $this->path.'Images/'.$bits[2].$bits[3];
				elseif($bits[1] == 'Resources') return Conf::read("WWW Path").'Resources/'.$bits[2].$bits[3];
				elseif($bits[1] == 'Application') return Conf::read("WWW Path").'Applications/'.$bits[2].$bits[3];
				elseif($bits[1] == 'Media') return Conf::read("WWW Path").'Media/'.$bits[2].$bits[3];
				elseif($bits[1] == 'Root') return Conf::read("WWW Path").$bits[2].$bits[3];
				else return Conf::read("WWW Path").$bits[1].'/'.$bits[2].$bits[3];
			} elseif(preg_match("^ex://([0-9a-zA-Z]+)([/]*)^", $link, $bits) == 1) {
				return Conf::read("WWW Path").$bits[1];
			}
		} else return $link;
	}
	
	public function parse_links($string) {
		
	}
	
	/* Sidebars */
	public function sidebar() {
		$args = func_get_args();
		$id = array_shift($args);
		if(is_null($id) or in_array(crunch($id), array('div', 'menu', 'image', 'aimage'))) { # New
			$tmp = eoargs($args);
			$tmp['type'] = (is_null($id)) ? 'div' : crunch($id) ;
			$this->sidebars[] = $tmp;
			$this->sidebar_counter++;
			return $this->sidebar_counter--;
		} else { # Read or Edit
			if($id == true) { # Read
				$template['div']['head'] = $this->template("DIV with Head");
				$template['div']['none'] = $this->template("DIV without Head");
				$template['menu']['head'] = $this->template("Menu with Head");
				$template['menu']['none'] = $this->template("Menu without Head");
				$template['image']['head'] = $this->template("Image with Head");
				$template['image']['none'] = $this->template("Image without Head");
				$template['aimage']['head'] = $this->template("A Image with Head");
				$template['aimage']['none'] = $this->template("A Image without Head");
				if($args[0] == true) { # All Sidebars
					if($args[1] == true) return $this->sidebars;
					else {
						foreach($this->sidebars as $id2 => $bar) {
							if($bar['type'] != 'aimage') {
								if(!is_null($bar['title'])) printf($template[$bar['type']]['head'], $bar['title'], $bar['content'], $id2);
								else printf($template[$bar['type']]['none'], $bar['content'], $id2);
							} else {
								if(!is_null($bar['title'])) printf($template[$bar['type']]['head'], $bar['title'], $bar['content'], $bar['link'], $id2);
								else printf($template[$bar['type']]['none'], $bar['content'], $bar['link'], $id2);
							}
						}
					}
				} else { # Sidebars by location
					foreach($this->sidebars as $id2 => $bar) $x[$id2] = $bar;
					if($args[1] == true) return $x;
					else {
						foreach($x as $id2 => $bar) {
							if(!is_null($bar['title'])) printf($template[$bar['type']]['head'], $bar['title'], $bar['content'], $id2);
							else printf($template[$bar['type']]['none'], $bar['content'], $id2);
						}
					}
				}
			} else { # Edit
				if(is_even($args)) {
					$i = 0;
					do {
						$this->sidebars[$id][crunch($args[$i])] = $args[$i++];
						$i = $i + 2;
					} while($i <= count($args));
				} else {
					Log::write("Interface::sidebar(edit) Bad argument layout. Make sure argument count, inclding key, is an odd number.");
				}
			}
		}
	}
	
	public function sidebar_delete($id) {
		if(isset($this->sidebars[$id])) unset($this->sidebar[$id]);
		else Log::write("Interface::sidebar_delete($id) Sidebar with id is not set.");
	}
	
	/* Template */
	public function template($name) {
		$name = crunch($name);
		return (isset($this->templates[$name])) ? $this->templates[$name] : $this->system_templates[$name] ;
	}
	
	public function display_login() {
		$this->interface_override = $this->login_window_interface;
		$this->content_override = str_replace('LOGINPATH', $this->parse_link('ex://Users/Login'), $this->template("Login Window"));
		$this->override = true;
	}
	
	/* Interface */
	public function uinterface($set=null, $name=false) {
		if(is_null($set)) {
			if($this->override) {
				$name = $this->interface_keys[$this->interface_override];
				return root."Resources/UI/{$this->ui}/Interfaces/{$name}";
			} else {
				$name = $this->interface_keys[$this->interface];
				return root."Resources/UI/{$this->ui}/Interfaces/{$name}";
			}
		} else $this->interface = $set;
	}

	public function content() {
		if($this->override) {
			echo $this->content_override;
		} else {
			echo $this->content;
		}
	}

	/* Debug */
	public function diagnostics($return=false) {
		# $content;
		if(!is_null($this->content)) $output['content'] = strlen($this->content)." Characters.";
		else $output['content'] = "NULL";
		# $content_override;
		if(!is_null($this->content_override)) $output['content-override'] = strlen($this->content_override)." Characters<br />";
		else $output['content_override'] = "NULL";
		# $interface_keys = array();
		foreach($this->interface_keys as $key => $file) {
			$output['interface-keys'] .= "<br />  => <span style=\"color:#46A4FA;\">$key</span> : $file";
		}
		# $ui;
		$output['UI'] = $this->ui;
		# $path;
		$output['path'] = $this->path;
		# $default_interface;
		$output['default-interface'] = $this->default_interface;
		# $interface_override;
		$output['interface-override'] = $this->interface_override;
		# $login_window_interface;
		$output['login-window-interface'] = $this->login_window_interface;
		# $interface;
		$output['interface'] = $this->interface;
		# $title_template;
		$output['title-template'] = $this->title_template;
		# $system_title;
		$output['system-title'] = $this->system_title;
		# $app_title;
		$output['application-title'] = $this->app_title;
		# <title>
		$output['title'] = $this->title(true);
		# $tagline;
		$output['tagline'] = $this->tagline;
		# $footer;
		$output['footer'] = str_replace("<", "&lt;", str_replace(">", "&gt;", $this->footer));
		# $description;
		$output['description'] = $this->description;
		# $keywords;
		$output['keywords'] = $this->keywords;
		# $favicon;
		if($this->favicon) $favicon = "TRUE ".$this->favicon;
		else {
			$path = "Resources/UI/{$this->ui}/Images/favicon.";
			if(file_exists($path."png")) $favicon = "TRUE ".$path."png";
			elseif(file_exists($path."ico")) $favicon = "TRUE ".$path."ico";
			elseif(file_exists($path."gif")) $favicon = "TRUE ".$path."gif";
			else $favicon = false;
		} $output['favicon'] = $favicon;
		# $apple_icon;
		if($this->apple_icon) $icon = "TRUE ".$this->apple_icon;
		else {
			$path = "Resources/UI/{$this->ui}/Images/appleicon.png";
			if(file_exists($path)) $icon = "TRUE ".$path;
			else $icon = false;
		} $output['apple-icon'] = $icon;
		# CSS
		$iPath = root."Resources/UI/{$this->ui}/Style/";
		$path = $this->path."Style/";
		if(file_exists($iPath."Base.css"))  $output['base-stylesheet'] = $path."Base.css";
		elseif(file_exists($iPath."Base.php")) $output['base-stylesheet'] = $path."Base.php";
		if($this->override) $interface = $this->interface_override; else $interface = $this->interface;
		if(file_exists($iPath.$this->interface.".css")) $output['interface-stylesheet'] = $path.$interface.".css";
		elseif(file_exists($iPath.$this->interface.".php")) $output['interface-stylesheet'] = $path.$interface.".php";
		else $output['interface-stylesheet'] = null;
		# $override = false;
		$output['override'] = $this->override;
		# $direct_echo = false;
		$output['direct-echo'] = $this->direct_echo;
		# $javascript = array();
		# javascript[head]
		if(!is_null($this->javascript['head'])) $output['javascript-head'] = strlen($this->javascript['head'])." Characters<br />\n".$this->javascript['head'];
		else $output['javascript-head'] = null;
		# javascript[body]
		if(!is_null($this->javascript['body'])) $output['javascript-body'] = strlen($this->javascript['body'])." Characters<br />\n".$this->javascript['body'];
		else $output['javascript-body'] = null;
		# javascript[include_head]
		if(count($this->javascript['include_head']) > 0) {
			$output['javascript-include-head'] = count($this->javascript['include_head'])." Items<br />";
			foreach($this->javascript['include_head'] as $item) {
				$output['javascript-include-head'] .= $item;
			}
		} else $output['javascript-include-head'] = null;
		# javascript[include_body]
		if(count($this->javascript['include_body']) > 0) {
			$output['javascript-include-body'] = count($this->javascript['include_body'])." Items<br />";
			foreach($this->javascript['include_body'] as $item) {
				$output['javascript-include-body'] .= $item;
			}
		} else $output['javascript-include-body'] = null;
		# $notifications = array();
		# notifications[UIError]
		if(count($this->notifications[UIError]) > 0) {
			$output['notification-errors'] = count($this->notifications[UIError])." Errors<br />";
			foreach($this->notifications[UIError] as $item) {
				$output['notification-errors'] .= $item;
			}
		} else $output['notification-errors'] = null;
		# notifications[UINotice]
		if(count($this->notifications[UINotice]) > 0) {
			$output['notification-notices'] = count($this->notifications[UINotice])." Notices<br />";
			foreach($this->notifications[UINotice] as $item) {
				$output['notification-notices'] .= $item;
			}
		} else $output['notification-notices'] = null;
		# $sidebars = array();
		$output['sidebar-count'] = count($this->sidebars);
		$output['sidebar-counter'] = $this->sidebar_counter;
		if(count($this->sidebars) > 0)
		foreach($this->sidebars as $id => $bar) {
			$output['sidebar-'.$id] = "<span style=\"color:#46A4FA;\">[{$bar['title']}]</span><br />{$bar['content']}";
		}
		# $templates = array();
		foreach($this->templates as $name => $template) $output['templates'] .= "<br />  => <span style=\"color:#46A4FA;\">$name</span> : ".html_safe($template);
		# $templates = array();
		if(crunch($this->ui) != 'system') foreach($this->system_templates as $name => $template) $output['system-templates'] .= "<br />  => <span style=\"color:#46A4FA;\">$name</span> : ".html_safe($template);
		
		# Return or Echo
		return diagnostic($output, $return);
	}
	
}

/* Shortcuts */
function add($string) {
	Registry::fetch('Interface')->content .= $string;
}

function path($return=false) {
	if($return) return Registry::fetch('Interface')->path;
	else echo Registry::fetch('Interface')->path;
}