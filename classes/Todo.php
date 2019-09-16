<?php
/**
 * TodoItem
 */
class Todo extends ElggObject {
	
	/**
	 * Reorders todo to new position
	 *
	 * @param int $offset new position
	 *
	 * @return void
	 */
	public function moveToPosition($offset) {
		$current_order = $this->order;
		
		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => $this::SUBTYPE,
			'container_guid' => $this->container_guid,
			'order_by_metadata' => [
				'name' => 'order',
				'direction' => 'ASC',
				'as' => 'integer',
			],
			'limit' => false,
		]);
		if (empty($entities)) {
			// nothing to do
			return;
		}

		$current_pos_entity = elgg_extract($offset, $entities);
		if (!$current_pos_entity) {
			return;
		}
		
		$new_order = $current_pos_entity->order;
		
		$forward = false;
		if ($current_order < $new_order) {
			$forward = true;
		}
		
		$this->order = $new_order;
		
		foreach ($entities as $entity) {
			if ($entity->guid == $this->guid) {
				continue;
			}
			
			if ($forward) {
				if ($entity->order <= $new_order) {
					$entity->order--;
				}
			} else {
				if ($entity->order >= $new_order) {
					$entity->order++;
				}
			}
		}
	}
}
