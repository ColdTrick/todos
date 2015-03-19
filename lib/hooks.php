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
	$entity = elgg_extract('entity', $params);
	if (!$entity) {
		return $return;
	}
		
	$return[] = ElggMenuItem::factory(array(
		'name' => 'edit', 
		'text' => elgg_echo('edit'), 
		'href' => '#', 
	));
	
	$return[] = ElggMenuItem::factory(array(
		'name' => 'delete', 
		'text' => elgg_echo('delete'), 
		'href' => 'action/todos/todoitem/delete?guid=' . $entity->guid, 
		'is_action' => true
	));
	
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
	$entity = elgg_extract('entity', $params);
	if (!$entity) {
		return $return;
	}
	
	$return[] = ElggMenuItem::factory(array(
		'name' => 'edit', 
		'text' => elgg_echo('edit'), 
		'href' => '#', 
	));
	
	$return[] = ElggMenuItem::factory(array(
		'name' => 'delete', 
		'text' => elgg_echo('delete'), 
		'href' => 'action/todos/todolist/delete?guid=' . $entity->guid,
		'is_action' => true
	));
	
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
	
	$return[] = ElggMenuItem::factory(array(
		'name' => 'active', 
		'text' => elgg_echo('todos:filter:active'), 
		'href' => 'todos'
	));

	$return[] = ElggMenuItem::factory(array(
		'name' => 'completed', 
		'text' => elgg_echo('todos:filter:completed'), 
		'href' => 'todos?filter=completed'
	));

	$return[] = ElggMenuItem::factory(array(
		'name' => 'assigned', 
		'text' => elgg_echo('todos:filter:assigned'), 
		'href' => 'todos?filter=assigned'
	));
	
	return $return;	
}