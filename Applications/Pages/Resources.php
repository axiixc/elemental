<?php # Pages Resources

class SharedPage
{
   
   static public $statements;
   
   private function __construct() {}
   
   public static function Initialize()
   {
      self::$statements['fetch_with_id'] = '';
      self::$statements['fetch_with_data'] = '';
      self::$statements['update_with_id'] = '';
      self::$statements['update_with_data'] = '';
      self::$statements['clone'] = '';
      self::$statements['create'] = '';
      self::$statements['update'] = '';
      self::$statements['is_page'] = '';
      self::$statements['is_draft'] = '';
   }
   
   public static function List($offset = 0, $count = 30, $full = false, $drafts = false)
   {
      // (full) ? return new Page() objects : return page ids ;
      // (drafts) ? include drafts : do not include drafts ;
   }
   
}

class Page
{
   
   public $read_only;
   public $id, $name, $content, $author = array(), $date_modified, $date_created, $draft;
   
   public function __construct($page_id, $read_only = true)
   {
      SharedPage::$statements['fetch_with_id'] = '';
      
      $this->read_only = $read_only;
      
      $_page = query(SharedPage::$statements['fetch_with_id'], $page_id);
      if (is_resource($_page) and mysql_num_rows($_page) > 0)
      {
         $page = mysql_fetch_assoc($_page);
         $id = $page['id'];
         $name = $page['name'];
         
         // TODO add ex: parsing, add special comment parsing, add [[]] parsing
         $content = $page['content'];
         
         $author = new User($page['user']);
         $date_modified = $page['date_modified'];
         $date_created = $page['date_created'];
         
         $draft = ($page['draft']);
      }
      else
      {
         exLog("Page->__construct(): No page at id $page_id");
      }
   }
   
   /* all the editing is done in here, really! */
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
         $this->$key = $value;
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
               $value = ($value) // Force BOOL
               query(SharedPage::$statements['update_with_id'], 'draft', '1', $this->id);
               $this->draft = ($value);
            }
            else
            {
               array_flip($valid_update);
               query(SharedPage::$statements['update_with_id'], $valid_update[$key], $value, $this->id);
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

new BlankPage extends Page
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
      $this->content = $args['content'];
   
      if (is_string($args['author']) and strlen($args['author'] == 32))
      {
         $args['author'] = new User($args['author']);
      }
   
      if (is_object($args['author']) and isset($args['author']->id))
      {
         $this->author = $args['author'];
      }
   
      $date_modified = $date_created = date(sdfDay);
      $draft = priority_select($args['draft'], false);
      
      // Is this needed?
      $draft = ($this->draft) ? '1' : '0';
      
      query(SharedPage::$statements['create'], $this->id, $this->name, $this->content, $this->author->id, $this->date_modified, $this->date_created, $draft);
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
   $_page = query(SharedPage::$statements['is_page'], $id);
   return (is_resource($_page) and mysql_num_rows($_key) == 1);
}

function appPagesIsDraft($id)
{
   
}