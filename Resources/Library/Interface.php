<?php

class UserInterface {
	
	public $content;
	public $content_override;
	public $interface_keys = array();
	public $ui;
	public $default_interface;
	public $interface_override;
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
	public $direct_echo = false;
	private $javascript = array();
	private $notifications = array();
	private $sidebars = array();
	
	public function __construct($ui=null) {
		if(is_null($ui)) $this->ui = Conf::read('UI');
		else $this->ui = $ui;
		include root.'Resources/UI/'.$this->ui.'/Conf.php';
		$this->default_interface = $this->interface = $this->interface_override = $Interface['default_interface'];
		$this->interface_keys = array_merge(import("Interface Keys"), $Interface['interface_keys']);
		$this->title_template = Conf::read("Head Title Format");
		$this->system_title = Conf::read("Title");
		$this->tagline = Conf::read("Tagline");
		$this->footer = Conf::read("Footer");
		$this->description = Conf::read("Description");
		$this->keywords = Conf::read("Keywords");
		$this->favicon = Conf::read("Favicon");
		$this->apple_icon = Conf::read("Apple Icon");
	}
	
	public function title($return=false) {
		if(!is_null($this->title)) {
			$output = str_replace('%a', $this->title, $this->head_template);
			$output = str_replace('%t', $this->system_title, $output);
		} else $output = $this->system_title;
		if($return) return $output;
		else printf("\t<title>%s</title>\n", $output);
	}
	
	public function favicon() {
		if($this->favicon) $path = $this->favicon;
		else {
			$path = "Resources/UI/{$this->ui}/Images/favicon.";
			if(file_exists($path.".png")) $favicon = $path.".png";
			elseif(file_exists($path.".ico")) $favicon = $path.".ico";
			elseif(file_exists($path.".gif")) $favicon = $path.".gif";
			else $favicon = null;
		}
	
		printf('<link rel="icon" href="%s" type="image/x-icon" />', $favicon);
		printf('<link rel="shortcut icon" href="%s" type="image/x-icon" />', $favicon);
	}
	
	public function css() {
		$path = "Resources/UI/{$this->ui}/Style/";
		
		if(file_exists($path."Base.css")) printf('<link rel="stylesheet" type="text/css" href="%s" />', $base."Base.css");
		elseif(file_exists($path."Base.php")) printf('<link rel="stylesheet" type="text/css" href="%s" />', $base."Base.php");
		else Log::write("Interface::css() No Base.(css|php) found.");
		
		if(file_exists($path.$this->interface.".css")) printf('<link rel="stylesheet" type="text/css" href="%s" />', $path.$this->interface.".css");
		elseif(file_exists($path.$this->interface.".php")) printf('<link rel="stylesheet" type="text/css" href="%s" />', $path.$this->interface.".php");
		else Log::write("Interface::css() No $this->interface.(css|php) found.");
	}
	
	public function meta() {
		printf('<meta name="description" content="%s" />', $this->description);
		printf('<meta name="keywords" content="%s" />', $this->keywords);
	}
	
