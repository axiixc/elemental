<?php # System UI : Configuration

# Custom $UIInterfaceKeys
$UIInterfaceKeys = array(
	'3Bar' => '2Bar.php',
	'iPhone' => '2Bar.php',
	'Mobile' => '2Bar.php',
	'Print' => '2Bar.php'
);

# Defaults
$system['UI']['default-interface'] = $system['UI']['interface'] = $UIInterfaceKeys['3Bar'];
$system['UI']['favicon'] = 'Resources/UI/BriteNite.ui/favicon.png';
$system['UI']['login-window'] = $UIInterfaceKeys['1Bar'];

# Color Ref (for css);
$green_vivid = '#8ACE2B';
$green_dull  = '#172309';

$blue_vivid  = '#B6DFF5';
$blue_dull   = '#0C1C28';

$pink_vivid  = '#E50086';
$pink_dull   = '#330031';