<html>
<head>

<?php System::InterfaceHandler()->head(); ?>

</head>
<body>

<?php if (System::InterfaceHandler()->notificationCount(notification)) System::InterfaceHandler()->notifications(notification); ?>

<?php if (count(System::InterfaceHandler()->sidebars) > 0) System::InterfaceHandler()->sidebar(); ?>

<?php System::InterfaceHandler()->content(true); ?>

<?php System::InterfaceHandler()->javascript(false); ?>

</body>
</html>