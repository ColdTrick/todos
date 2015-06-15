<?php
/**
 * Hooks for Todos
 */

/**
 * Adds the menu items to the todoitem
 *
 * @param string         $hook   name of the hook
 * @param string         $type   type of the hook
 * @param ElggMenuItem[] $return return value
 * @param array          $params hook parameters
 *
 * @return ElggMenuItem[]
 */
function todos_todoitem_menu_register($hook, $type, $return, $params) {
	
	if (empty($params) || !is_array($params)) {
		return $return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (empty($entity) || !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE)) {
		return $return;
	}
	
	if ($entity->canEdit()) {
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		
		elgg_load_js('elgg.userpicker');
		elgg_load_js('jquery.ui.autocomplete.html');
		
		$full_view = elgg_extract('full_view', $params, false);
		if ($full_view) {
			$return[] = ElggMenuItem::factory(array(
				'name' => 'toggle',
				'text' => $entity->isCompleted() ? elgg_echo('todos:todoitem:reopen') : elgg_echo('todos:todoitem:close'),
				'href' => "action/todos/todoitem/toggle?guid={$entity->getGUID()}",
				'is_action' => true
			));
		}
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "ajax/view/todos/todoitem/form?guid={$entity->getGUID()}",
			'link_class' => 'elgg-lightbox'
		));
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => "action/todos/todoitem/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm')
		));
	}
	
	return $return;
}

/**
 * Adds the menu items to the todolist
 *
 * @param string         $hook   name of the hook
 * @param string         $type   type of the hook
 * @param ElggMenuItem[] $return return value
 * @param array          $params hook parameters
 *
 * @return ElggMenuItem[]
 */
function todos_todolist_menu_register($hook, $type, $return, $params) {
	
	if (empty($params) || !is_array($params)) {
		return $return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (empty($entity) || !elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
		return $return;
	}
	
	if ($entity->canEdit()) {
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "ajax/view/todos/todolist/form?guid={$entity->getGUID()}",
			'link_class' => 'elgg-lightbox'
		));
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => "action/todos/todolist/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm')
		));
	}
	
	return $return;
}

/**
 * Adds the filter menu for todos
 *
 * @param string         $hook   name of the hook
 * @param string         $type   type of the hook
 * @param ElggMenuItem[] $return return value
 * @param array          $params hook parameters
 *
 * @return ElggMenuItem[]
 */
function todos_filter_menu_register($hook, $type, $return, $params) {
	if (elgg_get_context() !== 'todos') {
		return $return;
	}
	
	$page_owner = elgg_get_page_owner_entity();
	if (todos_enabled_for_container($page_owner)) {
		$base_url = 'todos';
		if (elgg_instanceof($page_owner, 'group')) {
			$base_url .= "/group/{$page_owner->getGUID()}/all";
		}
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'active',
			'text' => elgg_echo('todos:filter:active'),
			'href' => $base_url
		));
	
		$return[] = ElggMenuItem::factory(array(
			'name' => 'completed',
			'text' => elgg_echo('todos:filter:completed'),
			'href' => "{$base_url}?filter=completed"
		));
	}

	$user = elgg_get_logged_in_user_entity();
	if (!empty($user)) {
		$href = "todos/assigned/{$user->username}";
		if (elgg_instanceof($page_owner, 'group')) {
			$href .= "/{$page_owner->getGUID()}";
		}
		$return[] = ElggMenuItem::factory(array(
			'name' => 'assigned',
			'text' => elgg_echo('todos:filter:assigned'),
			'href' => $href
		));
	}
	
	return $return;
}

/**
 * Adds urls to the widgets
 *
 * @param string  $hook   name of the hook
 * @param string  $type   type of the hook
 * @param string  $return return value
 * @param arary   $params hook parameters
 *
 * @return string
 */
function todos_widget_title_url($hook, $type, $return, $params) {
	
	if (!empty($return)) {
		return $return;
	}
	
	if (empty($params) || !is_array($params)) {
		return $return;
	}
	
	$widget = elgg_extract('entity', $params);
	if (empty($widget) || !elgg_instanceof($widget, 'object', 'widget')) {
		return $return;
	}
	
	switch ($widget->handler) {
		case 'todos_assigned':
			$return = "todos/assigned/{$widget->getOwnerEntity()->username}";
			break;
		case 'todos_list':
			$list_guid = (int) $widget->list_guid;
			if (!empty($list_guid)) {
				$list = get_entity($list_guid);
				if (!empty($list) && elgg_instanceof($list, 'object', TodoList::SUBTYPE)) {
					$return = $list->getURL();
				}
			}
			break;
	}
	
	return $return;
}

/**
 * Adds the menu items to the owner_block of a group
 *
 * @param string         $hook   name of the hook
 * @param string         $type   type of the hook
 * @param ElggMenuItem[] $return return value
 * @param array          $params hook parameters
 *
 * @return ElggMenuItem[]
 */
