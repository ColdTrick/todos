<?php
/**
 * TodoItem
 */
class TodoItem extends Todo {

	const SUBTYPE = "todoitem";

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
	 * Saves object-specific attributes.
	 *
	 * @internal Object attributes are saved in the objects_entity table.
	 *
	 * @return bool
	 */
	public function save() {
		// Save ElggEntity attributes
		if (!parent::save()) {
			return false;
		}
	
		$parent_list = $this->getContainerEntity();
		$parent_list->validateListCompleteness();
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
	 * Marks a todo as completed
	 */
	public function complete() {
		unset($this->order);
		$this->completed = time();
		
		$parent_list = $this->getContainerEntity();
		$parent_list->validateListCompleteness();
	}
	
	/**
	 * Marks a todo as incomplete
	 */
	public function markAsIncomplete() {
		unset($this->completed);
		$this->order = time();
		
		$parent_list = $this->getContainerEntity();
		$parent_list->validateListCompleteness();
	}
	
	/**
	 * Check if todo is completed
	 */
	public function isCompleted() {
		if (!empty($this->completed)) {
			return true;
		}
		return false;
	}
}
