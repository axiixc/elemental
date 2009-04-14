<?php

if(isset($_COOKIE['fix_urls']) or $_COOKIE['fix_urls'] == true) {
	setcookie('fix_urls', false, time()-45465);
	echo 'Removed FIX URLS';
} else {
	setcookie('fix_urls', true, time()+545464);
	header('Location: http://axiixcdev.co.cc:8008/Elemental/');
}

?>