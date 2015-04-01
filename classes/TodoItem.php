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
		
		return true;
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
	 *
	 * @return void
	 */
	public function complete() {
		unset($this->order);
		$this->completed = time();
		
		// notify users
		$subject = elgg_echo('todos:notify:todoitem:completed:subject', array($this->title));
		$message = elgg_echo('todos:notify:todoitem:completed:message', array(
			$this->title,
			$this->getURL()
		));
		
		$this->notifyUsers($subject, $message);
		
		// check parent list
		$parent_list = $this->getContainerEntity();
		$parent_list->validateListCompleteness();
	}
	
	/**
	 * Marks a todo as incomplete
	 *
	 * @return void
	 */
	public function markAsIncomplete() {
		unset($this->completed);
		$this->order = time();
		
		// notify users
		$subject = elgg_echo('todos:notify:todoitem:reopen:subject', array($this->title));
		$message = elgg_echo('todos:notify:todoitem:reopen:message', array(
			$this->title,
			$this->getURL()
		));
		
		$this->notifyUsers($subject, $message);
		
		// check parent list
		$parent_list = $this->getContainerEntity();
		$parent_list->validateListCompleteness();
	}
	
	/**
	 * Check if todo is completed
	 *
	 * @return bool
	 */
	public function isCompleted() {
		return !empty($this->completed);
	}
	
	/**
	 * Check if the todo is assigned
	 *
	 * @return bool
	 */
	public function isAssigned() {
		return !empty($this->getAssignee());
	}
	
	/**
	 * Assign the todo, leave empty to unassign
	 *
	 * @param mixed $assignee (optional) the new assignee
	 *
	 * @return bool
	 */
	public function assign($assignee = null) {
		
		if (!empty($assignee) && is_array($assignee)) {
			if (count($assignee) > 1) {
				return false;
			}
			
			$assignee = $assignee[0];
		}
		
		$assignee = sanitize_int($assignee, false);
		$new_assignee = get_user($assignee);
		
		if (empty($new_assignee)) {
			if ($this->isAssigned()) {
				// unassigning
				$cur_assignee = $this->getAssignee();
				
				$subject = elgg_echo('todos:notify:todoitem:unassinged:subject', array($this->title));
				$message = elgg_echo('todos:notify:todoitem:unassinged:message', array(
					$this->title,
					$cur_assignee->name,
					$this->getURL()
				));
				
				$this->notifyUsers($subject, $message);
			}
			
			unset($this->assignee);
		} else {
			if ($this->isAssigned()) {
				// reassign?
				$cur_assignee = $this->getAssignee();
				if ((int) $cur_assignee->getGUID() !== $assignee) {
					// reassigned
					$subject = elgg_echo('todos:notify:todoitem:reassinged:subject', array($this->title));
					$message = elgg_echo('todos:notify:todoitem:reassinged:message', array(
						$this->title,
						$cur_assignee->name,
						$new_assignee->name,
						$this->getURL()
					));
					
					$this->notifyUsers($subject, $message, array($assignee));
				}
			} else {
				// assigned
				$subject = elgg_echo('todos:notify:todoitem:assinged:subject', array($this->title));
				$message = elgg_echo('todos:notify:todoitem:assinged:message', array(
					$this->title,
					$new_assignee->name,
					$this->getURL()
				));
				
				$this->notifyUsers($subject, $message, array($assignee));
			}
			
			$this->assignee = $assignee;
		}
		
		return true;
	}
	
	/**
	 * Return the assigned user
	 *
	 * @param bool $guid_only return only the guid of the assignee (if any)
	 *
	 * @return false|ElggUser|int
	 */
	public function getAssignee($guid_only = false) {
		if (empty($this->assignee)) {
			return false;
		}
		
		$assignee = get_user($this->assignee);
		if (empty($assignee)) {
			return false;
		}
		
		$guid_only = (bool) $guid_only;
		if ($guid_only) {
			return (int) $assignee->getGUID();
		}
		
		return $assignee;
	}
	
	public function setDueDate($timestamp = 0) {
		$timestamp = sanitize_int($timestamp);
		$cur_timestamp = sanitize_int($this->due);
		
		if ($cur_timestamp !== $timestamp) {
			// notify about new date
			$subject = elgg_echo('todos:notify:todoitem:due:subject', array($this->title));
			$message = elgg_echo('todos:notify:todoitem:due:message', array(
				$this->title,
				date('Y-m-d', $timestamp),
				$this->getURL()
			));
			
			$this->notifyUsers($subject, $message);
		}
		
		if (empty($timestamp)) {
			unset($this->due);
		} else {
			$this->due = $timestamp;
		}
	}
	
	/**
	 * Notify user and assignee
	 *
	 * @param string $subject          the subject
	 * @param string $message          the message
	 * @param int[]  $extra_user_guids (optional) additional user guids to notify
	 *
	 * @return void
	 */
	protected function notifyUsers($subject, $message, $extra_user_guids = array()) {
		
		if (!empty($extra_user_guids) && !is_array($extra_user_guids)) {
			$extra_user_guids = array($extra_user_guids);
		}
		
		$user_guids = array(
			(int) $this->getOwnerGUID()
		);
		if ($this->isAssigned()) {
			$user_guids[] = $this->getAssignee(true);
		}
		
		$user_guids = array_merge($user_guids, $extra_user_guids);
		$user_guids = array_unique($user_guids);
		
		foreach ($user_guids as $index => $user_guid) {
			if ($user_guid === elgg_get_logged_in_user_guid()) {
				unset($user_guids[$index]);
			}
		}
		
		if (empty($user_guids)) {
			// no recipients
			return;
		}
		
		notify_user($user_guids, elgg_get_logged_in_user_guid(), $subject, $message);
	}
}
