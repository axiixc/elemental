<?php # Global Utility Functions [axiixc]

function path_safe($path)
{
	$path = preg_replace('/[.]{2,}/',null,$path);
	$path = str_replace('/',null,$path);
	$path = str_replace('\\',null,$path);
	return $path;
}

function mysql_safe($str, $char="\\'\"")
{
	$chars = str_split($char);
	foreach($chars as $i)
	{
		$str = str_replace($i, "\\$i", $str);
	}
	return mysql_escape_string($str);
}

function html_safe($str=null)
{
	return htmlspecialchars($str);
}

function filename($str)
{
	$nfo = pathinfo($str);
	return $nfo['filename'];
}

function crunch(&$string, $edit_local=false)
{
	$x = str_replace(' ', '-', str_replace('_', '-', strtolower($string)));
	
	if (!$edit_local)
	{
		$string = $x;
	}
	return $x;
}

function uncrunch(&$string, $one=' ', $two=' ')
{
	if (is_bool($one))
	{
		$edit_local = $one;
		$expand_to = $two;
	}
	else
	{
		$edit_local = true;
		$expand_to = $one;
	}
	
	$x = ucwords(str_replace('-', $expand_to, $string));
	
	if ($edit_local) 
	{
		$string = $x;
	}
	return $x;
}

function is_even($x)
{
	return (!$x&1);
}

function is_odd($x)
{
	return ($x&1);
}

function is_hex($x)
{
	return (preg_match('^#([0-9A-Fa-f]6)^', $x));
}

function is_rgb($x)
{
	$x = strtolower($x);
	$x = str_replace(' ', null, $x);
	return (preg_match('^([0-9]3),([0-9]3),([0-9]3)^', $x));
}

function eoargs($array)
{
	if (is_even(count($array)))
	{
		$i = 0;
		do
		{
			$a = $i; $b = $i + 1;
			$x[crunch($array[$a])] = $array[$b];
			$i = $i + 2;
		} while ($i <= count($array));
		return $x;
	}
	else
	{
		exLog('eoargs(): requires an even number array.');
	}
}

function priority_select()
{
	$choices = func_get_args();
	$choices[] = false;

	do
	{
		$choice = array_shift($choices);
	} while ((is_null($choice) or $choice == '') and count($choices) > 0);
	
	exMethod("priority_select(): _choice = $choice");
	return $choice;		
}

function in_string($pointer, $input, $case=false, $word=false)
{
	if ($word)
	{
		$word = '\b';
	}
	else
	{
	   $word = null;
	}
	
	if (!$case)
	{
	   $case = 'i';
	}
	else
	{
	   $case = null;
	}
	
	$match = '/' . $word . $pointer . $word . '/' . $case;
	return (preg_match($match, $input));
}

function wcsubstr($str_String, $int_Length)
{
	/* (c) Ingo Renner (infoATingo-rennerDOTcom) www.ingo-renner.com 2003/01/18 */
	$str_String   = trim($str_String);
	$str_String   = substr($str_String, 0, $int_Length);
	$str_Revstr   = strrev($str_String);
	$str_LastChar = substr($str_Revstr, 0, 1);
	if ($str_LastChar == " ") 
	{
		return substr($str_String, 0, -1);
	}
	else
	{
		$arr_Words = explode(" ", $str_String);
		$int_Elements = count($arr_Words);
		if ($int_Elements == 1) 
		{
			return $arr_Words[0];
		}
		else
		{
			array_pop($arr_Words);
			$str_String = implode(" ", $arr_Words);
			return $str_String;
		}
	}
} 

function format_date($is=null, $want=null)
{
	$want = (is_null($want)) ? cfRead("Date Format") : $want ;
	return (is_null($is)) ? date($want) : date($want, strtotime($is)) ;
}

function whereami()
{
	$app = ($_GET['app'] != null) ? $_GET['app'] : system::application() ;
	$arg = ($_GET['arg'] != null) ? '/'.uncrunch($_GET['arg'], false) : null ;
	
	$gets = $_GET;
	unset($gets['app']); 
	unset($gets['arg']);
	
	$get = null;
	
	foreach($gets as $key => $value)
	{
		$get .= "$key=$value&";
	}
	
	$get = substr($get, 0, strlen($get)-1);
	
	if ($get != null)
	{
		$get = "?$get";
	}
	
	return www . $app . $arg . $get;
}

function str_remove($remove, $string)
{
	return str_replace($remove, null, $string);
}

function unique_seed()
{
   $random_one = (rand()%3);
	$random_two = (rand()%3);
	
	while($random_one != $random_two)
	{
		$random_one = (rand()%3);
		$random_two = (rand()%3);
	}
	
	return (($random_one + $random_two) / 2) . uniqid();
}

// THIS MAY NEED WORK?
function mime_type($input)
{
	if (file_exists($input)) 
	{
		$mime_types = cfRead('Mime Types');
		list($dir, $base, $ext, $file) = pathinfo($input);
		return isset($mime_types[$ext]) ? $mime_types[$ext] : 'application/octet-stream';
	}
	else
	{
		exLog("mime_type(): No file at $input.");
	}
}