function todos_group_owner_block_menu_register($hook, $type, $return, $params) {
	
	if (empty($params) || !is_array($params)) {
		return $return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (empty($entity) || !elgg_instanceof($entity, 'group')) {
		return $return;
	}
	
	if (!todos_group_enabled($entity)) {
		return $return;
	}
	
	$return[] = ElggMenuItem::factory(array(
		'name' => 'todos',
		'text' => elgg_echo('todos:owner_block:group'),
		'href' => "todos/group/{$entity->getGUID()}/all"
	));
	
	return $return;
}

/**
 * Adds the menu items to the owner_block of a user
 *
 * @param string         $hook   name of the hook
 * @param string         $type   type of the hook
 * @param ElggMenuItem[] $return return value
 * @param array          $params hook parameters
 *
 * @return ElggMenuItem[]
 */
function todos_user_owner_block_menu_register($hook, $type, $return, $params) {
	
	$user = elgg_get_logged_in_user_entity();
	if (empty($user)) {
		return $return;
	}
	
	if (empty($params) || !is_array($params)) {
		return $return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (empty($entity) || !elgg_instanceof($entity, 'user')) {
		return $return;
	}
	
	if ($entity->getGUID() !== $user->getGUID()) {
		return $return;
	}
	
	if (!todos_personal_enabled()) {
		return $return;
	}
	
	$return[] = ElggMenuItem::factory(array(
		'name' => 'todos',
		'text' => elgg_echo('todos:owner_block:user'),
		'href' => "todos"
	));
	
	return $return;
}

/**
 * Send notifications about due todo items
 *
 * @param string $hook   name of the hook
 * @param string $type   type of the hook
 * @param array  $return return value
 * @param array  $params hook parameters
 *
 * @return void
 */
function todos_cron_handler($hook, $type, $return, $params) {
	
	if (empty($params) || !is_array($params)) {
		return;
	}
	
	$time = (int) elgg_extract('time', $params, time());
	
	$upper = $time + (24 * 60 * 60);
	
	$options = array(
		'type' => 'object',
		'subtype' => TodoItem::SUBTYPE,
		'limit' => false,
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'due',
				'value' => $time,
				'operand' => '>='
			),
			array(
				'name' => 'due',
				'value' => $upper,
				'operand' => '<='
			)
		)
	);
	
	$ia = elgg_set_ignore_access(true);
	
	$batch = new ElggBatch('elgg_get_entities_from_metadata', $options);
	foreach ($batch as $entity) {
		$list = $entity->getContainerEntity();
		if (empty($list)) {
			// orphaned to-to item, should not happen
			continue;
		}
		
		$subject = elgg_echo('todos:notify:todoitem:due_soon:subject', array($entity->title));
		$message = elgg_echo('todos:notify:todoitem:due_soon:message', array(
			$entity->title,
			date('Y-m-d', $entity->due),
			$entity->getURL()
		));
		
		$entity->notifyUsers($subject, $message, $list->getContainerGUID());
	}
	
	elgg_set_ignore_access($ia);
}

/**
 * Check if a user can comment on a to-do item
 *
 * @param string $hook   name of the hook
 * @param string $type   type of the hook
 * @param bool   $return return value
 * @param array  $params hook parameters
 *
 * @return bool
 */
function todos_todoitem_can_comment($hook, $type, $return, $params) {
	
	if (empty($params) || !is_array($params)) {
		return $return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (empty($entity) || !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE)) {
		return $return;
	}
	
	$user = elgg_extract('user', $params);
	if (empty($user) || !elgg_instanceof($user, 'user')) {
		return $return;
	}
	
	$list = $entity->getContainerEntity();
	if (empty($list)) {
		return false;
	}
	
	return $list->canWriteToContainer($user->getGUID(), 'object', TodoItem::SUBTYPE);
}

/**
 * Check if a user can edit a to-do list
 *
 * @param string $hook   name of the hook
 * @param string $type   type of the hook
 * @param bool   $return return value
 * @param array  $params hook parameters
 *
 * @return bool
 */
function todos_todolist_can_edit($hook, $type, $return, $params) {
	
	if ($return) {
		// already allowed
		return $return;
	}
	
	if (empty($params) || !is_array($params)) {
		return $return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (empty($entity) || !elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
		return $return;
	}
	
	$user = elgg_extract('user', $params);
	if (empty($user) || !elgg_instanceof($user, 'user')) {
		return $return;
	}
	
	$container = $entity->getContainerEntity();
	if (empty($container) || !elgg_instanceof($container, 'group')) {
		return $return;
	}
	
	if ($container->isMember($user)) {
		return true;
	}
	
	return $return;
}
