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
		'list_class' => 'todos-list mtl',
		'pagination' => false
	);
	
	if (!empty($assignee)) {
		// assiged to specific person
		$options['metadata_name_value_pairs'][] = array(
			'name' => 'assignee',
			'value' => $assignee
		);
		$options['show_assignee'] = false;
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

/**
 * Get the where SQL to find unassigned todo items
 *
 * @return string
 */
function todos_get_unassigned_wheres_sql() {
	
	$dbprefix = elgg_get_config('dbprefix');
	$assignee_id = add_metastring('assignee');
	
	$where = "NOT EXISTS (
		SELECT 1 FROM {$dbprefix}metadata mda
		WHERE mda.entity_guid = e.guid
			AND mda.name_id = {$assignee_id}
	)";
	
	return $where;
}

/**
 * Get assignee filter options
 *
 * @param ElggEntity $container a container to filter the assignee for
 *
 * @return array
 */
function todos_get_assignee_filter_for_container(ElggEntity $container) {
	
	$result = array(
		'' => elgg_echo('todos:form:filters:assignee:all'),
		-1 => elgg_echo('todos:form:filters:assignee:unassigned'),
	);
	
	if (!($container instanceof ElggEntity)) {
		return $result;
	}
	
	$dbprefix = elgg_get_config('dbprefix');
	
	$metadata_options = array(
		'type' => 'object',
		'subtype' => TodoItem::SUBTYPE,
		'metadata_name' => 'assignee',
		'limit' => false,
		'joins' => array(
			"JOIN {$dbprefix}entities e ON n_table.entity_guid = e.guid",
			"JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid"
		),
		'wheres' => array("ce.container_guid = {$container->getGUID()}"),
	);
	$meta_batch = new ElggBatch('elgg_get_metadata', $metadata_options);
	$user_guids = array();
	foreach ($meta_batch as $metadata) {
		$user_guids[] = (int) $metadata->value;
	}
	
	if (empty($user_guids)) {
		return $result;
	}
	
	$user_guids = array_unique($user_guids);
	$user_options = array(
		'type' => 'user',
		'limit' => false,
		'guids' => $user_guids,
		'joins' => array("JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"),
		'order_by' => 'ue.name',
	);
	$user_batch = new ElggBatch('elgg_get_entities', $user_options, 'todos_get_assignee_filter_callback');
	foreach ($user_batch as $user_row) {
		$result[$user_row->guid] = $user_row->name;
	}
	
	return $result;
}

/**
 * Call back function for ElggBatch
 *
 * @see todos_get_assignee_filter_for_container()
 *
 * @param stdClass $row the db row
 *
 * @return stdClass
 */
function todos_get_assignee_filter_callback($row) {
	$result = new stdClass();
	$result->name = $row->name;
	$result->guid = (int) $row->guid;
	
	return $result;
}
