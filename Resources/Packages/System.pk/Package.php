<?php

function EXFetchResource($id) {
	include sprintf('%s/Resources/%s.php', dirname(__FILE__), $id);
	if($resource_type == 'array') return EXUnfold($resource);
	else return $resource;
}

function EXRegisterResource($id, $contents) {
	$template = "<?php # %s : System Resource\n\n\$resource_type = '%s';\n\$resource = '%s';\n\n?>";
	if(is_array($contents)) {
		$resource_type = 'array';
		$resource = EXFold($contents);
	} else { # string
		$resource_type = 'string';
		$resource = $contents;
	} $write = sprintf($template, $id, $resource_type, $resource);
	FSWrite(sprintf('%s/Resources/%s.php', dirname(__FILE__), $id), $write);
	unset ($template, $resource_type, $resource, $id);
}