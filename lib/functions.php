<?php
/**
 * All helper functions are bundled here
 */

function todos_get_open_assigned_item_options($assignee = 0) {
	
	$assignee = sanitise_int($assignee, false);
	
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
