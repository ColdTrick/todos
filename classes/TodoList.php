<?php
use Elgg\Database\QueryBuilder;

/**
 * TodoList
 */
class TodoList extends Todo {

	const SUBTYPE = 'todolist';

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
		return elgg_generate_entity_url($this, 'view', null, [
			'title' => elgg_get_friendly_title($this->getDisplayName()),
		]);
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
	 * {@inheritDoc}
	 */
	public function canEdit($user_guid = 0) {
		$result = parent::canEdit($user_guid);
		if ($result) {
			return $result;
		}
		
		$user = !$user_guid ? elgg_get_logged_in_user_entity() : get_entity($user_guid);
		if (!$user instanceof \ElggUser) {
			return $result;
		}
		
		$container = $entity->getContainerEntity();
		if (!$container instanceof \ElggGroup) {
			return $return;
		}
		
		return $container->isMember($user);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function canComment($user_guid = 0, $default = null) {
		return false;
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
		$incomplete_children_count = elgg_count_entities([
			'type' => 'object',
			'subtype' => TodoItem::SUBTYPE,
			'container_guid' => $this->guid,
			'metadata_names' => ['order'],
		]);
		
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
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$batch = elgg_get_entities([
				'type' => 'object',
				'subtype' => TodoItem::SUBTYPE,
				'limit' => false,
				'container_guid' => $this->guid,
				'wheres' => [
					function(QueryBuilder $qb, $main_alias) {
						return $qb->compare("{$main_alias}.access_id", '<>', $this->access_id, ELGG_VALUE_INTEGER);
					},
				],
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			foreach ($batch as $item) {
				$item->access_id = $this->access_id;
				$item->save();
			}
		});
	}
}
