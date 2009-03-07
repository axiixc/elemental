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
		<div class="lCol">
			<h1>Error: Undefined Application</h1>
			<p>The Requested application could not be found. You may have been given an incorrect link. Try the <a href="javascript:;">home page</a>.</p>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="footer"><?php UIMetaFooter(); ?></div>	
	<?php UIJavascript(false); ?>
<!--[if IE]></center><![endif]--></body>
</html>
