<?php # Home [axiixc] : Application Resources

class Home {
	
	public function __construct() {
		if(Conf::isset("Homepage Source")) { # Write Defaults
			Conf::fullwrite("Homepage Source", "readme", "Homepage Source", false);
			Conf::fullwrite("Homepage Text", null, "Homepage Text", false);
			Log::write("Home::__construct() Could not locate previous run. Wrote defaults to system configuration.");
		} else {
			if(Conf::read("Homepage Source") == 'readme') {
				add('<pre style="font-size:15px">'.html_safe(file_get_contents(root."README")).'</pre>');
			} else {
				add(Conf::read("Homepage Text"));
			}
		}
	}
	
}