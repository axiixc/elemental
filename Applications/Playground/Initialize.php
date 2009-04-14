<?php # Sandbox Initialization

/* NOTE: This does not check for administrative users. Only that you are in development mode.
         If you have this running on a public server and are in development mode then you
         misunderstood development mode. USE WITH EXTREME CAUTION!!!
*/

if(!Conf::read('Development Mode')) {
	Registry::fetch('Interface')->error('Permission Denied', 'You do not have permission to view this page.');
	Registry::fetch('UAuth')->verification = false;
}