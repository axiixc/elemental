<?php # Home [axiixc] : Application Resources

class Home {
	
	public function __construct() {
		if(Conf::is_set("Homepage Source")) { # Write Defaults
			Conf::fullwrite("Homepage Source", "readme", "Homepage Source", false);
			Conf::fullwrite("Homepage Text", null, "Homepage Text", false);
			Log::write("Home::__construct() Could not locate previous run. Wrote defaults to system configuration.");
		}
		if(Conf::read("Homepage Source") == 'readme') {
			add('<pre>'.str_replace('EVERYTHING HERE IS SUBJECT TO CHANGE!!', '<span style="background:red;padding:1px;color:white;">EVERYTHING HERE IS SUBJECT TO CHANGE!!</span>', html_safe(file_get_contents(root."README"))).'</pre>');
		} else {
			add(Conf::read("Homepage Text"));
		}
	}
	
}