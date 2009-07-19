<?php # System Execution Point [axiixc]

if (!isset($index))
{
	die('<b style="color:red">ERROR</b>');
}

define('root', $index);
include root . 'Resources/Configuration/System.php';
define('www', $web_path);
define('invalid', false);

// For exMethod()
define('exMethod', true);

$required_libraries = array('Dictionary', 'Utilities', 'Filesystem', 'Development', 
'Database', 'Configuration', 'System', 'Interface', 'Authority', 'User');
foreach($required_libraries as $library)
{
	require_once root . "Resources/Library/$library.php";
}

crunch($_GET['app']);
crunch($_GET['arg']);

System::Initialize();

$app_list = fsGetApplications();
$app_path = $app_list[System::Application()];
if (file_exists($app_path . 'Application.php'))
{
	
	if (file_exists($app_path . 'Initialize.php'))
	{
		include $app_path . 'Initialize.php';
	}
	
	if (System::Authority()->verified) 
	{
		
		if (file_exists($app_path . 'Info.php'))
		{
			include $app_path . 'Info.php';
			
			if (!is_null($application['name']))
			{
				setTitle($application['name']);
			}
		}
		
		if (file_exists($app_path . 'Resources.php'))
		{
			include $app_path . 'Resources.php';
		}
			
		require_once $app_path . 'Application.php';
		
	}
	else
	{
	   exLog('Authority->verified: false');
	   error('Insufficent Privlidges', 'You do not have the required permission to view this page.');
   }
	
}
else
{
	exLog('Application Load Failed: ' . System::Application());
	error('Application Not Found', 'The requested application bundle was either not found, or invalid.');
}

if (System::InterfaceHandler()->mannagedUI)
{
	include System::InterfaceHandler()->interfaceFile();
}

echo Development::logDump();

System::Terminate();