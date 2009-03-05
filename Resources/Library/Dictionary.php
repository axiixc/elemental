<?php # Dictionary : System dictionary entries

# System Definitions
define('UIError',        'UINotificationWithTypeError');
define('UINotice',       'UINotificationWithTypeNotice');
define('UINotification', 'UINotificationWithGenericType');

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
define('EXNullAsString', 'EXNullData');
define('override',       'EXElementHasBeenOverriden', true);
define('yes',            'yes', true);
define('no',             'no', true);
define('root',           EXScriptDir(), true);
define('rsc',            root.'Resources/', true);
define('lib',            rsc.'Library/', true);
define('nil',            rsc.'nil', true);

# Filesystem
define('lower_case_filename', 'lower_case_filename');
define('lower_case_basename', 'lower_case_basename');
define('upper_case_filename', 'upper_case_filename');
define('upper_case_basename', 'upper_case_basename');
define('filename',            'filename');
define('basename',            'basename');
define('position_in_array',   'position_in_array');
define('path',                'path');
define('full_path',           'full_path');

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
define('MLDS1', '[%1%]'); define('MLDS2', '[%2%]'); define('MLDS3', '[%3%]');
define('MLDS4', '[%4%]'); define('MLDS5', '[%5%]'); define('MLDS6', '[%6%]');
define('MLDS7', '[%7%]'); define('MLDS8', '[%8%]'); define('MLDS9', '[%9%]');

# User Definitions