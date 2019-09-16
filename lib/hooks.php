<?php
/**
 * Hooks for Todos
 */

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
		return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof \TodoItem) {
		return;
	}
	
	$user = elgg_extract('user', $params);
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	$list = $entity->getContainerEntity();
	if (empty($list)) {
		return false;
	}
	
	return $list->canWriteToContainer($user->guid, 'object', \TodoItem::SUBTYPE);
}
