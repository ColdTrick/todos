<?php

elgg_gatekeeper();

$user_guid = (int) get_input('user_guid');
$user = get_user($user_guid);
if (!$user instanceof \ElggUser) {
	forward(REFERER);
}

if (!$user->canEdit()) {
	forward(REFERER);
}

$page_owner = elgg_get_page_owner_entity();
if (!todos_enabled_for_container($page_owner)) {
	forward(REFERER);
}

// breadcrumb
if ($page_owner instanceof \ElggUser) {
	elgg_push_breadcrumb(elgg_echo('todos'), 'todos');
} else {
	elgg_push_breadcrumb(elgg_echo('todos'), "todos/group/{$page_owner->guid}/all");
}

// page elements
$title = elgg_echo("todos:filter:assigned");

// open assigned todo items
$options = todos_get_open_assigned_item_options($user->guid, $page_owner->guid);
$content = elgg_list_entities($options);
if (empty($content)) {
	$content = elgg_echo('todos:assigned:no_results');
}

// closed assigned todo items
$options['limit'] = 10;
$options['metadata_name_value_pairs'] = array(
	array(
		'name' => 'assignee',
		'value' => $user->guid
	),
	array(
		'name' => 'completed',
		'value' => 0,
		'operand' => '>'
	)
);
$options['order_by_metadata'] = array(
	'name' => 'completed',
	'as' => 'integer',
	'direction' => 'desc'
);
$options['item_class'] .= ' todos-list-item-completed';
$options['list_class'] = 'todos-list';

$closed = elgg_list_entities($options);
if (!empty($closed)) {
	$content .= elgg_view_module('info', elgg_echo('todos:assigned:closed'), $closed, array('class' => 'mtl'));
}

$filter = elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));

$body = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => $filter,
	'content' => $content,
	'sidebar' => elgg_view('todos/sidebar')
));

echo elgg_view_page($title, $body);
