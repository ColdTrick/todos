<?php
/**
 * TodoItem
 */
class Todo extends ElggObject {

	/**
	 * Marks a todo as completed
	 */
	public function complete() {
		unset($this->order);
		$this->completed = time();
	}
	
	/**
	 * Reorders todo to new position
	 * 
	 * @param int $pos new position
	 * 
	 * @return void
	 */
	public function moveToPosition($pos) {
		
		$options = array(
			'type' => 'object',
			'subtype' => $this::subtype,
			'container_guid' => $this->container_guid,
			'metadata_name_value_pairs' => array('order' => $pos),
			'limit' => 1
		);
		
		// is pos already taken move the other to pos + 1
		$existing_entity = elgg_get_entities_from_metadata($options);
				
		// update pos of current
		$this->order = $pos;
		
		if ($existing_entity) {
			$existing_entity = $existing_entity[0];
			$existing_entity->moveToPosition($pos + 1);
		}
		
	}	
}