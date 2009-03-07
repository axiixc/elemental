<?php

function UIJavascriptAdd($code, $head=true) { 
	global $system;
	if($head) $system['UI']['javascript-head'] = $system['UI']['javascript-head'].$code."\n"; 
	else $system['UI']['javascript-body'] = $system['UI']['javascript-body'].$code."\n";
}

function UIJavascriptInclude($file, $head=true) { 
	global $system; 
	if($head) $system['UI']['javascript-inc-head'][] = $file; 
	else $system['UI']['javascript-inc-body'][] = $file;
}

function UIJavascript($head=true) {
	global $system;
	if($head) {
		if(isset($system['UI']['javascript-head'])) 
			echo "<script type=\"text/javascript\">{$system['UI']['javascript-head']}</script>";
		if(isset($system['UI']['javascript-inc-head'])) 
		foreach($system['UI']['javascript-inc-head'] as $file) 
		echo "<script type=\"text/javascript\" src=\"$file\"></script>\n";
	} else {
		if(isset($system['UI']['javascript-body'])) 
			echo "<script type=\"text/javascript\">{$system['UI']['javascript-body']}</script>";
		if(isset($system['UI']['javascript-inc-body'])) 
		foreach($system['UI']['javascript-inc-body'] as $file) 
		echo "<script type=\"text/javascript\" src=\"$file\"></script>\n";
	}
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