<?php # Constants Dictionary [axiixc]

/* Elemental */
define('__exl_version_numeric', 0.4);
define('__ex_version_textual', 'Hydrogen');
define('__ex_version_build', 'H,0009');
define('__ex_copyright_year', 2008);
define('__ex_php_version', '5.2.8');
define('__ex_php_tested', '5.2.8');

/* Data Types */
define('bool', 'EXDataTypeBoolean', true);
define('string', 'EXDataTypeString', true);
define('integer', 'EXDataTypeNumberInteger', true);
define('float', 'EXDataTypeNumberFloat', true);
define('array', 'EXDataTypeArray', true);
define('id', 'EXDataTypeID', true);
define('void', 'EXDataTypeVoid', true);
define('object', 'EXDataTypeObject', true);
define('dictionary', 'EXDataTypeDictionary');
define('rgb', 'EXDataTypeColorRGB', true);
define('hex', 'EXDataTypeColorHEX', true);

/* Filesystem */
define('crunch', 'crunch');
define('lc_filename', 'lc_filename');
define('lc_basename', 'lc_basename');
define('uc_filename', 'uc_filename');
define('uc_basename', 'uc_basename');
define('filename', 'filename');   
define('basename', 'basename');   
define('position', 'position');
define('path', 'path');
define('full_path', 'full_path');

/* Date */
define('SDFDay', 'Ymd');
define('SDFHour', 'YmdH');
define('SDFMinute', 'YmdHi');

/* Predefined Time */
define('second', time()+1, true);
define('minute', time()+60, true);
define('hour', time()+3600, true);
define('day', time()+86400, true);
define('week', time()+604800, true);
define('month', time()+2592000, true);
define('year', time()+31536000, true);
define('destroy', time()-day, true);

/* System */
define('override', 'EXOverrideState', true);
define('failure', 'EXFailureState', true);
define('persistent', 'EXPersistentState', true);
define('append', 'EXAppendState', true);
define('nil', root.'Resources/nil', true);

/* User Interface */
define('notification', 'UINotification', true);
define('error', 'UINotificationError', true);
define('notice', 'UINotificationNotice', true);
define('head', 'UIDataInHead', true);
define('onload', 'UIDataInOnload', true);
define('body', 'UIDataInBodyAtTop', true);
define('body_top', body, true);
define('body_bottom', 'UIDataInBodyAtBottom', true);

/* User Authentication */
define('UAAdmin', 'UATypeAdmin', true);
define('UABasic', 'UATypeBasic', true);
define('UAGuest', 'UATypeGuest', true);
define('UABanned', 'UATypeBanned', true);

/* IP Address Information */
define('client_ip', ($_SERVER['REMOTE_ADDR'] == '::1') ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'], true);
define('server_ip', ($_SERVER['SERVER_ADDR'] == '::1') ? '127.0.0.1' : $_SERVER['SERVER_ADDR'], true);

/* System Resources */
$system['applications'] = array('api', 'preferences', 'system', 'user');
$system['package'] = array('developer_signatures', 'system_resources');
$system['ui'] = array('system');
$system['preferences'] = array('api', 'system', 'user', 'site');