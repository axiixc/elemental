<?php # Preferences Application

if (isset($_GET['arg']))
{
   $_GET['arg'] = 'system';
}

crunch($_GET['arg'], null);

foreach (fsGetPreferences() as $p_item)
{
   include $p_item . 'Info.php';
   
   $path_parts = pathinfo($p_item);
   $link_name = $path_parts['basename'];
   
   $preferences[] = array('name' => priority_select($preferences['name'], $link_name),
                          'link' => parseLink("ex://Preferences/$link_name")
                         );
}

