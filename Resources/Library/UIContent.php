<?php # UI Content Library : Environment and Functions

# WTF why doesn't UIContent Func Work???

$system['UI']['content'] = null;
function UIContentAdd($content) { global $system; echo $content; }
function UIAdd($str) { UIContentAdd($str); } # Shortcut to UIContentAdd();
function UIContentRead() { global $system; return $system['UI']['content']; }
function UIContent() { global $system; echo $system['UI']['content']; }

$system['UI']['direct-echo'] = false;
function UIDirectEcho($active=null) { 
	global $system; 
	if($active == null) return $system['UI']['direct-echo']; 
	else $system['UI']['direct-echo'] = $active; 
}