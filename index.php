<?php # Main Index [axiixc]

/* Starting Time */
$mtime = explode(' ', microtime());
print_r($mtime);
$starttime = $mtime[1] + $mtime[0];

/* The Framework */
$index = dirname(__FILE__).'/';
include 'Resources/System.php';

/* Ending Time */
$mtime = explode(' ', microtime());
$endtime = $mtime[1] + $mtime[0];

/* Calculations */
$totaltime = ( $endtime - $starttime );

/* Output */
echo ($__output_create_style == 'console') ? $totaltime : "<!-- $totaltime -->";

?>