<?php # User Interface Notification Library : Functions

# Just give a once over eventually

# Clarification
$system['UI'][UIError] = array();
$system['UI'][UINotice] = array();

function UINotificationAdd($type, $message) { global $system; if($type == UIError or $type == UINotice) $system['UI'][$type][] = $message; }

function UINotificationCount($type) {
	global $system;
	if($type == UIError or $type == UINotification) {
		if(isset($system['UI'][UIError]) and $system['UI'][UIError][0] != null) $countError = count($system['UI'][UIError]);
		else $countError = 0;
	} if($type == UINotice or $type == UINotification) {
		if(isset($system['UI'][UINotice]) and $system['UI'][UINotice][0] != null) $countNotice = count($system['UI'][UINotice]);
		else $countNotice = 0;
	} return $countError + $countNotice;
}

function UINotification($type, $return=false) {
	global $system; $i = null;
	$error_count = (isset($system['UI'][UIError]) and $system['UI'][UIError][0] != null) ? count($system['UI'][UIError]) : 0 ;
	$notice_count = (isset($system['UI'][UINotice]) and $system['UI'][UINotice][0] != null) ? count($system['UI'][UINotice]) : 0 ;
	
	if($type == UIError and $error_count == 0) foreach($system['UI'][UIError] as $elem) $i = $i."<div class=\"UIError\">$elem</div>";
	if($type == UINotice and $notice_count == 0) foreach($system['UI'][UINotice] as $elem) $i = $i."<div class=\"UINotice\">$elem</div>";
	if($type == UINotification) {
		if($error_count == 0) foreach($system['UI'][UIError] as $elem) $i = $i."<div class=\"UIError\">$elem</div>";
		if($notice_count == 0) foreach($system['UI'][UINotice] as $elem) $i = $i."<div class=\"UINotice\">$elem</div>";
	} if($return) return $i; else echo $i;
}

# Fullscreen, huge override, error message
function UIError($title, $message) {
	global $system;
	UIInterface('Box');
	UIAdd(sprintf('<h1 class="UIError">%s</h1><div class="UIError">%s</div>', $title, $message));
}