<?php # TineyMCE wrapper [axiixc]


class TinyMCE {
	
	public $theme;
	public $mode;
	public $plugins = array();
	public $conf = array();
	
	public function __construct() {
		$this->theme = 'advanced';
		$this->mode = 'textareas';
		$this->plugins = array('safari','pagebreak','style','layer','table','save','advhr','advimage','advlink','emotions','iespell','insertdatetime','preview','media','searchreplace','print','contextmenu','paste','directionality','fullscreen','noneditable','visualchars','nonbreaking','xhtmlxtras','template','inlinepopups');
		
		# Conf Values
		# Theme Button Bars
		$this->conf['theme-options']['buttons'][0] = null;
		$this->conf['theme-options']['buttons'][1] = 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect';
		$this->conf['theme-options']['buttons'][2] = 'bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor';
		$this->conf['theme-options']['buttons'][3] = 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,ltr,rtl,';
		
		# Theme Item Layout
		$this->conf['theme-options']['toolbar-location'] = 'top';
		$this->conf['theme-options']['toolbar-align'] = 'left';
		$this->conf['theme-options']['statusbar-location'] = 'bottom';
		$this->conf['theme-options']['resizing'] = 'true';
		
		# CSS
		$this->conf['css']['content'] = 'lists/word.css';
		
		# Dialog JavaScripts
		$this->conf['javascript']['template-external-list-url'] = 'lists/template_list.js';
		$this->conf['javascript']['external-link-list-url'] = 'lists/link_list.js';
		$this->conf['javascript']['external-image-list-url'] = 'lists/image_list.js';
		$this->conf['javascript']['media-external-list-url'] = 'lists/media_list.js';
		
		if((is_null(template('TinyMCE Options')) or count(template('TinyMCE Options') > 0)) and is_array(template('TinyMCE Options'))) {
			$temp_one = $this->conf;
			$temp_two = template('TinyMCE Options');
			$this->conf = array();
			$this->conf = array_merge($temp_one, $temp_two);
		}
	}
	
	/* ADD ACCESSOR FUNCTIONS HERE */
	
	public function commit() {
		# Include the base of TinyMCE
		$x .= '<script type="text/javascript" src="'.Conf::read('WWW Path').'Resources/Packages/TinyMCE/Resources/tiny_mce.js"></script>';
		# Do the setup
		$x .= '<script type="text/javascript">';
		$x .= 'tinyMCE.init({';
		# Basic Options
		$x .= "mode : '$this->mode',";
		$x .= "theme : '$this->theme',";
		$x .= 'plugins : "'.implode(',', $this->plugins).'",';
		# Theme Options
		foreach($this->conf['theme-options']['buttons'] as $id => $row) {
			if($row != null and !is_null($row)) {
				$x .= "theme_advanced_buttons$id : '$row',";
			}
		}
		$x .= "theme_advanced_toolbar_location : '{$this->conf['theme-options']['toolbar-location']}',";
		$x .= "theme_advanced_toolbar_align : '{$this->conf['theme-options']['toolbar-align']}',";
		$x .= "theme_advanced_statusbar_location : '{$this->conf['theme-options']['statusbar-location']}',";
		if(!is_string($this->conf['theme-options']['resizing'])) {
			$this->conf['theme-options']['resizing'] = ($this->conf['theme-options']['resizing'] === true) ? true : false ;
		}
		$x .= "theme_advanced_resizing : '{$this->conf['theme-options']['resizing']}',";
		# CSS
		$x .= "content_css : '{$this->conf['css']['content']}',";
		# Dialogs
		$x .= "template_external_list_url : '{$this->conf['javascript']['template-external-list-url']}',";
		$x .= "external_link_list_url : '{$this->conf['javascript']['external-link-list-url']}',";
		$x .= "external_image_list_url : '{$this->conf['javascript']['external-image-list-url']}',";
		$x .= "media_external_list_url : '{$this->conf['javascript']['media-external-list-url']}',";
		# Final Bit
		$x .= '});</script>';
		
		# Add to top of UI Content Region
		Registry::fetch('Interface')->content = $x . Registry::fetch('Interface')->content;
	}
	
}

$tinyMCE = new TinyMCE();

?>