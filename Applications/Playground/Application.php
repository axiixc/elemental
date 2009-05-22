<?php # Developer Playground [axiixc]

/*
$app = ($_GET['app'] != null) ? $_GET['app'] : Conf::read("Application") ;
$arg = ($_GET['arg'] != null) ? '/'.uncrunch($_GET['arg']) : null ;
$gets = $_GET;
unset($gets['app']); unset($gets['arg']);
$get = null;
foreach($gets as $key => $value) {
	$get .= "$key=$value&";
} $get = substr($get, 0, strlen($get)-1);
if($get != null) $get = "?$get";
echo Conf::read('WWW Path').$app.$arg.$get;

if(strtolower(Conf::read('WWW Path').$app.$arg.$get) == strtolower('http://localhost/Elemental/Playground/Foo?foo=bar&cat=dog')) add('YES');
else add('NO');
*/

// $myNav = new NavigationController('myNav', 25, 5);
// add("<h1>You are at page number $myNav->location of $myNav->total on myNav</h1>");
// $myNav->add();
// 
// $theirNav = new NavigationController('hisNav', 30, 10);
// add("<h1>But you are on page $theirNav->location of $theirNav->total on theirNav</h1>");
// $theirNav->add();

sidebar('div', 'title', 'Something Creative', 'content', 'Hey, cool look at this really awesome text, that is just sitting here waiting to be read. But you really shouldn\'t. I mean... after like 1000 blocks of text I get pretty uncreative');