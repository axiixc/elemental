<?php # Pages Resources

class SharedPage
{
   
   static public $statements, $identifiers;
   
   private function __construct() {}
   
   public static function Initialize()
   {
      self::$statements['fetch_all_not_draft_ids'] = "SELECT `id`, `ref_id`, `name` FROM `[prefix]pages` WHERE `draft` != 1 ORDER BY `date_created` ASC";
      self::$statements['fetch_all_ids'] = "SELECT `id`, `ref_id`, `name` FROM `[prefix]pages` ORDER BY `date_created` ASC";
      self::$statements['fetch_with_id'] = "SELECT *  FROM `[prefix]pages` WHERE `id` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
      self::$statements['fetch_with_data'] = "SELECT * FROM `[prefix]pages` WHERE `%s` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
      self::$statements['update_with_id'] = "UPDATE `[database]`.`[prefix]pages` SET `%s` = '%s' WHERE CONVERT(`[prefix]pages`.`id` USING utf8) = '%s' LIMIT 1;";
      // self::$statements['update_with_data'] = "";
      self::$statements['create'] = "INSERT INTO `[database]`.`[prefix]pages` (`id`, `name`, `ref_name`, `content`, `author`, `date_modified`, `date_created`, `draft`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');";
      self::$statements['delete'] = "DELETE FROM `[prefix]pages` WHERE CONVERT(`[prefix]pages`.`id` USING utf8) = '%s' LIMIT 1";
      // self::$statements['update'] = "";
      self::$statements['is_page'] = "SELECT `id`, `ref_id` FROM `[prefix]pages` WHERE `id` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
      self::$statements['is_draft'] = "SELECT `draft` FROM `[prefix]pages` WHERE `id` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
      
      $_identifiers = query(self::$statements['fetch_all_ids']);
      if (is_resource($_identifiers))
      {
         if (mysql_num_rows($_identifiers) > 0)
         {
            while ($page = mysql_fetch_assoc($_identifiers))
            {
               self::$identifiers[] = array(
                  'id' => $page['id'], 
                  'ref_id' => $page['ref_id'], 
                  'name' => $page['name'], 
                  'ref_name' => $page['ref_name']
               );
            }
         }
         else
         {
            exLog('SharedPage->Initialize(): No pages, initializing with no identifiers.');
         }
      }
      else
      {
         exLog('SharedPage->Initialize(): Error fetching page identifiers.');
      }
         
   }
   
   public static function Fetch($offset = 0, $count = 30, $full = false, $drafts = false)
   {
      $data_source = ($drafts) ? $identifiers_all : $identifiers ;
      
      for ($i=$offset; $i < $count; $i++) { 
         $output[] = ($full) ? new Page($data_source[$i]['id']) : $data_source[$i]['id'] ;
      }
      
      return $output;
   }
   
}

SharedPage::Initialize();

class Page
{
   
   public $read_only;
   public $id, $name, $content, $author = array(), $date_modified, $date_created, $draft;
   
   public function __construct($page_id, $read_only = true)
   {   
      exMethod("Page: Initializing with input($page_id)");
      $this->read_only = $read_only;
      
      /* The "try to fetch" Block */
      // Try with Identifier
      exMethod("Page: Trying 'fetch with id'");
      $_page = query(SharedPage::$statements['fetch_with_id'], $page_id);
      
      // Try with Reference Identifier
      if (mysql_num_rows($_page) != 1)
      {
         exMethod("Page: Trying 'fetch with ref'");
         $_page = query(SharedPage::$statements['fetch_with_data'], 'ref_id', $page_id);
      }
      
      // Try with crunch(Name)
      if (mysql_num_rows($_page) != 1)
      {
         exMethod("Page: Trying 'fetch with name'");
         $_page = query(SharedPage::$statements['fetch_with_data'], 'ref_name', crunch($page_id, true));
      }
      
      if (is_resource($_page) and mysql_num_rows($_page) == 1)
      {
         $page = mysql_fetch_assoc($_page);
         $this->id = $page['id'];
         $this->ref_id = $page['ref_id'];
         $this->name = $page['name'];
         $this->ref_name = $page['ref_name'];
         
         // TODO add ex: parsing, add special comment parsing, add [[]] parsing
         $this->content = $page['content'];
         
         $this->author = new User($page['author']);
         $this->date_modified = $page['date_modified'];
         $this->date_created = $page['date_created'];
         
         $this->draft = ($page['draft']);
      }
      else
      {
         exLog("Page->__construct(): No page exists with ID, Ref ID, or name of $page_id.");
      }
   }
   
