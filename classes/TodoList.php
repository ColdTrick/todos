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
	
}
