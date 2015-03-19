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
}
