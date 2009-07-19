<?php

srand(time());

function mTimeGet()
{
	$mtime = explode(' ', microtime());
	return $mtime[1] + $mtime[0];
}

$starttime = mTimeGet();

/* The Framework */
$index = dirname(__FILE__).'/';
require_once $index . 'Resources/Core.php';

echo '<!-- ' . (mTimeGet() - $starttime) . ' -->';

?>