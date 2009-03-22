<?php # Main System Execution Script [axiixc]

# Index check
if(isset($index)) define('root', $index);
else die('$index not found, be sure you are executing from index.php');

# Include the Library
include 'Library/Dictionary.php';
$required_libraries = array('Registry', 'System', 'Log', 'BaseUtilities', 'MySQL', 'Conf', 'UserAuthentication', 'ManagedData');
foreach($required_libraries as $library) require "Library/$library.php";

# The system resource package
include package('System');

# Setup the User Authentication
Registry::register('UAuth', new UserAuthentication);
Registry::fetch('UAuth')->awake();
Registry::fetch('UAuth')->log_msgs();

# Load the Application

# Write output

echo '<html><head><title>Woot</title></head><body style="background:black;color:white;">';
Log::read();
echo '</body></html>';