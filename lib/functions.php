<?php
/**
 * All helper functions are bundled here
 */

/**
 * returns an array to be used in elgg_get_* functions
 *
 * @param int $assignee     the guid of the assigned user
 * @param int $group_filter optional group filter
 *
 * @return array
 */
function todos_get_open_assigned_item_options($assignee = 0, $group_filter = 0) {
	
	$assignee = sanitise_int($assignee, false);
	$group_filter = sanitise_int($group_filter, false);
	
	$options = array(
		'type' => 'object',
		'subtype' => TodoItem::SUBTYPE,
		'limit' => false,
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'order',
				'value' => 0,
				'operand' => '>'
			)
		),
		'full_view' => false,
		'item_class' => 'todos-list-item',
		'list_class' => 'todos-list',
		'pagination' => false
	);
	
	if (!empty($assignee)) {
		// assiged to specific person
		$options['metadata_name_value_pairs'][] = array(
			'name' => 'assignee',
			'value' => $assignee
		);
	} else {
		// just assigned
		$options['metadata_name_value_pairs'][] = array(
			'name' => 'assignee',
			'value' => 0,
			'operand' => '>'
		);
	}
	
	if (!empty($group_filter) && ($assignee !== $group_filter)) {
		$group_lists = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => TodoList::SUBTYPE,
			'container_guid' => $group_filter,
			'limit' => false,
			'callback' => false,
			'metadata_name_value_pairs' => array('active' => true)
		));
		
		if (!empty($group_lists)) {
			$guids = array();
			foreach ($group_lists as $row) {
				$guids[] = (int) $row->guid;
			}
			
			$options['wheres'] = array('e.container_guid IN (' . implode(',', $guids) . ')');
		}
	}
	
	return $options;
}

/**
 * Check if group support is enabled
 *
 * @param ElggGroup $group (optional) check if the group has this enabled
 *
 * @return bool
 */
function todos_group_enabled(ElggGroup $group = null) {
	static $plugin_setting;

	if (!isset($plugin_setting)) {
		$plugin_setting = false;

		$setting = elgg_get_plugin_setting('enable_groups', 'todos');
		if ($setting === 'yes') {
			$plugin_setting = true;
		}
	}

	// shortcut
	if (!$plugin_setting) {
		return false;
	}

	if (empty($group) || !elgg_instanceof($group, 'group')) {
		return $plugin_setting;
	}

	if ($group->todos_enable === 'yes') {
		return true;
	}

	return false;
}

/**
 * Check if personal support is enabled
 *
 * @return bool
 */
function todos_personal_enabled() {
	static $plugin_setting;

	if (!isset($plugin_setting)) {
		$plugin_setting = false;

		$setting = elgg_get_plugin_setting('enable_personal', 'todos');
		if ($setting === 'yes') {
			$plugin_setting = true;
		}
	}

	return $plugin_setting;
}

/**
 * Wrapper function to check if todos is enabled to user/group
 *
 * @param ElggEntity $container the user/group to check
 *
 * @return bool
 */
function todos_enabled_for_container(ElggEntity $container) {
	
	if (empty($container) || !elgg_instanceof($container)) {
		return false;
	}
	
	if (elgg_instanceof($container, 'group')) {
		return todos_group_enabled($container);
	}
	
	return todos_personal_enabled();
}
