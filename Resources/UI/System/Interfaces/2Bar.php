<?php
Registry::fetch('Interface')->js_include(path(true).'Scripts/html_notifications.js');
if(Registry::fetch('Interface')->notification_count(UIError) > 0)
	Registry::fetch('Interface')->sidebar(null, 'title', 'Errors', 'content', Registry::fetch('Interface')->notification(UIError, true));
if(Registry::fetch('Interface')->notification_count(UINotice) > 0)
	Registry::fetch('Interface')->sidebar(null, 'title', 'Notices', 'content', Registry::fetch('Interface')->notification(UINotice, true));
include root.'Resources/UI/'.Registry::fetch('Interface')->ui.'/Style/Colorsheet.php';
?><html>
<head>
	<?php Registry::fetch('Interface')->head(); ?>
</head>
	
<body><!--[if IE]><center><![endif]-->
	<a name="top"></a>
	<div class="head">
		<div class="headwrap">
			<div class="main">
				<a href="javascript:axiixc_about_window();" class="about">About</a>
				<a href="#top" class="top">Top</a>
				<a href="<?php echo Registry::fetch('Interface')->parse_link('ex://System/Home'); ?>" class="noshow"><img class="logo" src="<?php path(); ?>Images/logo-small-blue.png" /></a>
			</div>
		</div>
	</div>

	<div class="nav"><?php Registry::fetch('Interface')->menu('Main', '<ul class="leftNav">'); Registry::fetch('Interface')->menu('Sub', '<ul class="rightNav">'); ?></div>

	<div class="pagewrap">
		<div class="lCol"><?php echo Registry::fetch('Interface')->content(); ?></div>
		<div class="rCol"><?php echo Registry::fetch('Interface')->sidebar(true, true); ?></div>
		<div class="clear"></div>
	</div>

	<div class="footer">
		<div class="col1">
			<ul class="niceMenu green" style="border-bottom: 35px solid <?php echo $green_dull; ?>">
				<li><h2>&nbsp;</h2></li>
				<li class="footer-special"><p><?php echo Registry::fetch('Interface')->footer; ?></p></li>
				<li class="footer-about"><center><a href="http://github.com/axiixc/elemental/tree"><img src="<?php path(); ?>Images/elemental-logo-grey.png" /></a></center></li>
			</ul>
		</div>
		<div class="col2"><?php Registry::fetch('Interface')->menu('Me', '<ul class="niceMenu blue" style="border-bottom: 35px solid '.$blue_dull.'"><li><h2>My Stuff</h2></li>'); ?></div>
		<div class="col3"><?php Registry::fetch('Interface')->menu('Affiliates', '<ul class="niceMenu pink" style="border-bottom: 35px solid '.$pink_dull.'"><li><h2>My Friends</h2></li>'); ?></div>
		<div class="clear"></div>
	</div>

	<?php Registry::fetch('Interface')->javascript(true); ?>
<!--[if IE]></center><![endif]--></body>
</html>