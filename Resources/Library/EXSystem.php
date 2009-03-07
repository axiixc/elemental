<?php # System Library : System Functions and Setup

# Set values
$system['default-ui'] = EXConfRead('default-ui');
if(isset($_GET['app'])) $system['default-app'] = strtolower($_GET['app']);
else $system['default-app'] = strtolower(EXConfRead('default-application'));

# Define Functions
function EXSystemUI($ui=false) { 
	global $system; 
	if($ui) $system['default-ui'] = $ui; 
	else return $system['default-ui']; 
}

function EXApp() { global $system; return strtolower($system['default-app']); }
function EXDefaultApp() { return EXConfRead('default-application'); }
function EXDebugMode() { return EXConfRead('mode-debug'); }
function EXDevMode() { return EXConfRead('mode-development'); }