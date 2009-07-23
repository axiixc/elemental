<?php # Pages Application

if (!is_null($_GET['arg']) and $_GET['arg'] != '') // View specific page
{
   exMethod("Pages: View -> Single Page with ID {$_GET['arg']}");
   $page = new Page(mysql_safe($_GET['arg']), true);
   
   if (!is_null($page->id))
   {
      add(template('Single Page', 'Page Name: %1$s<br />Date Mod: %3$s | Date Created: %4$s<br />Author: %7$s<br /><pre style="color:green">%2$s</pre>'), $page->name, $page->content, format_date($page->date_modified), format_date($page->date_created), $page->author->id, $page->author->username, $page->author->display_name);
   }
   else
   {
      error('Page Not Found', 'You entered an invalid page.');
   }
}
else // View all pages
{
   exMethod("Pages: View -> List with offset({$_GET['offset']}) and count({$_GET['count']})");
   $pages = SharedPage::Fetch(mysql_safe($_GET['offset']), mysql_safe($_GET['count']), true);
   // DONT CARE YET
}