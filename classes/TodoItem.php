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
		return elgg_generate_entity_url($this, 'view', null, [
			'title' => elgg_get_friendly_title($this->getDisplayName()),
		]);
	}
	
	/**
	 * Delete the to-do item
	 *
	 * @return bool
	 *
	 * @see ElggEntity::delete()
	 */
	public function delete($recursive = true) {
		
		// notify about delete
		$acting_user = elgg_get_logged_in_user_entity();
		
		$subject = elgg_echo('todos:notify:todoitem:delete:subject', [$this->getDisplayName()]);
		$message = elgg_echo('todos:notify:todoitem:delete:message', [
			$acting_user->getDisplayName(),
			$this->getDisplayName(),
		]);
		
		$this->notifyUsers($subject, $message);
		
		return parent::delete();
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
		$acting_user = elgg_get_logged_in_user_entity();
		
		$subject = elgg_echo('todos:notify:todoitem:completed:subject', [$this->getDisplayName()]);
		$message = elgg_echo('todos:notify:todoitem:completed:message', [
			$acting_user->getDisplayName(),
			$this->getDisplayName(),
			$this->getURL(),
		]);
		
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
		$acting_user = elgg_get_logged_in_user_entity();
		
		$subject = elgg_echo('todos:notify:todoitem:reopen:subject', [$this->getDisplayName()]);
		$message = elgg_echo('todos:notify:todoitem:reopen:message', [
			$acting_user->getDisplayName(),
			$this->getDisplayName(),
			$this->getURL(),
		]);
		
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
		
		if (!$this->canAssign($assignee)) {
			return false;
		}
		
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
				$this->unassignNotification();
			}
			
			unset($this->assignee);
		} else {
			$notify = false;
			
			if ($this->isAssigned()) {
				// reassign?
				$cur_assignee = $this->getAssignee();
				if ((int) $cur_assignee->guid !== $assignee) {
					
					$this->reassignNotification($new_assignee);
				}
			} else {
				// assigned
				$notify = true;
			}
			
			$this->assignee = $assignee;
			
			// only notify on change
			if ($notify) {
				$this->assignNotification();
			}
		}
		
		return true;
	}
	
	/**
	 * Check if an assigne can be assigned
	 *
	 * @param mixed $assignee       the new assignee (can be empty to unassign)
	 * @param bool  $register_error register an error (default: false)
	 *
	 * @return bool
	 */
	public function canAssign($assignee = null, $register_error = false) {
		
		$register_error = (bool) $register_error;
		
		if (empty($assignee)) {
			// unassign
			return true;
		}
		
		if (is_array($assignee) && count($assignee) > 1) {
			// can only assign to 1 user
			if ($register_error) {
				register_error(elgg_echo('todos:todoitem:error:assignee:too_many'));
			}
			return false;
		}
		
		if (is_array($assignee)) {
			$assignee = $assignee[0];
		}
		
		$assignee = sanitise_int($assignee, false);
		$user = get_user($assignee);
		if (empty($user)) {
			// no a user
			if ($register_error) {
				register_error(elgg_echo('todos:todoitem:error:assignee:no_user'));
			}
			return false;
		}
		
		if (!has_access_to_entity($this, $user)) {
			// assigne has no access
			if ($register_error) {
				register_error(elgg_echo('todos:todoitem:error:assignee:access', [$user->getDisplayName()]));
			}
			return false;
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
			return (int) $assignee->guid;
		}
		
		return $assignee;
	}
	
	/**
	 * Set the due date of this item
	 *
	 * @param int $timestamp the new due date
	 *
	 * @return void
	 */
	public function setDueDate($timestamp = 0) {
		$timestamp = sanitize_int($timestamp);
		$cur_timestamp = sanitize_int($this->due);
		
		if ($cur_timestamp !== $timestamp) {
			// notify about new date
			$acting_user = elgg_get_logged_in_user_entity();
			
			$subject = elgg_echo('todos:notify:todoitem:due:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:due:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				date('Y-m-d', $timestamp),
				$this->getURL(),
			]);
			
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
	 * @param int    $sender           the guid of the sender
	 *
	 * @return void
	 */
	public function notifyUsers($subject, $message, $sender = 0) {
		
		$sender = sanitize_int($sender, false);
		if (empty($sender)) {
			$sender = elgg_get_logged_in_user_guid();
		}
		
		if (empty($sender)) {
			return;
		}
		
		$user_guids = array(
			(int) $this->getOwnerGUID()
		);
		if ($this->isAssigned()) {
			$user_guids[] = $this->getAssignee(true);
		}
		
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
		
		notify_user($user_guids, $sender, $subject, $message);
	}
	
	/**
	 * Attach a file to the item
	 *
	 * @param string $filename the name of the file
	 * @param string $content  the file contents
	 *
	 * @return void
	 */
	public function attach($filename, $content) {
		
		if (empty($filename) || empty($content)) {
			return;
		}
		
		if (!$this->guid) {
			return;
		}
		
		$prefix = 'attachments/';
		
		$fh = new ElggFile();
		$fh->owner_guid = $this->guid;
		$fh->setFilename($prefix . $filename);
		
		if ($fh->exists()) {
			$i = 1;
			$fh->setFilename($prefix . $i . $filename);
			while ($fh->exists()) {
				$i++;
				$fh->setFilename($prefix . $i . $filename);
			}
		}
		
		$fh->open('write');
		$fh->write($content);
		$fh->close();
	}
	
	/**
	 * Get all attachted files
	 *
	 * @return array
	 */
	public function getAttachments() {
		$result = array();
		
		if (!$this->guid) {
			return $result;
		}
		
		$fh = new ElggFile();
		$fh->owner_guid = $this->guid;
		$fh->setFilename('attachments/');
		
		$base_path = $fh->getFilenameOnFilestore();
		$dh = opendir($base_path);
		if (empty($dh)) {
			return $result;
		}
		
		while (($filename = readdir($dh)) !== false) {
			if (!is_file($base_path . $filename)) {
				continue;
			}
			
			$result[] = $filename;
		}
		
		natcasesort($result);
		
		return $result;
	}
	
	/**
	 * Get the contents of one attchements
	 *
	 * @param string $filename the filename of the attachment
	 *
	 * @return false|string
	 */
	public function getAttachment($filename) {
		
		if (!$this->guid) {
			return false;
		}
		
		$fh = new ElggFile();
		$fh->owner_guid = $this->guid;
		$fh->setFilename("attachments/{$filename}");
		
		if (!$fh->exists()) {
			return false;
		}
		
		return $fh->grabFile();
	}
	
	/**
	 * Delete an attachment
	 *
	 * @param string $filename the filename of the attachment
	 *
	 * @return bool
	 */
	public function deleteAttachment($filename) {
		
		if (!$this->guid) {
			return false;
		}
		
		$fh = new ElggFile();
		$fh->owner_guid = $this->guid;
		$fh->setFilename("attachments/{$filename}");
		
		if (!$fh->exists()) {
			return false;
		}
		
		return $fh->delete();
	}
	
	/**
	 * Notify users about the unassignment of the to-do
	 *
	 * @return void
	 */
	protected function unassignNotification() {
		$acting_user = elgg_get_logged_in_user_entity();
		$assignee = $this->getAssignee();
		
		if ($acting_user->guid !== $assignee->guid) {
			// notify old assignee
			$subject = elgg_echo('todos:notify:todoitem:unassinged:assignee:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:unassinged:assignee:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				$this->getURL(),
			]);
			
			notify_user($assignee->guid, $acting_user->guid, $subject, $message);
		}
		
		if (($acting_user->guid !== (int) $this->getOwnerGUID()) && ($assignee->guid !== (int) $this->getOwnerGUID())) {
			// notify owner
			$subject = elgg_echo('todos:notify:todoitem:unassinged:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:unassinged:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				$assignee->getDisplayName(),
				$this->getURL(),
			]);
			
			notify_user($this->getOwnerGUID(), $acting_user->guid, $subject, $message);
		}
	}
	
	/**
	 * Notify users about the assignment of the to-do
	 *
	 * @return void
	 */
	protected function assignNotification() {
		$acting_user = elgg_get_logged_in_user_entity();
		$assignee = $this->getAssignee();
		
		if ($acting_user->guid !== $assignee->guid) {
			// notify assignee
			$subject = elgg_echo('todos:notify:todoitem:assinged:assignee:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:assinged:assignee:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				$this->getURL(),
			]);
			
			notify_user($assignee->guid, $acting_user->guid, $subject, $message);
		}
		
		if (($acting_user->guid !== (int) $this->getOwnerGUID()) && ($assignee->guid !== (int) $this->getOwnerGUID())) {
			// notify owner
			$subject = elgg_echo('todos:notify:todoitem:assinged:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:assinged:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				$assignee->getDisplayName(),
				$this->getURL(),
			]);
			
			notify_user($this->getOwnerGUID(), $acting_user->guid, $subject, $message);
		}
	}
	
	/**
	 * Notify users about reassignment
	 *
	 * @param ElggUser $new_assignee the new assignee
	 *
	 * @return void
	 */
	protected function reassignNotification(ElggUser $new_assignee) {
		$acting_user = elgg_get_logged_in_user_entity();
		$old_assignee = $this->getAssignee();
		$processed_guids = array();
		
		if ($acting_user->guid !== $old_assignee->guid) {
			// notify assignee
			$subject = elgg_echo('todos:notify:todoitem:unassinged:assignee:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:unassinged:assignee:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				$this->getURL(),
			]);
			
			notify_user($old_assignee->guid, $acting_user->guid, $subject, $message);
			
			$processed_guids[] = $old_assignee->guid;
		}
		
		if ($acting_user->guid !== $new_assignee->guid) {
			// notify assignee
			$subject = elgg_echo('todos:notify:todoitem:assinged:assignee:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:assinged:assignee:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				$this->getURL(),
			]);
			
			notify_user($new_assignee->guid, $acting_user->guid, $subject, $message);
			
			$processed_guids[] = $new_assignee->guid;
		}
		
		if (($acting_user->guid !== (int) $this->getOwnerGUID()) && !in_array($this->getOwnerGUID(), $processed_guids)) {
			// notify owner
			$subject = elgg_echo('todos:notify:todoitem:reassinged:subject', [$this->getDisplayName()]);
			$message = elgg_echo('todos:notify:todoitem:reassinged:message', [
				$acting_user->getDisplayName(),
				$this->getDisplayName(),
				$old_assignee->getDisplayName(),
				$new_assignee->getDisplayName(),
				$this->getURL(),
			]);
			
			notify_user($this->getOwnerGUID(), $acting_user->guid, $subject, $message);
		}
	}
}
