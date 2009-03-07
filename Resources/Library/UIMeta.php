<?php # UI Meta Library : Environment and Functions

# Load defaults
$system['UI']['system-title'] = EXConfRead('system-title');
$system['UI']['system-tagline'] = EXConfRead('system-tagline');
$system['UI']['system-footer'] = EXConfRead('system-footer');

# Favicon loader
$favicon = EXConfRead('custom-favicon');
if($favicon != 'system-default') $system['UI']['favicon'] = $favicon;
else $favicon = 'Resources/UI/System.ui/favicon.png';

function UIMetaTitle($x=true) { global $system; if($x) echo $system['UI']['system-title']; else return $system['UI']['system-title']; }
function UIMetaTagline($x=true) { global $system; if($x) echo $system['UI']['system-tagline']; else return $system['UI']['system-tagline']; }
function UIMetaFooter($x=true) { global $system; if($x) echo $system['UI']['system-footer']; else return $system['UI']['system-footer']; }
function UIMetaFaviconPath($x=true) { global $system; if($x) echo $system['UI']['favicon']; else return $system['UI']['favicon']; }

function UIMetaFaviconHTML() {
	global $system; $favicon = $system['UI']['favicon'];
	echo "\t<link rel=\"icon\" href=\"$favicon\" type=\"image/x-icon\" />\n";
	echo "\t<link rel=\"shortcut icon\" href=\"$favicon\" type=\"image/x-icon\" />\n";
}

function UIMetaHeadTitle($title=null, $return=false) {
	global $system;
	if(is_null($title)) {
		if(!isset($system['UI']['title']) or $system['UI']['title'] == null) $output = EXConfRead('system-title');
		else {
			$title = EXConfRead('system-head-title');
			$title = str_replace('%t',EXConfRead('system-title'),$title);
			$output = str_replace('%a',$system['UI']['title'],$title);
		}
	} else $output = $title; 
	if($return) return $output; else echo "\t<title>$output</title>\n";
}

function UIMetaCSS($b='css', $i='css') {
	$ui = 'Resources/UI/'.EXSystemUI().'.ui/';
	$interface = substr(UIInterface(), 0, -4);
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".ui."Style/Base.$b\" />";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".ui."Style/$interface.$i\" />";
}

function UIMetaBlock() {
	$desc = EXHTMLSafe(EXConfRead('system-description'));
	$keywords = EXHTMLSafe(EXConfRead('system-keywords'));
	echo "\t<meta name=\"description\" content=\"$desc\">\n";
	echo "\t<meta name=\"keywords\" content=\"$keywords\">\n";
}

function UIMetaHeadBlock($title=null, $return=false, $b='css', $i='css') { 
	UIMetaHeadTitle($title, $return); 
	UIMetaCSS($b, $i);
	UIMetaBlock(); 
	UIMetaFaviconHTML(); 
	UIJavascript(); 
}