	public function head($b='css', $i='css') {
		$this->title();
		$this->css($b, $i);
		$this->favicon();
		$this->block();
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
		if(count($args) == 0) { # Echo All
			foreach($this->notification[UIError] as $item) printf($this->template("Notification UIError"), $item);
			foreach($this->notifiaction[UINotice] as $item) printf($this->template("Notification UINotice"), $item);
			return true;
		} elseif(count($args) == 1) { # Echo <0type>
			foreach($this->notification[$args[0]] as $item) printf($this->template("Notification {$args[0]}"), $item);
			return true;
		} elseif(count($args) == 2) { # Return <0type> or Set <1string> to <0type>
			if($args[1] == true) { # Return <0type>
				if($args[0] == UINotification) { # Return All
					return $this->notifications;
				} else { # Return <0type>
					return $this->notifications[$args[0]];
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
		if($type == UIError or $type == UINotification) $error_count = count($this->errors);
		else $error_count = 0;

		# Count Notices
		if($type == UINotice or $Type == UINotification) $notice_count = count($this->notices);
		else $notice_count = 0;

		return $error_count + $notice_count;
	}

	public function error($title, $message) {
		Registry::fetch('System')->$override = true;
		$this->interface_override = 'Box';
		$this->content_override = sprintf($this->template("Notification Error"), $title, $message);
	}
	
	public function interface_diagnostics() {
		# $content;
		if(!is_null($this->content)) $output['content'] = strlen($this->content)." Characters<br />\n".$this->content;
		else $output['content'] = "NULL";
		# $content_override;
		if(!is_null($this->content_override)) $output['content-override'] = strlen($this->content_override)." Characters<br />\n".$this->content_override;
		else $output['content_override'] = "NULL";
		# $interface_keys = array();
		foreach($this->interface_keys as $key => $file) {
			$output['interface-keys'] .= "<br />  => <span style=\"color:#46A4FA;\">$key</span> : $file";
		}
		# $ui;
		$output['UI'] = $this->ui;
		# $default_interface;
		$output['default-interface'] = $this->default_interface;
		# $interface_override;
		$output['interface-override'] = $this->interface_override;
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
		if($this->favicon) $path = $this->favicon;
		else {
			$path = "Resources/UI/{$this->ui}/Images/favicon.";
			if(file_exists($path.".png")) $favicon = $path.".png";
			elseif(file_exists($path.".ico")) $favicon = $path.".ico";
			elseif(file_exists($path.".gif")) $favicon = $path.".gif";
			else $favicon = "NULL";
		} $output['favicon'] = $favicon;
		# $apple_icon;
		$output['apple-icon'] = "Unsupported ATM";
		# $direct_echo = false;
		if($this->direct_echo) $output['direct-echo'] = 'TRUE';
		else $output['direct-echo'] = 'FALSE';
		# $javascript = array();
		# javascript[head]
		if(!is_null($this->javascript['head'])) $output['javascript-head'] = strlen($this->javascript['head'])." Characters<br />\n".$this->javascript['head'];
		else $output['javascript-head'] = 'NULL';
		# javascript[body]
		if(!is_null($this->javascript['body'])) $output['javascript-body'] = strlen($this->javascript['body'])." Characters<br />\n".$this->javascript['body'];
		else $output['javascript-body'] = 'NULL';
		# javascript[include_head]
		if(count($this->javascript['include_head']) > 0) {
			$output['javascript-include-head'] = count($this->javascript['include_head'])." Items<br />\n";
			foreach($this->javascript['include_had'] as $item) {
				$output['javascript-include-head'] .= $item;
			}
		} else $output['javascript-include-head'] = 'NULL';
		# javascript[include_body]
		if(count($this->javascript['include_body']) > 0) {
			$output['javascript-include-body'] = count($this->javascript['include_body'])." Items<br />\n";
			foreach($this->javascript['include_body'] as $item) {
				$output['javascript-include-body'] .= $item;
			}
		} else $output['javascript-include-body'] = 'NULL';
		# $notifications = array();
		# notifications[UIError]
		if(count($this->notifications[UIError]) > 0) {
			$output['notification-errors'] = count($this->notifications[UIError])." Errors<br />\n";
			foreach($this->notifications[UIError] as $item) {
				$output['notification-errors'] .= $item;
			}
		} else $output['notification-errors'] = 'NULL';
		# notifications[UINotice]
		if(count($this->notifications[UINotice]) > 0) {
			$output['notification-notices'] = count($this->notifications[UINotice])." Notices<br />\n";
			foreach($this->notifications[UINotice] as $item) {
				$output['notification-notices'] .= $item;
			}
		} else $output['notification-notices'] = 'NULL';
		# $sidebars = array();
		$output['sidebars'] = 'Unsupported ATM';
		
		echo "<pre><table>";
		foreach($output as $name => $value) {
			$value = str_replace('NULL', '<span style="color:yellow">NULL</span>', $value);
			$value = str_replace('TRUE', '<span style="color:green">TRUE</span>', $value);
			$value = str_replace('FALSE', '<span style="color:red">FALSE</span>', $value);
			printf('<span style="color:#2C68C1;">[%s]</span>&nbsp;%s<br />', uncrunch($name), $value);
		}
		echo "</table></pre>";
	}
	
}

function add($string) {
	Registry::fetch('Interface')->content .= $string;
}

?>