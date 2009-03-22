<?php # Dictionary [ axiixc ] : System definitions

# System Version
define('EXSystemVersion', 0.3);

# System Definitions
define('UIError', 'UIError');
define('UINotice', 'UINotice');
define('UINotification', 'UINotification');
define('UISidebarMain', 'UISidebarMain');
define('UISidebarOther','UISidebarOther');
define('override', 'override');
define('nil', root.'Resources/nil');

# Filesystem
define('lc_filename', 'lc_filename');
define('lc_basename', 'lc_basename');
define('uc_filename', 'uc_filename');
define('uc_basename', 'uc_basename');
define('filename',    'filename');
define('basename',    'basename');
define('position',    'position');
define('path',        'path');
define('full_path',   'full_path');

# Date
define('EXDateSDF_Day', 'Ymd');
define('EXDateSDF_Hour', 'YmdH');
define('EXDateSDF_Minute', 'YmdHi');

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
define('server_ip', $_SERVER['SERVER_ADDR']);

# User Authentication
define('UATypeBanned', 'UATypeBanned');
define('UATypeGuest', 'UATypeGuest');
define('UATypeBasic', 'UATypeBasic');
define('UATypeAdmin', 'UATypeAdmin');

# Multilayer Data Storage [for storing multidimensional arrays in strings]
# These are not escaped, please don't try to fold arrays with the symbols
# in them, they will fail to unfold correctly.... That may be a security
# hole. MUST FIX MUST FIX MUST FIX MUST FIX MUST FIX MUST FIX MUST FIX!!
define('MLDF', '[%@%]');
define('MLDS', '[%#%]');