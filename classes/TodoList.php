<?php
/**
 * TodoList
 */
class TodoList extends Todo {

	const SUBTYPE = "todolist";

	/**
	 * initializes the default class attributes
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		
		$this->order = time();
	}
	
	/**
	 * Returns URL to the entity
	 *
	 * @return string
	 *
	 * @see ElggEntity::getURL()
	 */
	public function getURL() {
		return elgg_get_site_url() . "todos/view/" . $this->getGUID() . "/" . elgg_get_friendly_title($this->title);
	}
	
	/**
	 * Saves object-specific attributes.
	 *
	 * @return bool
	 *
	 * @see ElggObject::save()
	 */
	public function save() {
		$res = parent::save();
		
		if (!$res) {
			return $res;
		}
		
		$this->updateTodoItemAccess();
		return $res;
	}
	
	/**
	 * Marks a list as active
	 */
	public function markAsActive() {
		$this->active = true;
	}
	
	/**
	 * Marks a list as inactive
	 */
	public function markAsInactive() {
		$this->active = false;
	}
	
	/**
	 * Check if list is active
	 */
	public function isActive() {
		return (bool) $this->active;
	}
	
	/**
	 * Validates if is list is complete and marks as complete
	 */
	public function validateListCompleteness() {
		$options = array(
			'type' => 'object',
			'subtype' => TodoItem::SUBTYPE,
			'count' => true,
			'container_guid' => $this->guid,
			'metadata_names' => array('order')
		);
		
		$incomplete_children_count = elgg_get_entities_from_metadata($options);
		if ($this->isActive() && ($incomplete_children_count === 0)) {
			$this->markAsInactive();
		} elseif (!$this->isActive() && $incomplete_children_count > 0) {
			$this->markAsActive();
		}
	}
	
	/**
	 * Update the access of todoitems in this list to match the list access
	 *
	 * @return void
	 */
	protected function updateTodoItemAccess() {
		
		$options = array(
			'type' => 'object',
			'subtype' => TodoItem::SUBTYPE,
			'limit' => false,
			'container_guid' => $this->getGUID(),
			'wheres' => array('e.access_id <> ' . $this->access_id)
		);
		
		$ia = elgg_set_ignore_access(true);
		
		$batch = new ElggBatch('elgg_get_entities', $options);
		$batch->setIncrementOffset(false);
		foreach ($batch as $item) {
			$item->access_id = $this->access_id;
			$item->save();
		}
		
		elgg_set_ignore_access($ia);
	}
	
}
