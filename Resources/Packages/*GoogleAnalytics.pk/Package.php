<?php

function EXGoogleAnalytics() {
	$UUID = EXConfRead('googleanalytics-key');
	echo <<<GOOGLE
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	var pageTracker = _gat._getTracker("{$UUID}");
	pageTracker._trackPageview();
</script>
GOOGLE;
}

?>
