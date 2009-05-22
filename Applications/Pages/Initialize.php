<?php # Pages Initialization [axiixc]

if(
	Registry::fetch('UAuth')->type(UATypeAdmin) and 
	Registry::fetch('UAuth')->role(explode(',', Conf::read('Blog Admin Roles')))
) Registry::write('Edit Links', true);