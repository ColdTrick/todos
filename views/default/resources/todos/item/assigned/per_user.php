<?php

$page_owner = elgg_get_page_owner_entity();
if (!($page_owner instanceof ElggGroup)) {
	forward(REFERER);
}

if (!todos_enabled_for_container($page_owner)) {
	forward(REFERER);
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo('todos'), "todos/group/{$page_owner->guid}/all");

// build page elements
$title = elgg_echo('todos:filter:assigned_per_user');

$filter_tabs = elgg_view_menu('filter', [
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
]);

$sidebar = elgg_view('todos/sidebar');

// get items
$dbprefix = elgg_get_config('dbprefix');
$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => false,
	'pagination' => false,
	'metadata_name_value_pairs' => [],
	'joins' => [
		"JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid",
	],
	'wheres' => [
		"ce.container_guid = {$page_owner->guid}",
	],
);

// filter options
$filters = get_input('filters');
// show completed
if (!elgg_extract('show_completed', $filters)) {
	$options['metadata_name_value_pairs'][] = [
		'name' => 'order',
		'value' => 0,
		'operand' => '>=',
	];
}

// due date filters
$date_filter = elgg_extract('date', $filters);
switch ($date_filter) {
	case 'today':
		$options['metadata_name_value_pairs'][] = [
			'name' => 'due',
			'value' => mktime(0, 0, 0),
			'operand' => '>=',
		];
		$options['metadata_name_value_pairs'][] = [
			'name' => 'due',
			'value' => mktime(0, 0, 0) + (24 * 60 * 60),
			'operand' => '<=',
		];
		break;
	case 'tomorrow':
		$day = (24 * 60 * 60);
		
		$options['metadata_name_value_pairs'][] = [
			'name' => 'due',
			'value' => mktime(0, 0, 0) + $day,
			'operand' => '>=',
		];
		$options['metadata_name_value_pairs'][] = [
			'name' => 'due',
			'value' => mktime(0, 0, 0) + (2 * $day),
			'operand' => '<=',
		];
		break;
	case 'overdue':
		$options['metadata_name_value_pairs'][] = [
			'name' => 'due',
			'value' => mktime(0, 0, 0),
			'operand' => '<=',
		];
		break;
	case 'range':
		$range_lower = (int) elgg_extract('range_lower', $filters);
		if (!empty($range_lower)) {
			$options['metadata_name_value_pairs'][] = [
				'name' => 'due',
				'value' => $range_lower,
				'operand' => '>=',
			];
		}
		
		$range_upper = (int) elgg_extract('range_upper', $filters);
		if (!empty($range_upper)) {
			$options['metadata_name_value_pairs'][] = [
				'name' => 'due',
				'value' => $range_upper,
				'operand' => '<=',
			];
		}
		break;
}

// assignee filter
$assignee = (int) elgg_extract('assignee', $filters);
if (!empty($assignee)) {
	
	if ($assignee === -1) {
		$options['wheres'][] = todos_get_unassigned_wheres_sql();
	} else {
		$options['metadata_name_value_pairs'][] = [
			'name' => 'assignee',
			'value' => $assignee,
		];
	}
}

// get data
$ordered_items = [];
$user_guids = [];

// assigned items
$batch = new ElggBatch('elgg_get_entities', $options);
foreach ($batch as $index => $item) {
	$assignee = (int) $item->assignee;
	
	if (!empty($assignee)) {
		$user_guids[] = $assignee;
	}
	
	$order = (int) $item->due;
	if (empty($order)) {
		$order = mktime(0, 0, 0, 1, 1, 2038); // needed an oder far away
	}
	
	$order += $index;
	
	$ordered_items[$assignee][$order] = $item;
}

// build content
$base_url = "todos/assigned_per_user/{$page_owner->guid}";
$form_vars = [
	'method' => 'GET',
	'action' => $base_url,
	'class' => empty($filters) ? 'hidden mbs' : 'mbs',
	'disable_security' => true,
	'id' => 'todos-filters',
];
$body_vars = [
	'filters' => $filters,
	'base_url' => $base_url,
	'container' => $page_owner,
];
$content = elgg_view('todos/filters_toggle', ['shown' => !empty($filters)]);
$content .= elgg_view_form('todos/filters', $form_vars, $body_vars);

if (empty($ordered_items)) {
	$content .= elgg_echo('todos:assigned:no_results');
} else {
	
	// assigned items
	if (!empty($user_guids)) {
		$user_options = [
			'type' => 'user',
			'guids' => $user_guids,
			'limit' => false,
			'joins' => ["JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"],
			'order_by' => "ue.name ASC",
		];
		$user_batch = new ElggBatch('elgg_get_entities', $user_options);
		foreach ($user_batch as $user) {
			$content .= elgg_view('todos/assigned_per_user', [
				'user' => $user,
				'entities' => $ordered_items[$user->guid],
			]);
		}
	}
	
	// unassigned items
	if (isset($ordered_items[0])) {
		$content .= elgg_view('todos/assigned_per_user', [
			'user' => false, // need to do this otherwise ELgg fills in current user
			'entities' => $ordered_items[0],
		]);
	}
}

// build page
$page_data = elgg_view_layout('content', [
	'title' => $title,
	'content' => $content,
	'filter' => $filter_tabs,
	'sidebar' => $sidebar,
]);

echo elgg_view_page($title, $page_data);
