<?php # Error Library : Error setup and output
# TODO add language support

# Generic Errors
$error['Unsupported'] = 'Unsupported';
$error['Out of Service'] = 'Out of Service';
$error['Permission Denied'] = 'Permission Denied';
$error['Illegal Operation'] = 'Illegal Operation';

# Geek:
foreach($error as $id => $code) $ErrorCode[$id] = sprintf('<span style="font-family:monospace;">ERROR: 0x%06s %s', $id, $code);
# People: foreach($TMPErrorCode as $id => $code) $ErrorCode[$id] = sprintf('Sorry but an error occured (Error ID: %s, %s)', $id, $code);
# Blunt:  foreach($TMPErrorCode as $id => $code) $ErrorCode[$id] = sprintf('%s:%s', $id, $code);

function EXError($id) { global $ErrorCode; return $ErroCode[$id]; }