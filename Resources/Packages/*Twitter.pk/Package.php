<?php

$username = 'axiixc';
$count = 5;

$twitter_code = <<<TWITTER
<div id="twitter_div">
	<ul id="twitter_update_list"><li>Loading ...</li></ul>
</div>
TWITTER;

UISidebarWrite('Twitter', $twitter_code, UISidebarSub);
UIJavascriptInclude("http://twitter.com/javascripts/blogger.js", false);
UIJavascriptInclude("http://twitter.com/statuses/user_timeline/$username.json?callback=twitterCallback2&amp;count=$count", false);