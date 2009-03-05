<?php

function UIJavascriptAdd($code) { 
	global $system; 
	$system['UI']['javascript'] = $system['UI']['javascript'].$code."\n"; 
}

function UIJavascriptInclude($file) { 
	global $system; 
	$system['UI']['javascript-inc'][] = $file; 
}

function UIJavascript() {
	global $system;
	if(isset($system['UI']['javascript'])) 
		echo "<script type=\"text/javascript\">{$system['UI']['javascript']}</script>";
	if(isset($system['UI']['javascript-inc'])) 
		foreach($system['UI']['javascript-inc'] as $file) 
			echo "<script type=\"text/javascript\" src=\"$file\"></script>\n";
}

function UIJavascriptOnloadAdd($onload) {
	global $system;
	if($system['UI']['javascript-onload'] == null or !isset($system['UI']['javascript-onload'])) $system['UI']['javascript-onload'] = $onload;
	else $system['UI']['javascript-onload'] = $system['UI']['javascript-onload'].';'.$onload;
}

function UIJavascriptOnload() {
	global $system;
	if($system['UI']['javascript-onload'] != null or isset($system['UI']['javascript-onload'])) 
		echo " onload=\"{$system['UI']['javascript-onload']}\"";
}