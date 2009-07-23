<?php # Current User and Blank User Object [ONLY SOME PROPERTIES ARE READ/WRITE]

class User
{
   private $statements;
   private $id, $username, $first_name, $middle_name, $last_name, $display_name;
   private $email, $registered, $configuration, $configuration_hash, $type, $role, $banned;
   private $needs_update = array();
   
   public function __construct($_id = null, $isID = true)
   {   
	   $this->statements['user_from_id'] = "SELECT *  FROM `[prefix]users` WHERE `id` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
	   $this->statements['user_from_username'] = "SELECT *  FROM `[prefix]users` WHERE `username` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci";
	   $this->statements['update_user_custom'] = "UPDATE `[database]`.`[prefix]users` SET %s WHERE CONVERT(`[prefix]users`.`id` USING utf8) = '%s' LIMIT 1;";
      
      if (is_null($_id))
      {
         exLog("User->__construct(): New user with no id, defaulting to guest.");
         $_id = 'guest';
         $_is_id = false;
      }
      
      if ($isID)
      {
         exMethod("User: Initializing with id($_id)");
         $_user = query($this->statements['user_from_id'], $_id);
      }
      else
      {
         exMethod("User: Initializing with username($_id)");
         $_user = query($this->statements['user_from_username'], $_id);
      }
      
      if (is_resource($_user) and mysql_num_rows($_user) == 1)
      {
         $user = mysql_fetch_assoc($_user);
      }
      
      $this->id = $user['id'];
      $this->username = $user['username'];
      $this->first_name = $user['fname'];
      $this->middle_name = $user['mname'];
      $this->last_name = $user['lname'];
      
      $this->display_name = $user['dname'];
      $this->display_name = str_replace('%u', $this->username, $this->display_name);
      $this->display_name = str_replace('%f', $this->first_name, $this->display_name);
      $this->display_name = str_replace('%m', $this->middle_name, $this->display_name);
      $this->display_name = str_replace('%l', $this->last_name, $this->display_name);
      
      $this->email = $user['email'];
      $this->registered = $user['registered'];
      $this->configuration = unserialize($user['configuration']);
      $this->configuration_hash = md5($this->configuration);
      $this->type = $user['type'];
      $this->role = $user['role'];
      $this->banned = ($user['banned']);
   }
   
   // Enforce the partial read-only polocy ;)
   public function __get($key)
   {
       return $this->$key;
   }

   public function __set($key, $value)
   {
      $valid_update = array(
         'username' => 'username',
         'fname' => 'first_name',
         'mname' => 'middle_name',
         'lname' => 'last_name', 
         'dname' => 'display_name',
         'email' => 'email',
         'type' => 'type',
         'role' => 'role',
         'banned' => 'banned'
      );
      
      if (in_array($key, $valid_update))
      {
         array_flip($valid_update);
         $this->$key = $value;
         $this->needs_update[$key] = $value;
      }
      else
      {
         exLog("User->__set(): $key does not allow modifications.");
      }
   }
   
   public function __destruct()
   {
      if (count($this->needs_update) > 0)
      {
         $sql_x = " ";
         foreach ($this->needs_update as $key => $value)
         {
            if ($key == 'id')
            {
               // pass, just make sure this doesn't slip ins
            }
            else if (is_int($value) or is_bool($value))
            {
               $sql_x .= "`$key` = $value, ";
            }
            else if (is_string($value))
            {
               $sql_x .= "`$key` = '$value', ";
            }
         }
         // Remove the last ", "
         $sql_x = substr($sql_x, 0, (strlen($sql_x) - 2));
      
         query($this->statements['update_custom'], $sql_x, $this->id);
      }
   }
   
}