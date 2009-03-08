<?php # Dictionary [ axiixc ] : System definitions

# System Version
define('EXSystemVersion', 0.2);

# System Definitions
define('UIError',        'UINotificationWithTypeError');
define('UINotice',       'UINotificationWithTypeNotice');
define('UINotification', 'UINotificationWithGenericType');
define('UISidebarMain',  'UISidebarMain');
define('UISidebarOther', 'UISidebarOther');

# Time
define('EXDateSortableDateFormat', 'Ymd'); # Depreciated
define('EXDateSDF_YMD',     'Ymd');
define('EXDateSDF_YMDH',    'YmdH');
define('EXDateSDF_YMDHM',   'YmdHi');
define('EXDateSDF_YMDHMS',  'YmdHis');
define('EXDateSDF_YMDHMSM', 'YmdHisu');

# Time Shortcuts
define('second',  time()+1);
define('minute',  time()+60);
define('hour',    time()+3600);
define('day',     time()+86400);
define('week',    time()+604800);
define('month',   time()+2592000);  # Est to 30 days per month
define('year',    time()+31536000); # Est to 369 days per year (no leap year)
define('destroy', time()-day);

# System Extensions
define('override', 'override', true);
define('root',     $index_path, true);
define('rsc',      root.'Resources/', true);
define('lib',      rsc.'Library/', true);
define('nil',      rsc.'nil', true);

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

# Client Information
define('client_ip', $_SERVER['REMOTE_ADDR']);
define('server_ip', $_SERVER['SERVER_ADDR']);

# User Authentication Types
define('UATypeBanned',  'UATypeBanned');
define('UATypeGuest',   'UATypeGuest' );
define('UATypeBasic',   'UATypeBasic' );
define('UATypeAdmin',   'UATypeAdmin' );
define('UALogin',       'UALogin');
define('UAApplication', 'UAApplication');

# Multilayer Data Storage [for storing multidimensional arrays in strings]
define('MLDF', '[%@%]');
define('MLDS', '[%#%]');

# User Definitions