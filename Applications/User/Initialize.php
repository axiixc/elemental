<?php # User Manager Initilization [axiixc]

# Deny any guests
Registry::fetch('UAuth')->require_type(UATypeBasic);