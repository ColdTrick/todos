<?php
/**
 * Hooks for Todos
 */

/**
 * Adds the menu items to the todoitem
 *
 * @param string  $hook   name of the hook
 * @param string  $type   type of the hook
 * @param unknown $return return value
 * @param unknown $params hook parameters
 *
 * @return array
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
		elgg_load_js("lightbox");
		elgg_load_css("lightbox");
		
		$full_view = elgg_extract('full_view', $params, false);
		if ($full_view) {
			$return[] = ElggMenuItem::factory(array(
				'name' => 'toggle',
				'text' => $entity->isCompleted() ? elgg_echo('todos:todoitem:reopen') : elgg_echo('todos:todoitem:close'),
				'href' => 'action/todos/todoitem/toggle?guid=' . $entity->getGUID(),
				'is_action' => true
			));
		}
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => 'ajax/view/todos/todoitem/form?guid=' . $entity->getGUID(),
			'link_class' => 'elgg-lightbox'
		));
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => 'action/todos/todoitem/delete?guid=' . $entity->getGUID(),
			'confirm' => elgg_echo('deleteconfirm')
		));
	}
	
	return $return;
}

/**
 * Adds the menu items to the todolist
 *
 * @param string  $hook   name of the hook
 * @param string  $type   type of the hook
 * @param unknown $return return value
 * @param unknown $params hook parameters
 *
 * @return array
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
		elgg_load_js("lightbox");
		elgg_load_css("lightbox");
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => 'ajax/view/todos/todolist/form?guid=' . $entity->getGUID(),
			'link_class' => 'elgg-lightbox'
		));
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => 'action/todos/todolist/delete?guid=' . $entity->getGUID(),
			'confirm' => elgg_echo('deleteconfirm')
		));
	}
	
	return $return;
}

/**
 * Adds the filter menu for todos
 *
 * @param string  $hook   name of the hook
 * @param string  $type   type of the hook
 * @param unknown $return return value
 * @param unknown $params hook parameters
 *
 * @return array
 */
function todos_filter_menu_register($hook, $type, $return, $params) {
	if (elgg_get_context() !== 'todos') {
		return $return;
	}
	
	$base_url = 'todos';
	$page_owner = elgg_get_page_owner_entity();
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
		'href' => "$base_url?filter=completed"
	));

	$user = elgg_get_logged_in_user_entity();
	if (!empty($user)) {
		$return[] = ElggMenuItem::factory(array(
			'name' => 'assigned',
			'text' => elgg_echo('todos:filter:assigned'),
			'href' => 'todos/assigned/' . $user->username
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
 * @param unknown $params hook parameters
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
		case "todos_assigned":
			$return = "todos/assigned/" . $widget->getOwnerEntity()->username;
			break;
	}
	
	return $return;
}

/**
 * Adds the menu items to the owner_block of a group
 *
 * @param string  $hook   name of the hook
 * @param string  $type   type of the hook
 * @param unknown $return return value
 * @param unknown $params hook parameters
 *
 * @return array
 */
function todos_owner_block_menu_register($hook, $type, $return, $params) {
	
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
