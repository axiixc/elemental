<?php # Users Application

if ($_GET['arg'] == 'login') // Force show login
{
   if (System::Authority()->guest)
   {
      System::Authority()->showLogin();
   }
   else
   {
      error('You are already logged in', 'If you need to login as a different user <a href="' . parseLink('ex://Users/Logout') . '">logout</a> and log back in');
   }
}
else if ($_GET['arg'] == 'logout') // Force logout
{
   if (!System::Authority()->guest)
   {
      System::Authority()->sessionDestroy(
         System::Authority()->session['id']
      );
      
      error('You have been logged out');
   }
   else
   {
      error('You are not logged in', 'You must <a href="' . parseLink('ex://Users/Login') . '">Login</a> before you can logout.');
   }
}
else if ($_GET['arg'] > 0) // Profile
{
   // Not a priority
}
else if ($_GET['arg'] > 'account') // Account Manager
{
   // Not a priority
}