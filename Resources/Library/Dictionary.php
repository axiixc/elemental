<?php # A ton of define()s [axiixc]

/* Elemental Info */
define('exSystemVersion', 0.4);
define('exSystemBuild', '0004');

/* Development */
define('exLogMarker', '=====================================================================');

/* Interface */
define('error', 'uiNotificationTypeError', true);
define('notice', 'uiNotificationTypeNotice', true);
define('notification', 'uiNotification', true);
define('normal', 'uiStateNormal', true);
define('override', 'uiStateOverride', true);
define('login', 'uiStateLogin', true);

/* Filesystem */
define('lc_filename', 'lc_filename');
define('lc_basename', 'lc_basename');
define('uc_filename', 'uc_filename');
define('uc_basename', 'uc_basename');
define('filename', 'filename');
define('basename', 'basename');
define('position', 'position');
define('path', 'path');
define('full_path', 'full_path');

/* Date and Time */
define('sdfDay', 'Ymd');
define('sdfHour', 'YmdH');
define('sdfMinute', 'YmdHi');
define('second', time()+1);
define('minute', time()+60);
define('hour', time()+3600);
define('day', time()+86400);
define('week', time()+604800);
define('month', time()+2592000);
define('year', time()+31536000);
define('destroy', time()-day);

/* Client Information */
if ($_SERVER['REMOTE_ADDR'] == '::1')
{
   define('client_ip', '127.0.0.1');
}
else
{
   define('client_ip', $_SERVER['REMOTE_ADDR']);
}

if ($_SERVER['SERVER_ADDR'] == '::1')
{
	define('server_ip', '127.0.0.1');
}
else
{
	define('server_ip', $_SERVER['SERVER_ADDR']);
}

/* Authority */
define('auBanned', 'AUTypeBanned', true);
define('auGuest', 'AUTypeGuest', true);
define('auBasic', 'AUTypeBasic', true);
define('auAdmin', 'AUTypeAdmin', true);