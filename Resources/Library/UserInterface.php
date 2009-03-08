<?php # User Interface [ axiixc ] : What runs the show

class UserInterface {
	
	public $content, $content_override, $direct_echo, $interface_keys, $default_interface, $interface, $interface_override, $override; # content
	public $title_template, $system_title, $title, $tagline, $footer, $description, $keywords, $favicon; # meta
	public $javascript_head, $javascript_body, $javascript_include_head, $javascript_include_body, $javascript_onload; # javascript
	private $errors, $notices; # notifications
	private $sidebars, $sidebar_count; # sidebars
	private $templates; # templates
	
	public function __construct() {
		
		# Default interface list (modified in your UI's conf)
		$this->interface_keys = array(
			'1Bar'   => '1Bar.php',
			'2Bar'   => '2Bar.php',
			'3Bar'   => '3Bar.php',
			'Blank'  => 'Blank.php',
			'Box'    => 'Box.php',
			'iPhone' => 'iPhone.php',
			'Mobile' => 'Mobile.php',
			'Print'  => 'Print.php'
		); $this->default_interface = '2Bar';
		
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
		printf('<link rel="icon" href="%s" type="image/x-icon" />', $this->favicon);
		printf('<link rel="shortcut icon" href="%s" type="image/x-icon" />', $this->favicon);
	}
	
	public function css($b='css', $i='css') {
		$path = "Resources/UI/{$this->ui}.ui/Style/";
		printf('<link rel="stylesheet" type="text/css" href="%s.%s" />', $path, $b);
		printf('<link rel="stylesheet" type="text/css" href="%s%s.%s" />', $path, $this->interface, $b);
	}
	
	public function block() {
		printf('<meta name="description" content="%s" />', $this->description);
		printf('<meta name="keywords" content="%s" />', $this->keywords);
	}
	
	public function headBlock($b='css', $i='css') {
		$this->title();
		$this->css($b, $i);
		$this->favicon();
		$this->block();
		$this->javascript();
	}
	
	/* Javascript */
	public function javascriptAdd($js, $head=true) {
		if($head) $this->javascript_head .= $js."\n";
		else $this->javascript_body .= $js."\n";
	}
	
	public function javascriptInclude($file, $head=true) {
		if($head) $this->javascript_include_head[] .= $file;
		else $this->javascript_include_body[] .= $file;
	}
	
	public function javascript($head=true) {
		if($head) {
			if(!is_null($this->javascript_head)) printf('<script type="text/javascript">%s</script>', $this->javascript_head);
			if(count($this->javascript_include_head) > 0)
				foreach($this->javascript_include_head as $file) printf('<script src="%s" type="text/javascript"></script>', $file);
		} else {
			if(!is_null($this->javascript_body)) printf('<script type="text/javascript">%s</script>', $this->javascript_body);
			if(count($this->javascript_include_body) > 0)
				foreach($this->javascript_include_body as $file) printf('<script src="%s" type="text/javascript"></script>', $file);
		}
	}
	
	/* Notifications */
	public function notificationAdd($type, $message) {
		if($type == UIError) {
			$this->$errors[] = $message;
			return true;
		} elseif($type == UINotice) {
			$this->notices[] = $messages;
			return true;
		} else return false;
	}
	
	public function notificationCount($type=UINotification) {
		# Count Errors
		if($type == UIError or $type == UINotification) $error_count = count($this->errors);
		else $error_count = 0;
		
		# Count Notices
		if($type == UINotice or $Type == UINotification) $notice_count = count($this->notices);
		else $notice_count = 0;
		
		return $error_count + $notice_count;
	}
	
	public function notification($type, $return=false) {
		if($type == UIError or $type == UINotification and $this->notificationCount(UIError) > 0)
			foreach($this->errors as $msg) $output .= sprintf('<div class="UIError">%s</div>', $msg);
		if($type == UINotice or $type == UINotification and $this->notificationCount(UINotice) > 0)
			foreach($this->notice as $msg) $output .= sprintf('<div class="UINotice">%s</div>', $msg);
		if($return) return $output; else echo $output;
	}
	
	public function error($title, $message) {
		$this->override = true;
		$this->interface_override = 'Box';
		$this->content_override = sprintf('<h1 class="UIFullscreenError">%s</h1><div class="UIFullscreenError">%s</div>', $title, $message);
	}
	
	/* Menus */
	public function menu($id, $class='UIMenu', $title=null, $array=false, $return=false) {
		$t = mysql_fetch_assoc(EXMySQLQuery("SELECT * FROM `[prefix]menus` WHERE `name` LIKE CONVERT(_utf8 '$id' USING latin1) COLLATE latin1_swedish_ci"));
		$menu = $t['id'];

		$result = EXMySQLQuery("SELECT *  FROM `[prefix]navigation` WHERE `menu` = $menu ORDER BY `rank` ASC");

		if($array) while($nav = mysql_fetch_assoc($result)) { # Return an array
			$output[$nav['id']]['link'] = $nav['link']; 
			$output[$nav['id']]['name'] = $nva['name']; 
		} else { # Create HTML structure
			$output = "<ul class=\"$class\">"; # the opening list tag
			if(!is_null($title)) $output = $output."<h2>$title</h2>";
			while($nav = mysql_fetch_assoc($result)) $output = $output."\t<li><a href=\"{$nav['link']}\">{$nav['name']}</a></li>\n";
			$output = $output.'</ul>'; # closing list tag
		} if($return) return $output; else echo $output;
	}
	
	/* Sidebars */
	public function sidebarWrite($title, $content, $col='main', $id=null) {
		if(!is_null($id) and !is_null($this->sidebars[$id])) { # Edit Mode
			if(!is_null($title)) $this->sidebars[$id]['title'] = $title;
			if(!is_null($content)) $this->sidebars[$id]['content'] = $content;
			if(!is_null($col)) $this->sidebars[$id]['col'] = $col;
		} else { # New Mode
			if(is_null($id)) $id = $this->sidebar_count;
			$this->sidebar_count++;
			if(is_null($col)) $col = UISidebarMain;
			$tmp = array('title' => $title, 'content' => $content, 'col' => $col);
			$this->sidebars[$id] = $tmp;
		} return $id;
	}
	
	public function sidebarDelete($id) {
		unset($this->sidebars[$id]);
	}
	
	public function sidebar($id=true) {
		$template = '<div class="sb_item" id="%s">%s%s</div>';
		if($id != true) {
			$cols_active = explode(',', str_replace(' ', null, $id));
			foreach($this->sidebars as $id => $col)
				if(in_array($col['col'], $cols_active)) $sb_items[$id] = $col;
		} else $sb_items = $this->sidebars;
		if(!is_null($sb_items)) foreach($sb_items as $id => $col) {
			if(!is_null($col['title'])) $hTag = "<h1>{$col['title']}</h1>";
			else $hTag = null;
			$sb_object = $sb_object . sprintf($template, $id, $hTag, $col['content']);
		} echo $sb_object;
	}
	
}

# For sanity's sake
function add($str) { Registry::fetch('UI')->content .= $str; }
function add_js($str, $head=true) { Registry::fetch('UI')->javascriptAdd($str, $body); }
function inc_js($str, $head=true) { Registry::fetch('UI')->javascriptInclude($str, $head); }
function onload($str) { Registry::fetch('UI')->onload .= $str; }
function add_error($message) { Registry::fetch('UI')->notificationAdd(UIError, $message); };
function add_notice($message) { Registry::fetch('UI')->notificationAdd(UINotice, $message); }