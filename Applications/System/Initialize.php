<?php # System Actions [axiixc]

# Redirects and Shortcuts
if(crunch($_GET['arg']) == 'home') header('Location: '.Conf::read("WWW Path").Conf::read("Application"));