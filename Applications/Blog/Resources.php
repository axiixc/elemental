<?php # Blog [axiixc] : Public Resources

class Blog {
	
	public function __construct() {}
	
	public function read_list($start_date='now', $count='read-conf') {
		$start_date = ($start_date == 'now') ? date(DateSDF_YMD) : $start_date ;
		$count = ($count == 'read-conf') ? Conf::read("Blog Count") : $count ;
		$result = MySQL::query("SELECT *  FROM `[prefix]blog` WHERE `created` >= %u LIMIT 0, %u", $start_date, $count);
		if(mysql_num_rows($result) > 0) {
			while($post = mysql_fetch_assoc($result)) $return[] = $post;
			return $return;
		} else {
			Log::write("Blog::read_list($start_date, $count) no matches found for parameters.");
			return array();
		}
	}
	
	public function read($sid) {
		if($sid > 0) return $this->read_with_id($sid);
		elseif(is_array($sid)) {
			foreach($sid as $_sid) {
				$r = array();
				$r[] = $this->read($_sid);
			} return $r;
		} else return $this->read_with_slug($sid);
	}
	
	public function read_with_slug($slug) {
		$result = MySQL::query("SELECT *  FROM `[prefix]blog` WHERE `slug` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci", $slug);
		if(mysql_num_rows($result) > 0) {
			return mysql_fetch_assoc($result);
		} else {
			Log::write("Blog::read($slug) [read_with_date] no match found.");
			return array();
		}
	}
	
	public function read_with_id($id) {
		$result = MySQL::query("SELECT *  FROM `[prefix]blog` WHERE `id` = %u", $id);
		if(mysql_num_rows($result) > 0) {
			return mysql_fetch_assoc($result);
		} else {
			Log::write("Blog::read($slug) [read_with_id] no match found.");
			return array();
		}
	}
	
	public function read_with_date($start, $end) {
		$result = MySQL::query("SELECT *  FROM `[prefix]blog` WHERE `created` <= %u AND `modified` >= %u", $start, $end);
		if(mysql_num_rows($result) == 1) {
			return array(mysql_fetch_assoc($result));
		} elseif(mysql_num_rows($result) > 1) {
			while($row = mysql_fetch_assoc($result)) $r[] = $row;
			return $r;
		} else {
			Log::write("Blog::read_with_date($start, $end) No values returned");
			return array();
		}
	}
	
	public function write() {
		$args = func_get_args();
		$sid = array_shift($args);
		$args = eoargs($args);
		if(is_string($sid)) return $this->write_with_slug($sid, $args);
		elseif(is_null($sid)) return $this->new($args);
		else return $this->write_with_id($sid, $args);
	}
	
	public function write_with_slug($slug, $args) {
		return MySQL::query("UPDATE `[database]`.`[prefix]blog` SET `slug` = '%s', `title` = '%s', `content` = '%s', `tags` = '%s', `author` = '%u', `modified` = '%u' WHERE `[prefix]blog`.`slug` = '%s' LIMIT 1;", $args['slug'], $args['title'], $args['content'], $args['tags'], $args['author'], date(DateSDF_YMD), $args['slug']);
	}
	
	public function write_with_id($id, $args) {
		return MySQL::query("UPDATE `[database]`.`[prefix]blog` SET `slug` = '%s', `title` = '%s', `content` = '%s', `tags` = '%s', `author` = '%u', `modified` = '%u' WHERE `[prefix]blog`.`id` = %u LIMIT 1;", $args['slug'], $args['title'], $args['content'], $args['tags'], $args['author'], date(DateSDF_YMD), $args['id']);
	}
	
	public function add($args) {
		return MySQL::query("INSERT INTO `[database]`.`[prefix]blog` (`id`, `slug`, `title`, `content`, `tags`, `author`, `created`, `modified`) VALUES (NULL, '%s', '%s', '%s', '%s', '%u', '%7\$i', '%7\$i');", $args['id'], $args['slug'], $args['title'], $args['content'], $args['tags'], $args['author'], date(DateSDF_MYD));
	}
	
	public function delete($sid) {
		if(is_string($sid)) $this->delete_with_slug($sid);
		elseif(is_array($sid)) {
			foreach($sid as $_sid) $this->delete($sid);
		} else $this->delete_with_id($sid);
	}
	
	public function delete_with_slug($slug) {
		return MySQL::query("DELETE FROM `[prefix]blog` WHERE `[prefix]blog`.`slug` = '%s' LIMIT 1", $slug);
	}
	
	public function delete_with_id($id) {
		return MySQL::query("DELETE FROM `[prefix]blog` WHERE `[prefix]blog`.`id` = %u LIMIT 1", $id);
	}
	
	public function search($count, $with=null, $as=null) {
		# Not done yet
	}	
	
}