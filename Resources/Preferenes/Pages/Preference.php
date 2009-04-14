<?php # Pages Preference [axiixc]

define('IS_OK_TO_RUN', true);
$action_path = root.'Resources/Preferences/Pages/Actions/';
$actions = array(
	'main' => 'Main.php',
	'new' => 'New.php',
	'edit' => 'Edit.php',
	'Delete' => 'Delete.php'
);

if(crunch($_GET['section']) == 'main' or is_null($_GET['section'])) include $action_path.$actions['main'];
elseif(crunch($_GET['section']) == 'new') include $action_path.$actions['new'];
elseif(crunch($_GET['section']) == 'edit') include $action_path.$actions['edit'];
elseif(crunch($_GET['section']) == 'delete') include $action_path.$actions['delete'];
else include $action_path.$actions['main'];