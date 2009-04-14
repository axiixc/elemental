<?php # Dictionary [ axiixc ] : System definitions

# System Version
define('EXSystemVersion', 0.4);
define('EXSystemBuild', 0004);

# System Definitions
define('UIError', 'UIError');
define('UINotice', 'UINotice');
define('UINotification', 'UINotification');
define('UISidebarMain', 'UISidebarMain'); # Is this even used?
define('UISidebarOther','UISidebarOther'); # '''
define('override', 'override');
define('nil', root.'Resources/nil'); # Not null, this points to a file

# Filesystem
define('lc_filename', 'lc_filename'); # Lower Case Filename
define('lc_basename', 'lc_basename'); # Lower Case Basename
define('uc_filename', 'uc_filename'); # Upper Case Filename
define('uc_basename', 'uc_basename'); # Upper Case Filename
define('filename',    'filename');
define('basename',    'basename');
define('position',    'position');
define('path',        'path');
define('full_path',   'full_path');

# Date
define('EXDateSDF_Day', 'Ymd'); # DEPRICATED
define('EXDateSDF_Hour', 'YmdH'); # DEPRICATED
define('EXDateSDF_Minute', 'YmdHi'); # DEPRICATED
define('DateSDF_YMD', EXDateSDF_Day);
define('DateSDF_YMDH', EXDateSDF_Hour);
define('DateSDF_YMDHM', EXDateSDF_Minute);


# Time Shortcuts
define('second',  time()+1);
define('minute',  time()+60);
define('hour',    time()+3600);
define('day',     time()+86400);
define('week',    time()+604800);
define('month',   time()+2592000);  # Est to 30 days per month
define('year',    time()+31536000); # Est to 369 days per year (no leap year)
define('destroy', time()-day);

# Client Information
define('client_ip', $_SERVER['REMOTE_ADDR']);
if($_SERVER['SERVER_ADDR'] = '::1') define('server_ip', '127.0.0.1');
else define('server_ip', $_SERVER['SERVER_ADDR']);

# User Authentication
define('UATypeBanned', 'UATypeBanned');
define('UATypeGuest', 'UATypeGuest');
define('UATypeBasic', 'UATypeBasic');
define('UATypeAdmin', 'UATypeAdmin');

# System Resources
$system['applications'] = array('api', 'preferences', 'system', 'user');
$system['package'] = array('developers', 'system');
$system['ui'] = array('system');
$system['preferences'] = array('api', 'system', 'user', 'site');