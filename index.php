<?php # Index : This is basically a redirect

# THIS IS A KEY RESOURCE (DO NOT MOVE THIS FUNCTION)
function EXScriptDir($input=__FILE__,$HTML=FALSE) { # Version: 1.2
	$output = dirname($input).'/';
	if(!$HTML) return $output;
	else return str_replace(dirname(__FILE__).'/',null,$output);
}

# This includes the engine
include 'Resources/System.php';

?>