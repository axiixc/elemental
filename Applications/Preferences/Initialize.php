<?php # Preferences Auth Check [axiixc]

# No excpetions here
Registry::fetch('UAuth')->require_type(UATypeAdmin);
Registry::fetch('UAuth')->require_role('admin');