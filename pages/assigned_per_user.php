<?php

$page_owner = elgg_get_page_owner_entity();
if (!($page_owner instanceof ElggGroup)) {
	forward(REFERER);
}

if (!todos_enabled_for_container($page_owner)) {
	forward(REFERER);
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo('todos'), "todos/group/{$page_owner->getGUID()}/all");
elgg_push_breadcrumb($page_owner->name);

// build page elements
$title = elgg_echo('todos:filter:assigned_per_user');

$filter = elgg_view_menu('filter', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz'
));

$sidebar = elgg_view('todos/sidebar');

// get items
$dbprefix = elgg_get_config('dbprefix');
$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => false,
	'metadata_name_value_pairs' => array(
		array(
			'name' => 'assignee',
			'value' => 0,
			'operand' => '>',
		),
		array(
			'name' => 'order',
			'value' => 0,
			'operand' => '>=',
		),
	),
	'joins' => array(
		"JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid",
	),
	'wheres' => array(
		"ce.container_guid = {$page_owner->getGUID()}",
	),
);

$ordered_items = array();
$user_guids = array();

// assigned items
$batch = new ElggBatch('elgg_get_entities_from_metadata', $options);
foreach ($batch as $index => $item) {
	$assignee = (int) $item->assignee;
	
	$user_guids[] = $assignee;
	
	$order = (int) $item->due;
	if (empty($order)) {
		$order = mktime(0,0,0,1,1,2038);
	}
	
	$order += $index;
	
	$ordered_items[$assignee][$order] = $item;
}

// unassigned items
$unassigned_options = $options;
$unassigned_options['metadata_name_value_pairs'] = array(
	'name' => 'order',
	'value' => 0,
	'operand' => '>=',
);
$unassigned_options['wheres'][] = todos_get_unassigned_wheres_sql();

$unassigned_items = array();
$unassigned_batch = new ElggBatch('elgg_get_entities_from_metadata', $unassigned_options);
foreach ($unassigned_batch as $item) {
	
	$order = (int) $item->due;
	if (empty($order)) {
		$order = mktime(0,0,0,1,1,2038);
	}
	
	$order += $index;
	
	$unassigned_items[$order] = $item;
}

if (empty($user_guids) && empty($unassigned_items)) {
	// no items to show
	$page_data = elgg_view_layout('content', array(
		'title' => $title,
		'content' => elgg_echo('todos:assigned:no_results'),
		'filter' => $filter,
		'sidebar' => $sidebar,
	));
	
	echo elgg_view_page($title, $page_data);
	return;
}

// build content
$content = '';
if (!empty($user_guids)) {
	$user_options = array(
		'type' => 'user',
		'guids' => $user_guids,
		'joins' => array("JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"),
		'order_by' => "ue.name ASC",
	);
	
	$user_batch = new ElggBatch('elgg_get_entities', $user_options);
	foreach ($user_batch as $user) {
		
		$content .= elgg_view('todos/assigned_per_user', array(
			'user' => $user,
			'entities' => elgg_extract($user->getGUID(), $ordered_items, array()),
		));
	}
}

if (!empty($unassigned_items)) {
	$content .= elgg_view('todos/assigned_per_user', array(
		'user' => false, // need to do this otherwise ELgg fills in current user
		'entities' => $unassigned_items,
	));
}

// build page
$page_data = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'filter' => $filter,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $page_data);