   public function delete()
   {
      query(SharedPage::$statements['delete'], $this->id);
   }
   
   /* all the editing is done in here, really! (er... see if you can't clean it up) */
   public function __set($key, $value)
   {
      $valid_update = array(
         'name' => 'name',
         'content' => 'content',
         'author' => 'author',
         'date_modified' => 'date_modified',
         'date_created' => 'date_created',
         'draft' => 'draft'
      );
      
      if ($read_only)
      {
         if ($key != 'id')
         {
            if ($key == 'author')
            {
               if (is_string($value) and strlen($value) == 32)
               {
                  $value = new User($value);
               }
               
               if (is_object($value) and isset($value->id))
               {
                  $this->user = $value;
               }
               else
               {
                  exLog("Pages->__set(): Could not update author. Neither user ID nor user Object given as value.");
               }
            }
            else if ($key == 'draft')
            {
               $value = ($value); // Force BOOL
               $this->draft = ($value);
            }
            else
            {
               $this->$key = $value;
            }
         }
         else
         {
            exLog('Page->__set(): ID is not editable');
         }
      }
      else
      {
         if (in_array($key, $valid_update))
         {
            if ($key == 'author')
            {
               if (is_string($value) and strlen($value) == 32)
               {
                  $value = new User($value);
               }
               
               if (is_object($value) and isset($value->id))
               {
                  query(SharedPage::$statements['update_with_id'], 'author', $value->id, $this->id);
                  $this->user = $value;
               }
               else
               {
                  exLog("Pages->__set(): Could not update author. Neither user ID nor user Object given as value.");
               }
            }
            else if ($key == 'draft')
            {
               $value = ($value); // Force BOOL
               query(SharedPage::$statements['update_with_id'], 'draft', '1', $this->id);
               $this->draft = ($value);
            }
            else
            {
               array_flip($valid_update);
               query(SharedPage::$statements['update_with_id'], $valid_update[$key], $value, $this->id);
               $this->$key = $value;
            }
         }
         else
         {
            exLog("Pages->__set(): Cannot update $key in mysql, only local copy modified.");
            $this->$key = $value;
         }
      }
   }
   
}

class BlankPage extends Page
{
   
   public function __construct()
   {
      $args = eoargs(func_get_args());
      $this->read_only = false;
      
      if (isset($args['clone']))
      {
         $_page = new Page($args['clone']);
         if (!is_null($_page->id))
         {
            $this->id = $_page->id;
            $this->name = $_page->name;
            $this->content = $_page->content;
            $this->author = $_page->author;
         }
         else
         {
            exLog("BlankPage->__construct(): Failed to clone from id {$args['clone']}");
         }
      }

      $this->$id = appPagesGenerateKey();
      $this->name = $args['name'];
      $this->ref_name = crunch($args['name'], true);
      $this->content = $args['content'];
   
      if (is_string($args['author']) and strlen($args['author'] == 32))
      {
         $args['author'] = new User($args['author']);
      }
   
      if (is_object($args['author']) and isset($args['author']->id))
      {
         $this->author = $args['author'];
      }
   
      $this->date_modified = $this->date_created = date(sdfDay);
      $this->draft = priority_select($args['draft'], false);
      
      // Is this needed?
      $draft = ($this->draft) ? '1' : '0';
      
      query(SharedPage::$statements['create'], $this->id, $this->name, $this->ref_name, $this->content, $this->author->id, $this->date_modified, $this->date_created, $draft);
      
      $__this = query(SharedPage::$statements['is_page'], $this->id);
      if (is_resource($__this) and mysql_num_rows($__this) > 0)
      {
         $_this = mysql_fetch_assoc($__this);
         $this->ref_id = $_this['ref_id'];
      }
   }
   
   /* __set() is handled by inheritance ;) */
   
}

function appPagesGenerateKey()
{
   do
   {
      $key = md5(unique_seed());
      $not_unique = appPagesIsPage($key);
   } while ($not_unique);
   
   return $key;
}

function appPagesIsPage($id)
{
   $_page = query(SharedPage::$statements['is_page'], mysql_safe($id));
   return (is_resource($_page) and mysql_num_rows($_key) == 0);
}

function appPagesIsDraft($id)
{
   if (appPagesIsPage($id))
   {
      $_page = query(SharedPage::$statements['is_draft'], mysql_safe($id));
      return (is_resource($_page) and mysql_num_rows($_page) == 1);
   }
   else
   {
      exLog("appPagesIsDraft(): Invalid reading, $id is not a valid page.");
      return false;
   }
}