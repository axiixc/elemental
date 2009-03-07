<html>
<head><?php UIMetaHeadBlock(null,false,'php','php'); ?></head>
	
<body><!--[if IE]><center><![endif]-->
	<div class="head">
		<div class="headwrap">
			<div class="main">
				<img class="logo" src="<?php echo ui; ?>Images/logo_small_blue.png" onclick="" />
			</div>
		</div>
	</div>
	<div class="nav"><?php UIMenu('Main', 'leftNav'); UIMenu('Sub', 'rightNav'); ?></div>
	<div class="pagewrap">
		<div class="lCol"><?php UIContent(); ?></div>
		<div class="rCol"><?php UISidebar(); ?></div>
		<div class="clear"></div>
	</div>

	<div class="footer">
		<div class="col1">
			<ul class="niceMenu green">
				<li><h2>&nbsp;</h2></li>
				<li class="footer-special"><p><?php UIMetaFooter(); ?></p></li>
				<li class="footer-about"><?php _UIElemental(); ?></li>
			</ul>
		</div>
		<div class="col2"><?php UIMenu('MyWork', 'niceMenu blue', '&nbsp;'); ?></div>
		<div class="col3"><?php UIMenu('Affiliates', 'niceMenu pink', '&nbsp'); ?></div>
		<div class="clear"></div>
	</div>
	<?php UIJavascript(false); ?>
<!--[if IE]></center><![endif]--></body>
</html>