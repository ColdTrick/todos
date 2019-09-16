<?php
use Elgg\Database\QueryBuilder;

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
function todos_get_open_assigned_item_options(int $assignee = 0, int $group_filter = 0) {

	$options = [
		'type' => 'object',
		'subtype' => TodoItem::SUBTYPE,
		'limit' => false,
		'metadata_name_value_pairs' => [
			[
				'name' => 'order',
				'value' => 0,
				'operand' => '>'
			],
		],
		'full_view' => false,
		'item_class' => 'todos-list-item',
		'list_class' => 'todos-list',
		'pagination' => false,
	];
	
	if (!empty($assignee)) {
		// assiged to specific person
		$options['metadata_name_value_pairs'][] = [
			'name' => 'assignee',
			'value' => $assignee,
		];
		$options['show_assignee'] = false;
	} else {
		// just assigned
		$options['metadata_name_value_pairs'][] = [
			'name' => 'assignee',
			'value' => 0,
			'operand' => '>',
		];
	}
	
	if (!empty($group_filter) && ($assignee !== $group_filter)) {
		$group_lists = elgg_get_entities([
			'type' => 'object',
			'subtype' => TodoList::SUBTYPE,
			'container_guid' => $group_filter,
			'limit' => false,
			'callback' => false,
			'metadata_name_value_pairs' => ['active' => true],
		]);
		
		if (!empty($group_lists)) {
			$guids = [];
			foreach ($group_lists as $row) {
				$guids[] = (int) $row->guid;
			}
			$options['wheres'] = [
				function(QueryBuilder $qb, $main_alias) use ($guids) {
					return $qb->compare("{$main_alias}.container_guid", 'in', $guids, ELGG_VALUE_GUID);
				},
			];
		}
	}
	
	return $options;
}

/**
 * Wrapper function to check if todos is enabled to user/group
 *
 * @param ElggEntity $container the user/group to check
 *
 * @return bool
 */
function todos_enabled_for_container(ElggEntity $container) {
	
	if ($container instanceof \ElggGroup) {
		return $container->isToolEnabled('todos');
	}
	
	return elgg_get_plugin_setting('enable_personal', 'todos') == 'yes';
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
		'wheres' => array("ce.container_guid = {$container->guid}"),
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
