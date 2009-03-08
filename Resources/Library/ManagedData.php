<?php # Managed Data Library [ axiicx ] : Class Definition

class ManagedData {
	
	protected $entity;
	
	public function __construct($entity) {
		$this->entity = $entity;
	}
	
	public function read() {
		$args = func_get_args();
		$key = array_shift($args);
		
	}
	
	public function write() {
		$args = func_get_args();
		
	}
	
	public function dump() {
		
	}
	
}

# For installs
class MDConstructor extends ManagedData {
	
	public function __construct($entity) {
		$this->entity = $entity;
	}
	
	public function createEntity($entity, $type, $keys) {
		
	}
	
	public function deleteEntity($entity) {
		
	}
	
}