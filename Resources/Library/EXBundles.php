<?php

function EXApplication($item) {
	$app = root."Applications/$item.app/Reference.php";
	if(file_exists($app)) return $app;
	else return nil;
}

function EXPackage($item) {
	$pk = rsc."Packages/$item.pk/Package.php";
	if(file_exists($pk)) return $pk;
	else return nil;
}

function EXLibrary($item) {
	$lib = rsc."Library/$item.php";
	if(file_exists($lib)) return $lib;
	else return nil;
}