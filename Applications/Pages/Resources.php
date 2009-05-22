<?php # Pages Resources [axiixc]

class Pages {
	
	public function __construct() {}
	
	/* Accepts array() */
	public function read($x) {
		if($x > 0) return $this->read_with_id($x);
		elseif(is_array($x)) {
			foreach($x as $y) $r[] = $this->read($y);
			return $r;
		} else return $this->read_with_slug($x);
	}
	
	public function read_with_slug($slug) {
		$result = MySQL::query("SELECT * FROM `[prefix]pages` WHERE `slug` LIKE CONVERT(_utf8 '%s' USING latin1) COLLATE latin1_swedish_ci LIMIT 0, 1", $slug);
		return (mysql_num_rows($result) > 0) ? mysql_fetch_assoc($result) : false ;
	}
	
	public function read_with_id($id) {
		$result = MySQL::query("SELECT *  FROM `[prefix]pages` WHERE `id` = %u", $id);
		return (mysql_num_rows($result) > 0) ? mysql_fetch_assoc($result) : false ;
	}
	
	/* Does not accept array() */
	public function write() {
		$args = func_get_args();
		$sid = array_shift($args);
		$args = eoargs($args);
		if(read($sid) === false or is_null($sid)) {
			if($sid > 0) write_with_id($sid, $args);
			else write_with_slug($sid, $args);
		} else {
			$id = (is_null($sid)) ? 'NULL' : $sid ;
			$this->add($id, $args);
		}
	}
	
	public function write_with_slug($sid, $args) {
		return MySQL::query("UPDATE `[database]`.`[prefix]pages` SET `slug` = '%s', `name` = '%s', `content` = '%s', `author` = '%s', `created` = '%s', `modified` = '%s' WHERE `[prefix]pages`.`slug` = %s LIMIT 1;", $args['slug'], $args['name'], $args['content'], $args['author'], $args['created'], $args['modified'], $sid);
		
	}
	
	public function write_with_id($sid, $args) {
		return MySQL::query("UPDATE `[database]`.`[prefix]pages` SET `slug` = '%s', `name` = '%s', `content` = '%s', `author` = '%s', `modified` = '%s' WHERE `[prefix]pages`.`id` = %s LIMIT 1;", $args['slug'], $args['name'], $args['content'], $args['author'], date('Ymd'), $sid);
	}
	
	public function add($id, $args) {
		return MySQL::query("INSERT INTO `[database]`.`[prefix]pages` (`id`, `slug`, `name`, `content`, `author`, `created`, `modified`) VALUES (NULL, '%s', '%s', '%s', '%s', '%6\$s', '%6\$s'');", $id, $args['slug'], $args['name'], $args['content'], $args['author'], date('Ymd'));
	}
	
	/* Accepts array() */
	public function delete($id) {
		if($id > 0) return $this->read_with_id($id);
		elseif(is_array($id)) {
			foreach($id as $y) $r[] = $this->delete($id);
			return $r;
		} else return $this->read_with_slug($id);
	}
	
	public function delete_with_slug($slug) {
		return MySQL::query("DELETE FROM `[prefix]pages` WHERE `[prefix]pages`.`slug` = '%s' LIMIT 1", $slug);
	}
	
	public function delete_with_id($id) {
		return MySQL::query('DELETE FROM `[prefix]pages` WHERE `[prefix]pages`.`id` = %u LIMIT 1', $id);
	}
	
	public function dump($limit=30, $offset=0, $direction=true) {
		$direction = ($direction === true) ? 'ASC' : 'DESC' ;
		$result = MySQL::query("SELECT * FROM `[prefix]pages` ORDER BY `id` %s LIMIT %u, %u", $direction, $offset, $limit);
		if(mysql_num_rows($result) > 0) {
			while($page = mysql_fetch_assoc($result)) $pages[$page['id']] = $page;
			return $pages;
		} else {
			return null;
		}
	}
	
	public function search() {
		# Um no clue how to do this efficiently ATM
	}
	
}