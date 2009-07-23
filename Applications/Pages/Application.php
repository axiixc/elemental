<?php # Pages Application

if (is_string($_GET['arg']) and strlen($_GET['arg']) == 32) // View specific page
{
   $page = new Page(mysql_safe($_GET['arg']), true);
}
else // View all pages
{
   $pages = SharedPage::List(mysql_safe($_GET['offset']), mysql_safe($_GET['count']), true);
}