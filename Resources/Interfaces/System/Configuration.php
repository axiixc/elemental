<?php # System Interface Configuration [axiixc]

// (required)
$interface_configuration['interface'] = '1Bar';
$interface_configuration['interface_override'] = '1Bar';
$interface_configuration['login_window'] = 'Box';
$interface_configuration['interface_keys'] = array(
   '1Bar'   => '1Bar',
   '2Bar'   => '1Bar',
   '3Bar'   => '1Bar',
   'Box'    => 'Box',
   'Blank'  => 'Blank',
   'Mobile' => '1Bar',
   'iPhone' => '1Bar',
   'Print'  => '1Bar'
);

// (optional) The follow are mapped to /Resources/Interfaces/<INTERFACE>/Images/<PATH>
$interface_configuration['favicon'] = 'Branding/Favicon.png';
$interface_configuration['iphoneicon'] = 'Branding/iPhone_Icon.png';

$interface_configuration['avatar_small'] = 'Avatar/Small.png';
$interface_configuration['avatar_medium'] = 'Avatar/Medium.png';
$interface_configuration['avatar_large'] = 'Avatar/Large.png';

// (optional) Author credits
$_author['name'] = 'James Savage';
$_author['handle'] = 'axiixc';
$_author['web'] = 'http://axiixc.com';
$_author['web_ui'] = 'http://axiixc.com';
$_author['email'] = 'axiixc@gmail.com';

$interface_configuration['author'] = $_author;