<?php # UI Interface Library : Environment and Functions

# Keys and environment vars
$UIInterfaceKeys = array(
	'1Bar'   => '1Bar.php',
	'2Bar'   => '2Bar.php',
	'3Bar'   => '3Bar.php',
	'Blank'  => 'Blank.php',
	'Box'    => 'Box.php',
	'iPhone' => 'iPhone.php',
	'Mobile' => 'Mobile.php',
	'Print'  => 'Print.php'
); $system['UI']['interface'] = $UIInterfaceKeys['2Bar'];
# Allow customization of ^

# Functions
function UICustomInterface($interface=false) { 
	global $system, $UIInterfaceKeys; 
	if($interface) $system['UI']['interface'] = $UIInterfaceKeys[$interface]; 
	else return $system['UI']['interface']; 
}

function UIDefaultInterface() { global $system; return $system['UI']['default-interface']; }
function UIInterface() { global $system; return $system['UI']['interface']; }