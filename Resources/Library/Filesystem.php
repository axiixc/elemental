<?php # Filesystem Library [ axiixc ] : Basic Actions

/* Directory Specific Functions */
function fsDirRead($path, $full=true, $keyformat=null, $post='/')
{
	if (file_exists($path))
	{
		$temp = scandir($path);
		foreach($temp as $file)
		{
			if (!in_array(strtolower($file), array('.', '..', '.ds_store')))
			{
				if ($keyformat == lc_filename)
				{
					$key = strtolower(filename($file));
				}
				else if ($keyformat == lc_basename)
				{
					$key = strtolower($file);
				}
				else if ($keyformat == uc_filename) 
				{
					$key = strtolower(filename($file));
				}
				else if ($keyformat == uc_basename)
				{
					$key = strtoupper($file);
				}
				else if ($keyformat == filename)
				{
					$key = filename($file);
				}
				else if ($keyformat == basename)
				{
					$key = $file;
				}
				else if ($keyformat == position_in_array)
				{
					$key = count($output) - 1;
				}
				else if ($keyformat == path)
				{
					$key = $path;
				}
				else if ($keyformat == full_path)
				{
					$key = $path . $file;
				}
				
				if (!$full)
				{
					$output[$key] = $file . $post;
				}
				else
				{
					$output[$key] = $path . '/' . $file . $post;
				}
			}
		}
		return $output;
	}
}

function fsDirMake($path)
{ 
	if (!file_exists($path)) 
	{
		mkdir($path); 
		return true; 
	}
	else
	{
		return false; 
	}
}

/* General Filesystem Functions */
function fsRename($path,$rename)
{
	$name = dirname($path) . '/' . $rename;
	if (!copy($path,$name))
	{
		return false;
	}
	else
	{
		$original_path = $path;
		$handler = opendir($path);
		while (true)
		{
			$item = readdir($handler);
			if ($item == "." or $item == "..")
			{
				continue;
			}
			else if (gettype($item) == "boolean")
			{
				closedir($handler);
				if (!@rmdir($path))
				{
					return false;
				}
				if ($path == $original_path) 
				{
					break;
				}
				$path = substr($path, 0, strrpos($path, "/"));
				$handler = opendir($path);
			}
			else if (is_dir($path."/".$item)) 
			{
				closedir($handler);
				$path = $path."/".$item;
				$handler = opendir($path);
			}
			else
			{
				unlink($path."/".$item);
			}
		}
		return true;
	}
}

function fsMove($path, $to)
{
	return FSRename($path, $to);
}

function fsCopy($path,$to)
{
	return copy($path, $to);
}

function fsDelete($path)
{
	$original_path = $path;
	$handler = opendir($path);
	while (true)
	{
		$item = readdir($handler);
		if ($item == "." or $item == "..")
		{
			continue;
		}
		else if (gettype($item) == "boolean")
		{
			closedir($handler);
			if (!@rmdir($path))
			{
				return false;
			}
			if ($path == $original_path)
			{
				break;
			}
			$path = substr($path, 0, strrpos($path, "/"));
			$handler = opendir($path);
		}
		else if (is_dir($path."/".$item))
		{
			closedir($handler);
			$path = $path."/".$item;
			$handler = opendir($path);
		}
		else
		{
			unlink($path."/".$item);
		}
	}
	return true;
}
/* FSPermissions, FSOwner, FSGroup Unsupported */
function fsPermissions($path, $permissions, $recursive)
{
	return false;
}

function fsOwner($path, $owner, $recursive)
{
	return false;
}

function fsGroup($path, $group, $recursive)
{
	return false;
}

/* File Specific Functions */
function fsRead($path, $username=false, $password=false)
{
	if ($username and $password)
	{
		$auth = "$username@$password:";
		if (substr(ltrim($path), 8, -8) == 'https://')
		{
			$prefix = 'https://'; 
			$suffix = substr($path, 8);
		} 
		elseif (substr(ltrim($path), 7, -7) == 'http://')
		{
			$prefix = 'http://';
			$suffix = substr($path, 7);
		}
		else
		{
			$prefix = null; 
			$auth = null; 
			$suffix = $path;
		} 
		$use_path = $prefix . $auth . $suffix;
	}
	else 
	{
		$use_path = $path;
	}
	return file_get_contents($use_path);
}

function fsEdit($path, $contents, $mode)
{
	$handle = fopen($path, $mode);
	$return = (fwrite($handle, $contents));
	fclose($handle);
	return $return;
}

/* Shortcuts, use if you wish */

function fsWrite($path, $contents)
{
	return fsEdit($path, $contents, w);
}

function fsAppend($path, $contents)
{
	return fsEdit($path, $contents, a);
}

function fsMake($path)
{
	return fsEdit($path, '', w);
}

function fsGetApplications()
{
	return fsDirRead(root . 'Applications', true, lc_filename);
}
function fsGetLibraries()
{
	return fsDirRead(root . 'Resources/Library', true, lc_filename);
}
function fsGetPackages()
{
	return fsDirRead(root . 'Resources/Packages', true, lc_filename);
}
function fsGetInterface()
{
	return fsDirRead(root . 'Resources/Interfaces', true, lc_filename);
}