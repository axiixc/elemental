<?php # Database Configuration Read/Write [axiixc]

class Configuration
{
	
	public $cache, $statements;
	
	public function __construct()
	{
		$this->statements['sync'] = "SELECT * FROM `[database]`.`[prefix]configuration`";
		$this->statements['create'] = "INSERT INTO `[database]`.`[prefix]configuration` (`key`, `value`) VALUES ('%1\$s', '%2\$s');";
		$this->statements['update'] = "UPDATE `[database]`.`[prefix]configuration` SET `value` = '%2\$s' WHERE CONVERT(`[prefix]configuration`.`key` USING utf8) = '%1\$s' LIMIT 1;";
		$this->statements['delete'] = "DELETE FROM `[prefix]configuration` WHERE CONVERT(`[prefix]configuration`.`key` USING utf8) = '%1\$s' LIMIT 1";
		
		// Update the cache for the first time
		$this->Sync();
	}
	
	public function read($key)
	{
		crunch($key);
		if ($this->set($key))
		{
			return $this->cache[$key];
		}
		else
		{
         exLog("Configuration->read(): $key does not exist");
	   }
	}
	
	public function write($key, $value)
	{
		crunch($key);
		if (is_array($value))
		{
		   $value = "[ARRAY]" . serialize($value);
	   }
		
		if ($this->set($key))
		{
			$this->Update($key, $value);
		}
		else
		{
			$this->Create($key, $value);
		}
		
		$this->sync();
	}
	
	private function create($key, $value)
	{
		query($this->statements['create'], $key, $value);
	}
	
	private function update($key, $value)
	{
		query($this->statements['update'], $key, $value);
	}
	
	public function delete($key)
	{
		crunch($key);
		if ($this->set($key))
		{
			query($this->statements['delete'], $key);
		}
	}
	
	public function set($key)
	{
		crunch($key);
		return (array_key_exists($key, $this->cache));
	}
	
	public function sync()
	{
		$result = query($this->statements['sync']);
		while ($data = mysql_fetch_assoc($result))
		{
		   if (substr($data['value'], 0, 7) == '[ARRAY]')
		   {
		      $this->cache[$data['key']] = unserialize(substr($data['value'], 7));
	      }
	      else if ($data['value'] == '')
	      {
	         $this->cache[$data['key']] = null;
         }
         else if ($data['value'] == 'yes' or $data['value'] === 1)
         {
            $this->cache[$data['key']] = true;
         }
         else if ($data['value'] == 'no' or $data['value'] === 0)
         {
            $this->cache[$data['key']] = false;
         }
	      else
	      {
			   $this->cache[$data['key']] = $data['value'];
		   }
		}
	}
	
}

/* Plain Function Accessors (normal name convention) */

function cfRead($key)
{
	return System::Configuration()->read($key);
}

function cfWrite($key, $value)
{
	System::Configuration()->write($key, $value);
}

function cfDelete($key)
{
	System::Configuration()->delete($key);
}

function cfSet($key)
{
	return System::Configuration()->is_set($key);
}

function cdSync()
{
	System::Configuration()->sync();
}