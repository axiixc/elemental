<?php

/* Timer */
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$index = dirname(__FILE__).'/';
include 'Resources/System.php';

/* Counter */
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
echo "<!-- ". ($endtime - $starttime) . " -->"; 

?>