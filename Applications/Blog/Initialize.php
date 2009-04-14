<?php # Blog [axiixc] : Application Initilization Code

/* No Cache or Preload? */

if(
	Registry::fetch('UAuth')->type(UATypeAdmin) and 
	Registry::fetch('UAuth')->role(explode(',', Conf::read('Blog Admin Roles')))
) Registry::write('Edit Links', true);